<?php if (have_rows('home_module')): ?>
    <?php while (have_rows('home_module')): the_row(); ?>

        <?php if (get_row_layout() == 'featured_property'): ?>
            <section class="featured-section">
                <div class="featured-wrapper">
                    <div class="featured-content">
                        <h1><?php the_sub_field('featured_property_title'); ?></h1>
                        <p><?php the_sub_field('featured_property_blurb'); ?></p>
                    </div>
                    <div>

                    </div>
                </div>
            </section>
        <?php endif; ?>

    <?php endwhile; ?>
<?php endif; ?>

<script>