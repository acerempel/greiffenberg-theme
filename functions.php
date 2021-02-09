<?php

function greiffenberg_styles() {
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
}

add_action('wp_enqueue_scripts', 'greiffenberg_styles');

function greiffenberg_customize($customizer) {
  $customizer->add_setting('body-line-height', array(
    'capability' => 'edit_theme_options', // what is this?
    'default' => '1.5',
    'transport' => 'postMessage',
    'sanitize_callback' => function ($number) { return (float) $number; }
  ));

  $customizer->add_control('body-line-height', array(
    'label' => 'Line height of body text',
    'type' => 'number',
    'input_attrs' => array('min' => '0.5', 'max' => '3.0', 'step' => '0.1'),
    'section' => 'typography'
  ));

  $customizer->add_section('typography', array(
    'title' => 'Typography',
    'description' => 'Ajust how text is displayed – line height, font family, etc.',
    'priority' => 45
  ));

  $customizer->add_setting('vertical-spacing', array(
    'capability' => 'edit_theme_options',
    'default' => '1',
    'transport' => 'postMessage',
    'sanitize_callback' => function ($number) { return (float) $number; }
  ));

  $customizer->add_control('vertical-spacing', array(
    'label' => 'Vertical spacing',
    'description' => 'This controls the amount of vertical space between various elements of the page, including paragraphs.',
    'type' => 'number',
    'input_attrs' => array('min' => '0.5', 'max' => '3.0', 'step' => '0.1'),
    'section' => 'spacing'
  ));

  $customizer->add_setting('horizontal-spacing', array(
    'capability' => 'edit_theme_options',
    'default' => '0.8',
    'transport' => 'postMessage',
    'sanitize_callback' => function ($number) { return (float) $number; }
  ));

  $customizer->add_control('horizontal-spacing', array(
    'label' => 'Horizontal spacing',
    'description' => 'This controls the amount of horizontal space around various parts of the page.',
    'type' => 'number',
    'input_attrs' => array('min' => '0.5', 'max' => '3.0', 'step' => '0.1'),
    'section' => 'spacing'
  ));

  $customizer->add_section('spacing', array(
    'title' => 'Spacing',
    'description' => 'Ajust the spacing between various visual elements of the page',
    'priority' => 50
  ));

}

add_action('customize_register', 'greiffenberg_customize');

function greiffenberg_custom_css() {
  echo '<style> body {';
  echo '--global--line-height-body:' . get_theme_mod('body-line-height', '1.5') . 'rem;'; 
  echo '--global--spacing-vertical:' . get_theme_mod('vertical-spacing', '1') . 'rem;';
  echo '--global--spacing-horizontal:' . get_theme_mod('horizontal-spacing', '0.8') . 'rem;';
  echo '}</style>';
}

add_action('wp_head', 'greiffenberg_custom_css');

function greiffenberg_customize_preview_script() {
  wp_enqueue_script(
    'greiffenberg-customize-preview-script',
    get_stylesheet_directory_uri() . '/customize_preview.js',
    array('customize-preview'),
    wp_get_theme()->get('Version')
  );
}

add_action('customize_preview_init', 'greiffenberg_customize_preview_script');
