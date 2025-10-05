<?php
// Add Footer Settings page
add_action('admin_menu', 'custom_footer_settings_page');
function custom_footer_settings_page() {
    add_menu_page(
        'Footer Settings',
        'Footer Settings',
        'manage_options',
        'footer-settings',
        'render_footer_settings_page',
        'dashicons-admin-generic',
        90
    );
}

// Register settings
add_action('admin_init', 'register_footer_settings');
function register_footer_settings() {
    register_setting('footer_settings_group', 'footer_phone_1');
    register_setting('footer_settings_group', 'footer_phone_2');
    register_setting('footer_settings_group', 'footer_email');
    register_setting('footer_settings_group', 'footer_address');
    register_setting('footer_settings_group', 'footer_newsletter');
    register_setting('footer_settings_group', 'footer_copyright');
}

// Render the settings form
function render_footer_settings_page() {
    ?>
    <div class="wrap">
        <h1>Footer Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('footer_settings_group'); ?>
            <?php do_settings_sections('footer_settings_group'); ?>

            <table class="form-table">
                <tr><th>Phone 1</th><td><input type="text" name="footer_phone_1" value="<?php echo esc_attr(get_option('footer_phone_1')); ?>" class="regular-text"></td></tr>
                <tr><th>Phone 2</th><td><input type="text" name="footer_phone_2" value="<?php echo esc_attr(get_option('footer_phone_2')); ?>" class="regular-text"></td></tr>
                <tr><th>Email</th><td><input type="email" name="footer_email" value="<?php echo esc_attr(get_option('footer_email')); ?>" class="regular-text"></td></tr>
                <tr><th>Address</th><td><textarea name="footer_address" rows="3" class="large-text"><?php echo esc_textarea(get_option('footer_address')); ?></textarea></td></tr>
                <tr><th>Newsletter Text</th><td><textarea name="footer_newsletter" rows="2" class="large-text"><?php echo esc_textarea(get_option('footer_newsletter')); ?></textarea></td></tr>
                <tr><th>Copyright</th><td><input type="text" name="footer_copyright" value="<?php echo esc_attr(get_option('footer_copyright')); ?>" class="regular-text"></td></tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}