<?php if (have_rows('home_module')): ?>
    <?php while (have_rows('home_module')): the_row(); ?>

        <?php if (get_row_layout() == 'hero_banner'): ?>
            <section class="hero-banner">
                <div class="hero-wrapeper">
                    <h1><?php the_sub_field('hero_title'); ?></h1>
                    <a class="btn button-text" href="<?php the_sub_field('hero_link_url'); ?>">
                        <?php the_sub_field('hero_link_label'); ?>
                    </a>
                </div>
                <?php
                    $image = get_sub_field('hero_banner');
                    if ($image):
                ?>
                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                <?php endif; ?>
            </section>
        <?php endif; ?>

    <?php endwhile; ?>
<?php endif; ?>