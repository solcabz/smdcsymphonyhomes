<footer>
    <div class="footer-container">

        <div class="footer-top">
            <div class="footer-logo">
                <?php
                    $footer_img = get_theme_mod('footer_image');
                    if ($footer_img) : ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <img src="<?php echo esc_url($footer_img); ?>" alt="symphony homes logo" />
                        </a>
                <?php endif; ?>
            </div>
            <div class="footer-details">
                <div class="contact-footer">
                    <p><?php echo esc_html(get_option('footer_phone_1')); ?></p>
                    <p><?php echo esc_html(get_option('footer_phone_2')); ?></p>
                    <p><a href="mailto:<?php echo esc_attr(get_option('footer_email')); ?>">
                        <?php echo esc_html(get_option('footer_email')); ?>
                    </a></p>
                </div>
                <p class="footer-address"><?php echo esc_html(get_option('footer_address')); ?></p>
            </div>
        </div>

        <div class="footer-seperator"></div>

        <div class="footer-down">
            <div class="down-wrapper">
                <div class="down-left">
                    <div class="news-form-wrapper">
                        <p><?php echo esc_html(get_option('footer_newsletter')); ?></p>
                        <form method="POST" action="">
                            <?php wp_nonce_field('subscribe_newsletter', 'newsletter_nonce'); ?>
                            <input type="email" placeholder="Enter your email" required>
                            <button type="submit">Subscribe</button>
                        </form>
                        <!-- Newsletter Modal -->
                        <div id="newsletter-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
                            background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:9999;">
                            <div class="modal-wrap" style="background:#fff; padding:20px; border-radius:8px; max-width:400px; text-align:center;">
                                <p id="newsletter-message"></p>
                                <button onclick="document.getElementById('newsletter-modal').style.display='none'">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="down-right"></div>
            </div>
            <div class="copyright-section">
                <div class="sidebar-icons">
                    <?php
                    global $wpdb;
                    $table_social = $wpdb->prefix . 'social_links';
                    $social_icons = $wpdb->get_results("SELECT * FROM $table_social");

                    // Example: Loop and output
                    if ($social_icons) {
                        foreach ($social_icons as $icon) {
                            echo '<a href="'.esc_url($icon->link).'" target="_blank">';
                            if (!empty($icon->img)) {
                                echo '<img src="'.esc_url($icon->img).'" alt="'.esc_attr($icon->name).'" style="width:30px;height:30px;">';
                            } else {
                                echo esc_html($icon->name);
                            }
                            echo '</a> ';
                        }
                    }
                    ?>
                </div>
                <div class="copyright">
                    <p>
                        Copyright 
                        <?php echo date('Y'); ?> 
                        <a href="https://smdc.com" target="_blank" rel="noopener noreferrer">SMDC</a>, 
                        <?php echo esc_html(get_option('footer_copyright')); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
<script>
    function showNewsletterModal(message) {
        document.getElementById('newsletter-message').innerText = message;
        document.getElementById('newsletter-modal').style.display = 'flex';
    }
</script>


</body>
<?php wp_footer(); ?>
</html>