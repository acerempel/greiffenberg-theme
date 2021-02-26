<?php

// {{{ EXTERNAL STYLESHEETS & SCRIPTS
function greiffenberg_enqueue_styles() {
  wp_enqueue_style(
    // Handle that identifies this resource.
    'greiffenberg-main-stylesheet',
    // Path to the stylesheet.
    get_stylesheet_uri(),
    // Ensure the parent theme stylesheet is loaded. See twentytwentyone/functions.php.
    array('twenty-twenty-one-style'),
    // Ensure the stylesheet is reloaded (not cached) when the theme version is changed.
    wp_get_theme()->get('Version')
  );
  greiffenberg_enqueue_google_fonts();
}

add_action('wp_enqueue_scripts', 'greiffenberg_enqueue_styles');

function greiffenberg_dequeue_responsive_embeds() {
  // No responsive embeds! Embeds are disabled anyway, and we don't need the
  // extra script.
  wp_dequeue_script('twenty-twenty-one-responsive-embeds-script');
}

add_action('wp_enqueue_scripts', 'greiffenberg_dequeue_responsive_embeds', /* priority */ 11);

add_action('after_setup_theme', function () {
  remove_theme_support('responsive-embeds');
  add_editor_style('./block-editor-style.css');
});

function greiffenberg_enqueue_customize_preview_script() {
  wp_enqueue_script(
    'greiffenberg-customize-preview-script',
    get_stylesheet_directory_uri() . '/customize_preview.js',
    array('customize-preview'),
    wp_get_theme()->get('Version')
  );
}

add_action('customize_preview_init', 'greiffenberg_enqueue_customize_preview_script');

function greiffenberg_resource_hints($urls, $relation) {
  if ($relation === 'preconnect') {
    $urls['greiffenberg-google-fonts-preconnect'] = array('href' => 'https://fonts.gstatic.com', 'crossorigin' => true);
  }
  return $urls;
}

add_filter('wp_resource_hints', 'greiffenberg_resource_hints', 10 /* the default priority */, 2 /* accepts 2 arguments */);

// }}} EXTERNALS

// {{{ DEFAULTS

$greiffenberg_defaults = array(
  'body-line-height' => '1.6',
  'heading-line-height' => '1.4',
  'site-title-case' => 'uppercase',
  'font-family-base' => 'unset', // falls back to system-ui etc.
  'font-family-headings' => 'unset', // likewise
  'font-weight-headings' => '500',
  'background--darken-by' => '0',
  'spacing-unit' => '1',
  'sidebar-width' => '0.375',
);

$greiffenberg_units = array(
  'spacing-unit' => 'rem',
);

// }}} DEFAULTS

// {{{ FONTS

$greiffenberg_fonts = array(
  'unset' => array(
    'label' => "User's system default",
    'google_font_name' => false,
    'type' => 'sans-serif'
  ),
  'Crimson Pro' => array(
    'fallbacks' => 'Garamond, Georgia, Baskerville',
    'type' => 'serif',
    'variable' => true,
  ),
  'Lato' => array(
    'fallbacks' => '"Open Sans", Roboto',
    'type' => 'sans-serif',
    'variable' => false,
  ),
  'Libre Baskerville' => array(
    'fallbacks' => 'Baskerville, Georgia',
    'type' => 'serif',
    'variable' => false,
  ),
  'Lora' => array(
    'fallbacks' => 'Georgia, "Palatino Linotype", Palatino',
    'type' => 'serif',
    'variable' => true,
  ),
  'Newsreader' => array(
    'fallbacks' => 'Georgia, "Palatino Linotype", Palatino',
    'type' => 'serif',
    'variable' => true,
  ),
  'Nunito' => array(
    'fallbacks' => '"Open Sans", Ubuntu, "Segoe UI", -apple-system, BlinkMacSystemFont',
    'type' => 'sans-serif',
    'variable' => false,
  ),
  'Poppins' => array(
    'fallbacks' => '"Avenir Next", "Avenir", "Open Sans", Roboto',
    'type' => 'sans-serif',
    'variable' => false,
  ),
  'Source Serif Pro' => array(
    'fallbacks' => '"Palatino Linotype", Palatino, Georgia',
    'type' => 'serif',
    'variable' => false,
  ),
  'Vollkorn' => array(
    'fallbacks' => '"Hoefler Text", "Palatino Linotype", Palatino, Georgia',
    'type' => 'serif',
    'variable' => true,
  )
);

function greiffenberg_get_font_choices() {
  global $greiffenberg_fonts;
  $result = array();
  foreach ($greiffenberg_fonts as $value => $info) {
    $result[$value] = $info['label'] ?? $value;
  }
  return $result;
}

function greiffenberg_get_google_fonts_uri() {
  global $greiffenberg_fonts;
  function variant($ital, $wght) { return "$ital,$wght"; }

  $base_font = greiffenberg_get_mod('font-family-base');
  $headings_font = greiffenberg_get_mod('font-family-headings');
  $headings_font_weight = greiffenberg_get_mod('font-weight-headings');
  $page_title_font_weight = max(400, $headings_font_weight - 100);

  $base_font_variants = array(variant(0, 400), variant(0, 700), variant(1, 400));
  $is_customize_preview = is_customize_preview();
  if ($is_customize_preview) {
    $headings_font_variants =
      $greiffenberg_fonts[$headings_font]['variable']
      ? array(
          variant(0, '300..700'),
          variant(1, '300..700'),
        )
      : array(
          variant(0, 300), variant(0, 400), variant(0, 500),
          variant(0, 600), variant(0, 700),
        );
  } else {
    $headings_font_variants = array(
      variant(0, $headings_font_weight),
      variant(1, $headings_font_weight),
      variant(0, $page_title_font_weight),
    );
  }

  if ($base_font === $headings_font) {
    // If in customizer preview, headings variants includes all variants, so no
    // duplicates.
    $merged_variants = $is_customize_preview
      ? $headings_font_variants
      : array_merge($base_font_variants, $headings_font_variants);
    $desired_fonts = array(array(
      'family' => $base_font,
      'variants' => $merged_variants,
    ));
  } else {
    $desired_fonts = array(
      array('family' => $base_font, 'variants' => $base_font_variants),
      array('family' => $headings_font, 'variants' => $headings_font_variants),
    );
  }

  $desired_fonts_filtered = array_filter($desired_fonts, function ($font) use ($greiffenberg_fonts) {
    $font_info = $greiffenberg_fonts[$font['family']];
    return !(array_key_exists('google_font_name', $font_info)) || (array_key_exists('google_font_name', $font_info) && $font_info['google_font_name'] !== false);
  });
  if (count($desired_fonts_filtered) === 0) return null;

  $uri = "https://fonts.googleapis.com/css2?";
  foreach ($desired_fonts_filtered as $font) {
    $uri .= 'family='; $uri .= str_replace(' ', '+', $greiffenberg_fonts[$font['family']]['google_font_name'] ?? $font['family']);
    $variants = array_unique($font['variants'], SORT_STRING);
    sort($variants, SORT_STRING);
    $uri .= ':ital,wght@'; $uri .= implode(';', $variants);
    $uri .= '&';
  }
  $uri .= 'display=swap';

  return $uri;
}

function greiffenberg_enqueue_google_fonts() {
  $gfonts_uri = greiffenberg_get_google_fonts_uri();
  if ($gfonts_uri !== null) {
    wp_enqueue_style(
      'greiffenberg-google-fonts',
      $gfonts_uri,
      array(), // No dependencies.
      null // Don't add a version.
    );
  }
}

function greiffenberg_font_property_value($font) {
  global $greiffenberg_fonts;
  $font_info = $greiffenberg_fonts[$font];
  if ($font === 'unset') return "$font;";
  $font_name = $font_info['google_font_name'] ?? $font;
  $font_info_fallback = $font_info['fallbacks'];
  $font_info_type = $font_info['type'];
  if (null === $font_info_fallback && null === $font_info_type) {
    $fallback = '';
  } else if (null !== $font_info_fallback && null === $font_info_type) {
    $fallback = ", $font_info_fallback";
  } else if (null === $font_info_fallback && null !== $font_info_type) {
    $fallback = ", $font_info_type";
  } else if (null !== $font_info_fallback && null !== $font_info_type){
    $fallback = ", $font_info_fallback, $font_info_type";
  }
  return "'$font_name'" . $fallback;
}

// }}} FONTS

// {{{ CUSTOMIZER CUSTOMIZATION

function greiffenberg_customize($customizer) {
  global $greiffenberg_defaults;

  // Section: TYPOGRAPHY {{{

  // Line height {{{
  $customizer->add_setting('body-line-height', array(
    'capability' => 'edit_theme_options', // what is this?
    'default' => $greiffenberg_defaults['body-line-height'],
    'transport' => 'postMessage',
    'sanitize_callback' => function ($number) { return (float) $number; }
  ));

  $customizer->add_control('body-line-height', array(
    'label' => 'Line height – body text',
    'type' => 'number',
    'input_attrs' => array('min' => '1.0', 'max' => '3.0', 'step' => '0.1'),
    'section' => 'typography'
  ));

  $customizer->add_setting('heading-line-height', array(
    'capability' => 'edit_theme_options', // what is this?
    'default' => $greiffenberg_defaults['heading-line-height'],
    'transport' => 'postMessage',
    'sanitize_callback' => function ($number) { return (float) $number; }
  ));

  $customizer->add_control('heading-line-height', array(
    'label' => 'Line height – headings',
    'type' => 'number',
    'input_attrs' => array('min' => '1.0', 'max' => '3.0', 'step' => '0.1'),
    'section' => 'typography'
  ));
  // }}} Line height

  // {{{ Case
  $customizer->add_setting('site-title-case', array(
    'capability' => 'edit_theme_options',
    'default' => $greiffenberg_defaults['site-title-case'],
    'transport' => 'postMessage',
    'sanitize_callback' => function ($bool) { return $bool ? 'uppercase' : 'none'; }
  ));

  $customizer->add_control('site-title-case', array(
    'label' => 'Display site title in all caps',
    'type' => 'checkbox',
    'section' => 'typography'
  ));
  // }}} Case

  // {{{ Font family
  $customizer->add_setting('font-family-base', array(
    'capability' => 'edit_theme_options',
    'default' => $greiffenberg_defaults['font-family-base'],
    'transport' => 'refresh' // note: change this to postMessage once we figure that out
  ));

  $customizer->add_setting('font-family-headings', array(
    'capability' => 'edit_theme_options',
    'default' => $greiffenberg_defaults['font-family-headings'],
    'transport' => 'refresh' // note: change this to postMessage once we figure that out
  ));

  $font_choices = greiffenberg_get_font_choices();

  $customizer->add_control('font-family-base', array(
    'label' => 'Font family – body text',
    'type' => 'select',
    'choices' => $font_choices,
    'section' => 'typography'
  ));

  $customizer->add_control('font-family-headings', array(
    'label' => 'Font family – headings',
    'type' => 'select',
    'choices' => $font_choices,
    'section' => 'typography'
  ));
  // }}} Font family

  // {{{ Font weight
  $font_weight_choices = array(
    '300' => 'Light',
    '400' => 'Regular',
    '500' => 'Medium',
    '600' => 'Semibold',
    '700' => 'Bold'
  );

  $customizer->add_setting('font-weight-headings', array(
    'capability' => 'edit_theme_options',
    'default' => $greiffenberg_defaults['font-weight-headings'],
    'transport' => 'postMessage',
  ));

  $customizer->add_control('font-weight-headings', array(
    'label' => 'Font weight – headings',
    'type' => 'select',
    'choices' => $font_weight_choices,
    'section' => 'typography'
  ));
  // }}} Font weight

  $customizer->add_section('typography', array(
    'title' => 'Typography',
    'description' => 'Ajust how text is displayed – line height, font family, etc.',
    'priority' => 45
  ));

  // }}} Section: TYPOGRAPHY

  // {{{ Section: SPACING

  $customizer->add_setting('spacing-unit', array(
    'capability' => 'edit_theme_options',
    'default' => $greiffenberg_defaults['spacing-unit'],
    'transport' => 'postMessage',
    'sanitize_callback' => function ($number) { return (float) $number; },
  ));

  $customizer->add_control('spacing-unit', array(
    'label' => 'Spacing',
    'description' => 'This controls the amount of space between various items, including between paragraphs.',
    'type' => 'range',
    'input_attrs' => array('min' => '0.5', 'max' => '3.0', 'step' => '0.1'),
    'section' => 'spacing',
  ));
  
  $customizer->add_setting('sidebar-width', array(
    'capability' => 'edit_theme_options',
    'default' => $greiffenberg_defaults['sidebar-width'],
    'transport' => 'postMessage',
    'sanitize_callback' => function ($input) { return $input; },
  ));
  
  $customizer->add_control('sidebar-width', array(
    'label' => 'Sidebar width',
    'description' => 'Adjust the width of the sidebar, on screens large enough for it to appear.',
    'type' => 'range',
    'input_attrs' => array('min' => '0.25', 'max' => '0.75', 'step' => '0.125'),
    'section' => 'spacing',
  ));

  $customizer->add_section('spacing', array(
    'title' => 'Spacing',
    'description' => 'Ajust the spacing between various visual elements of the page. These settings each control many parts of the page at once; in order to evaluate their effects, it\'s best to try them out and observe the live preview.',
    'priority' => 50
  ));

  // }}} Section: SPACING

  // {{{ Section: BACKGROUND
  $customizer->add_setting('background--darken-by', array(
    'capability' => 'edit_theme_options',
    'default' => $greiffenberg_defaults['background--darken-by'],
    'transport' => 'postMessage',
  ));

  $customizer->add_control('background--darken-by', array(
    'label' => 'Darken background image',
    'description' => '100% will result in a black background; 0% will leave the background image unchanged.',
    'type' => 'range',
    'input_attrs' => array('min' => '0', 'max' => '1', 'step' => '0.01'),
    'section' => 'background_image',
  ));
  // }}} Section: BACKGROUND
}

add_action('customize_register', 'greiffenberg_customize');

// }}} CUSTOMIZER

function greiffenberg_get_mod($id) {
  global $greiffenberg_defaults, $greiffenberg_units;
  return get_theme_mod($id, $greiffenberg_defaults[$id]) . ($greiffenberg_units[$id] ?? '');
}

// {{{ INLINE STYLES
// (in <style> tags, not in style attributes)

$greiffenberg_css_variables = array(
  array('global--line-height-body', 'body-line-height'),
  array('global--line-height-heading', 'heading-line-height'),
  array('branding--title--text-transform', 'site-title-case'),
  array('heading--font-weight', 'font-weight-headings'),
  array('font-base', 'font-family-base'),
  array('font-headings', 'font-family-headings'),
  array('background--darken-by', 'background--darken-by'),
  array('spacing-unit', 'spacing-unit'),
  array('sidebar-width', 'sidebar-width'),
);

function greiffenberg_css_variable($variable, $value, $important = false) {
  $mod = greiffenberg_get_mod($value);
  $prop_value = strpos($value, 'font-family-') === 0 ? greiffenberg_font_property_value($mod) : $mod;
  return "--$variable: " . $prop_value . ($important ? ' !important;' : ';');
}

function greiffenberg_get_css_variables($important) {
  global $greiffenberg_css_variables;
  return array_reduce($greiffenberg_css_variables, function ($acc, $var) use ($important) {
    return $acc . greiffenberg_css_variable($var[0], $var[1], $important);
  }, '');
}

function greiffenberg_print_inline_style() {
  echo '<style>:root {' . greiffenberg_get_css_variables(false) . '}</style>';
}

add_action('wp_head', 'greiffenberg_print_inline_style');

function greiffenberg_enqueue_block_editor_assets() {
  greiffenberg_enqueue_google_fonts();
  wp_add_inline_style('wp-edit-post', '.editor-styles-wrapper {' . greiffenberg_get_css_variables(true) . '}');
}

add_action('enqueue_block_editor_assets', 'greiffenberg_enqueue_block_editor_assets');

// }}} INLINE STYLES
