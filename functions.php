<?php

function greiffenberg_styles() {
  wp_enqueue_style(
    // Handle that identifies this resource.
    'greiffenberg-main-stylesheet',
    // Path to the stylesheet.
    /* get_template_directory_uri() . '/style.css', */
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
    'sanitize_callback' => function ($number) { return (float) $number; }
  ));

  $customizer->add_control('body-line-height', array(
    'label' => 'Line height of body text',
    'type' => 'number',
    'section' => 'typography'
  ));

  $customizer->add_section('typography', array(
    'title' => 'Typography',
    'description' => 'Ajust how text is displayed – line height, font family, etc.',
    'priority' => 50
  ));
}

add_action('customize_register', 'greiffenberg_customize');

function greiffenberg_line_height_css() {
  echo '<style>body {--global--line-height-body:' . get_theme_mod('body-line-height', '1.5') . 'rem;}</style>'; 
}

add_action('wp_head', 'greiffenberg_line_height_css');
