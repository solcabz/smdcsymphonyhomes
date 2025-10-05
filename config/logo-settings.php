<?php

function symphonyhomes_customize_register($wp_customize) {
    // Header Image Setting
    $wp_customize->add_setting('header_image', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'header_image',
        [
            'label' => __('Header Image', 'symphonyhomes'),
            'section' => 'title_tagline', // Or create a new section
            'settings' => 'header_image',
        ]
    ));
    
    // Sidebar Image Setting
    $wp_customize->add_setting('sidebar_image', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'sidebar_image',
        [
            'label' => __('Sidebar Image', 'symphonyhomes'),
            'section' => 'title_tagline', // Or create a new section
            'settings' => 'sidebar_image',
        ]
    ));

    // Footer Image Setting
    $wp_customize->add_setting('footer_image', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'footer_image',
        [
            'label' => __('Footer Image', 'symphonyhomes'),
            'section' => 'title_tagline', // Or create a new section
            'settings' => 'footer_image',
        ]
    ));
}
add_action('customize_register', 'symphonyhomes_customize_register');
