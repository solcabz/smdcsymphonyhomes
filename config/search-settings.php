<?php
class SmartSearch {
    public function __construct() {
        // Redirect logic
        add_action('template_redirect', [$this, 'property_search_redirect']);

        // Force submit on Enter
        add_action('wp_footer', [$this, 'auto_submit_search_form']);

        // AJAX live search
        add_action('wp_ajax_live_search_suggestions', [$this, 'live_search_suggestions']);
        add_action('wp_ajax_nopriv_live_search_suggestions', [$this, 'live_search_suggestions']);
    }

    public function property_search_redirect() {
        if ( is_search() && !is_admin() && !defined('REST_REQUEST') && !empty($_GET['s']) ) {
            $search_term = sanitize_text_field($_GET['s']);

            $title_filter = function ($where, $query) use ($search_term) {
                global $wpdb;
                if ($query->get('smart_title_search')) {
                    $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s", '%' . $wpdb->esc_like($search_term) . '%');
                }
                return $where;
            };

            // Check Property
            add_filter('posts_where', $title_filter, 10, 2);
            $property_query = new WP_Query([
                'post_type' => 'property',
                'posts_per_page' => 1,
                'post_status' => 'publish',
                'smart_title_search' => true,
            ]);
            remove_filter('posts_where', $title_filter, 10);

            if ($property_query->have_posts()) {
                wp_safe_redirect(get_permalink($property_query->posts[0]->ID));
                exit;
            }

            // Check taxonomies
            $this->redirect_if_term_exists($search_term, 'property_segment');
            $this->redirect_if_term_exists($search_term, 'property_region');

            // Check Pages
            add_filter('posts_where', $title_filter, 10, 2);
            $page_query = new WP_Query([
                'post_type' => 'page',
                'posts_per_page' => 1,
                'post_status' => 'publish',
                'smart_title_search' => true,
            ]);
            remove_filter('posts_where', $title_filter, 10);

            if ($page_query->have_posts()) {
                wp_safe_redirect(get_permalink($page_query->posts[0]->ID));
                exit;
            }

            // Nothing matched → 404
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            nocache_headers();
            include get_404_template();
            exit;
        }
    }

    private function redirect_if_term_exists($term, $taxonomy) {
        $found = get_term_by('name', $term, $taxonomy);
        if ($found && !is_wp_error($found)) {
            $link = get_term_link($found);
            if (!is_wp_error($link)) {
                wp_safe_redirect($link);
                exit;
            }
        }
    }

    public function auto_submit_search_form() {
        if (!is_admin()) : ?>
            <script type="text/javascript">
                document.querySelector('.search-field')?.addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        this.form.submit();
                    }
                });
            </script>
        <?php endif;
    }

    public function live_search_suggestions() {
        $term = isset($_GET['term']) ? sanitize_text_field($_GET['term']) : '';
        if (empty($term)) wp_send_json([]);

        $results = [];

        // Posts/pages/properties
        $query = new WP_Query([
            'post_type' => ['post', 'page', 'property', 'good_life'],
            's' => $term,
            'posts_per_page' => 5,
            'post_status' => 'publish',
        ]);

        foreach ($query->posts as $post) {
            $results[] = [
                'title' => get_the_title($post),
                'url'   => get_permalink($post),
                'thumbnail' => get_the_post_thumbnail_url($post, 'thumbnail') ?: '',
            ];
        }

        // Property location (meta)
        $location_query = new WP_Query([
            'post_type' => 'property',
            'posts_per_page' => 5,
            'post_status' => 'publish',
            'meta_query' => [[
                'key'     => '_property_location',
                'value'   => $term,
                'compare' => 'LIKE',
            ]],
        ]);

        foreach ($location_query->posts as $post) {
            $results[] = [
                'title' => get_the_title($post),
                'url'   => get_permalink($post),
                'thumbnail' => get_the_post_thumbnail_url($post, 'thumbnail') ?: '',
            ];
        }

        // Property region (taxonomy)
        $region_terms = get_terms([
            'taxonomy'   => 'property_region',
            'name__like' => $term,
            'number'     => 3,
            'hide_empty' => true,
        ]);

        if (!empty($region_terms) && !is_wp_error($region_terms)) {
            foreach ($region_terms as $region) {
                $region_query = new WP_Query([
                    'post_type'      => 'property',
                    'posts_per_page' => 5,
                    'post_status'    => 'publish',
                    'tax_query'      => [[
                        'taxonomy' => 'property_region',
                        'field'    => 'term_id',
                        'terms'    => $region->term_id,
                    ]],
                ]);

                foreach ($region_query->posts as $property) {
                    $results[] = [
                        'title'     => get_the_title($property),
                        'url'       => get_permalink($property),
                        'thumbnail' => get_the_post_thumbnail_url($property, 'thumbnail') ?: '',
                    ];
                }
            }
        }

        wp_send_json($results);
    }
}

// Instantiate
new SmartSearch();
