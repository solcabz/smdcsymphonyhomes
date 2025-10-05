<?php

class My_Custom_Functionality {
    public function __construct() {
        add_action('init', [$this, 'add_plugin_manager_role']);
        add_filter('rest_authentication_errors', [$this, 'restrict_rest_api']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_live_search_assets']);
    }

    public function add_plugin_manager_role() {
        if (!get_role('plugin_manager')) {
            add_role('plugin_manager', 'Plugin Manager', [
                'read'            => true,
                'update_plugins'  => true,
                'activate_plugins'=> true,
            ]);
        }
    }

    public function restrict_rest_api($result) {
        if (!empty($result)) return $result;
        if (is_admin()) return $result;

        if (!is_user_logged_in()) {
            return new WP_Error(
                'rest_forbidden',
                __('REST API restricted to authenticated users.'),
                ['status' => 401]
            );
        }

        return $result;
    }

    public function enqueue_live_search_assets() {
        wp_enqueue_script(
            'live-search',
            get_template_directory_uri() . '/assets/js/live-search.js',
            [],
            null,
            true
        );

        wp_localize_script('live-search', 'liveSearch', [
            'ajax_url' => admin_url('admin-ajax.php'),
        ]);
    }
}

new My_Custom_Functionality();
