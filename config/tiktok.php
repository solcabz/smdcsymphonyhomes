<?php
/**
 * Plugin Name: TikTok Feed Scraper v2
 * Description: Scrapes TikTok profile videos without login and displays as scrollable cards.
 * Version: 1.1
 * Author: Your Name
 */

if (!defined('ABSPATH')) exit; // no direct access

/**
 * Scrape TikTok videos from public profile
 */
function scrape_tiktok_videos($username, $limit = 10) {
    $url = "https://www.tiktok.com/@{$username}";

    $response = wp_remote_get($url, [
        'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Referer' => 'https://www.google.com/',
            'Connection' => 'keep-alive'
        ],
        'timeout' => 15
    ]);

    if (is_wp_error($response)) {
        return [];
    }

    $html = wp_remote_retrieve_body($response);
    $videos = [];

    // --- Try parsing SIGI_STATE first ---
    if (preg_match('/<script id="SIGI_STATE"[^>]*>(.*?)<\/script>/s', $html, $matches)) {
        $json = $matches[1];
        $data = json_decode($json, true);

        if (isset($data['ItemList']['user-post']['list'])) {
            foreach ($data['ItemList']['user-post']['list'] as $videoId) {
                if (!isset($data['ItemModule'][$videoId])) continue;
                $video = $data['ItemModule'][$videoId];
                $videos[] = [
                    'id' => $video['id'],
                    'desc' => $video['desc'],
                    'cover' => $video['video']['cover'],
                    'url' => 'https://www.tiktok.com/@' . $username . '/video/' . $video['id'],
                ];
                if (count($videos) >= $limit) break;
            }
        }
    }

    // --- If SIGI_STATE failed, try NEXT_DATA ---
    if (empty($videos) && preg_match('/<script id="__NEXT_DATA__"[^>]*>(.*?)<\/script>/s', $html, $matches2)) {
        $json2 = $matches2[1];
        $data2 = json_decode($json2, true);

        if (isset($data2['props']['pageProps']['items'])) {
            foreach ($data2['props']['pageProps']['items'] as $item) {
                $videos[] = [
                    'id' => $item['id'],
                    'desc' => $item['desc'],
                    'cover' => $item['video']['cover'],
                    'url' => 'https://www.tiktok.com/@' . $username . '/video/' . $item['id'],
                ];
                if (count($videos) >= $limit) break;
            }
        }
    }

    return $videos;
}

/**
 * Shortcode handler [tiktok_feed username="..." limit="8"]
 */
function tiktok_feed_shortcode($atts) {
    $atts = shortcode_atts([
        'username' => '',
        'limit' => 8
    ], $atts);

    if (empty($atts['username'])) {
        return '<p>No TikTok username provided.</p>';
    }

    $videos = scrape_tiktok_videos($atts['username'], intval($atts['limit']));
    if (!$videos) {
        return '<p>Could not load TikTok videos (profile may require JS or login).</p>';
    }

    ob_start(); ?>
    <div class="tiktok-feed">
        <?php foreach ($videos as $v): ?>
            <div class="tiktok-card">
                <a href="<?php echo esc_url($v['url']); ?>" target="_blank">
                    <img src="<?php echo esc_url($v['cover']); ?>" alt="<?php echo esc_attr($v['desc']); ?>">
                </a>
                <p><?php echo esc_html($v['desc']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('tiktok_feed', 'tiktok_feed_shortcode');

/**
 * Enqueue styles
 */
function tiktok_feed_styles() {
    wp_register_style('tiktok-feed-style', false);
    wp_enqueue_style('tiktok-feed-style');
    $custom_css = "
        .tiktok-feed {
            display: flex;
            overflow-x: auto;
            gap: 16px;
            padding: 10px;
            scroll-snap-type: x mandatory;
        }
        .tiktok-card {
            min-width: 200px;
            max-width: 220px;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            flex-shrink: 0;
            scroll-snap-align: start;
            font-family: 'Montserrat', sans-serif;
        }
        .tiktok-card img {
            width: 100%;
            display: block;
            border-radius: 12px 12px 0 0;
        }
        .tiktok-card p {
            font-size: 14px;
            padding: 8px;
            margin: 0;
            color: #333;
        }
    ";
    wp_add_inline_style('tiktok-feed-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'tiktok_feed_styles');
