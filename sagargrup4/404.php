<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <section class="error-404 not-found" style="text-align: center;">
            <img src="<?php echo get_template_directory_uri(); ?>/404.png" alt="404 Not Found" style="max-width: 100%; height: auto; margin-bottom: 20px;">
            <div class="page-content">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button" style="padding: 10px 20px; background-color: #0073aa; color: #fff; text-decoration: none; border-radius: 5px;"><?php esc_html_e( 'Go to Homepage', 'sagargrup' ); ?></a>
            </div><!-- .page-content -->
        </section><!-- .error-404 -->
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
