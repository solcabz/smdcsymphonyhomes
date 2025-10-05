<?php

add_action('wp_enqueue_scripts', function () {
    // Google Fonts: Montserrat + Figtree
    wp_enqueue_style(
        'theme-google-fonts',
        'https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap',
        [],
        null
    );
});

//Add # Munus Selection
function menu_option() {
    register_nav_menu('primary', 'Primary Menu');
    register_nav_menu('secondary', 'Secondary Menu');
    register_nav_menu('tertiary', 'Tertiary Menu');
}
add_action('after_setup_theme', 'menu_option');

// style.css
function my_theme_enqueue_styles() {
    wp_enqueue_style('theme-style', get_template_directory_uri() . "/style.css", array(), '2.0', 'all');
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

// all css on the assets
function enqueue_all_styles() {
    $theme_dir = get_template_directory_uri();
    $style_dir = get_template_directory() . '/assets/css/';

    // Check if directory exists
    if (is_dir($style_dir)) {
        foreach (glob($style_dir . '*.css') as $file) {
            $filename = basename($file);
            wp_enqueue_style("custom-$filename", $theme_dir . "/assets/css/$filename", array(), filemtime($file));
        }
    }
}
add_action('wp_enqueue_scripts', 'enqueue_all_styles');

// Enqueue all JS files from assets/js/
function enqueue_all_scripts() {
    $theme_dir = get_template_directory_uri();
    $script_dir = get_template_directory() . '/assets/js/';

    if (is_dir($script_dir)) {
        foreach (glob($script_dir . '*.js') as $file) {
            $filename = basename($file);
            wp_enqueue_script("custom-$filename", $theme_dir . "/assets/js/$filename", array('jquery'), filemtime($file), true);
        }
    }
}
add_action('wp_enqueue_scripts', 'enqueue_all_scripts');


require_once get_template_directory( ). '/config/admin-settings.php';
require_once get_template_directory( ). '/config/search-settings.php';
require_once get_template_directory( ). '/config/tiktok.php';