<?php if (have_rows('home_module')): ?>
    <?php while (have_rows('home_module')): the_row(); ?>

        <?php if (get_row_layout() == 'avp_section'): ?>
            <section class="avp-section">
                <div class="avp-wrapper">
                    <div class="avp-content">
                        <h1><?php the_sub_field('avp_title'); ?></h1>
                        <p><?php the_sub_field('avp_blurb'); ?></p>
                    </div>
                    <?php
                        $video = get_sub_field('avp_video');
                        if ($video):
                    ?>
                        <div class="avp-video-container">
                            <video class="avp-video" src="<?php echo esc_url($video['url']); ?>" loop playsinline></video>
                            <button class="avp-playpause-btn">
                                <span class="play">▶</span>
                                <span class="pause">⏸</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>

    <?php endwhile; ?>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const containers = document.querySelectorAll('.avp-video-container');

    containers.forEach(container => {
        const video = container.querySelector('.avp-video');
        const button = container.querySelector('.avp-playpause-btn');
        const playIcon = button.querySelector('.play');
        const pauseIcon = button.querySelector('.pause');

        button.addEventListener('click', () => {
            if (video.paused) {
                video.muted = false; // 👈 Unmute video when user clicks play
                video.play();
                container.classList.add('playing');
                playIcon.style.display = 'none';
                pauseIcon.style.display = 'inline';
            } else {
                video.pause();
                container.classList.remove('playing');
                playIcon.style.display = 'inline';
                pauseIcon.style.display = 'none';
            }
        });

        video.addEventListener('play', () => {
            container.classList.add('playing');
            playIcon.style.display = 'none';
            pauseIcon.style.display = 'inline';
        });

        video.addEventListener('pause', () => {
            container.classList.remove('playing');
            playIcon.style.display = 'inline';
            pauseIcon.style.display = 'none';
        });
    });
});
</script>
