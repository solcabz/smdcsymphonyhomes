<?php
// Create both tables
function create_social_links_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $table1 = $wpdb->prefix . 'social_links';
    $sql1 = "CREATE TABLE IF NOT EXISTS $table1 (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        link varchar(255) NOT NULL,
        name varchar(100) NOT NULL,
        img varchar(255) DEFAULT '' NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    $table2 = $wpdb->prefix . 'social_news';
    $sql2 = "CREATE TABLE IF NOT EXISTS $table2 (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        link varchar(255) NOT NULL,
        name varchar(100) NOT NULL,
        img varchar(255) DEFAULT '' NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql1);
    dbDelta($sql2);
}
add_action('after_setup_theme', 'create_social_links_tables');

// Add the admin menu
function custom_theme_add_social_menu() {
    add_menu_page(
        'Social Media Links', 
        'Social Links', 
        'manage_options', 
        'custom-social-links', 
        'custom_theme_social_links_page', 
        'dashicons-share',
        30
    );
}
add_action('admin_menu', 'custom_theme_add_social_menu');

// Main Page with Tabs
function custom_theme_social_links_page() {
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'icons';
    ?>
    <div class="wrap">
        <h1>Social Media Links</h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=custom-social-links&tab=icons" class="nav-tab <?php echo $active_tab == 'icons' ? 'nav-tab-active' : ''; ?>">Social Icons</a>
            <a href="?page=custom-social-links&tab=news" class="nav-tab <?php echo $active_tab == 'news' ? 'nav-tab-active' : ''; ?>">Social Icon News</a>
        </h2>
        <div>
            <?php 
            if ($active_tab == 'icons') {
                custom_theme_social_tab('social_links');
            } elseif ($active_tab == 'news') {
                custom_theme_social_tab('social_news');
            }
            ?>
        </div>
    </div>
    <?php
}

// Reusable tab renderer (works for both tables)
function custom_theme_social_tab($table_key) {
    global $wpdb;
    $table_name = $wpdb->prefix . $table_key;

    // Handle insert
    if (isset($_POST['add_'.$table_key]) && check_admin_referer('add_'.$table_key.'_action', 'add_'.$table_key.'_nonce')) {
        $link = esc_url_raw($_POST['social_link']);
        $name = sanitize_text_field($_POST['social_name']);
        $img = handle_social_icon_upload_v2($_FILES['social_image']);
        if ($link && $name) {
            $wpdb->insert($table_name, ['link' => $link, 'name' => $name, 'img' => $img]);
        }
    }

    // Handle delete
    if (isset($_GET['delete_'.$table_key]) && isset($_GET['social_id']) && check_admin_referer('delete_'.$table_key.'_action_' . intval($_GET['social_id']))) {
        $wpdb->delete($table_name, ['id' => intval($_GET['social_id'])]);
    }

    $items = $wpdb->get_results("SELECT * FROM $table_name");
    ?>
    <form method="post" enctype="multipart/form-data" style="display:flex;gap:10px;align-items:center;">
        <?php wp_nonce_field('add_'.$table_key.'_action', 'add_'.$table_key.'_nonce'); ?>
        <input type="url" name="social_link" placeholder="Input Social link" required>
        <input type="text" name="social_name" placeholder="Social name" required>
        <input type="file" name="social_image" accept="image/*">
        <input type="submit" name="add_<?php echo esc_attr($table_key); ?>" value="Add" class="button">
    </form>
    <br>
    <table class="widefat fixed" style="width:100%;max-width:900px;">
        <thead>
            <tr>
                <th>Social Links</th>
                <th>Social Name</th>
                <th>Social Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($items) : foreach ($items as $s): ?>
                <tr>
                    <td><a href="<?php echo esc_url($s->link); ?>" target="_blank"><?php echo esc_html($s->link); ?></a></td>
                    <td><?php echo esc_html($s->name); ?></td>
                    <td><?php if ($s->img): ?><img src="<?php echo esc_url($s->img); ?>" style="max-width:50px;"><?php endif; ?></td>
                    <td>
                        <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=custom-social-links&tab='.$table_key.'&delete_'.$table_key.'=1&social_id=' . $s->id), 'delete_'.$table_key.'_action_' . $s->id); ?>" class="button button-small" onclick="return confirm('Delete this item?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="4">No entries yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php
}

// Upload handler
function handle_social_icon_upload_v2($file) {
    if (!empty($file['name'])) {
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        $upload = wp_handle_upload($file, ['test_form' => false]);
        if (!isset($upload['error']) && isset($upload['url'])) {
            return esc_url_raw($upload['url']);
        }
    }
    return '';
}
