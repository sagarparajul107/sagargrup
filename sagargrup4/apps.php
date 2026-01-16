<?php
/*
Template Name: All Apps
*/
get_header();
?>

<div id="primary" class="content-area container mx-auto mt-8 mb-8 px-4">
    <main id="main" class="site-main">

        <header class="page-header mb-8">
            <h1 class="page-title text-4xl font-bold text-gray-800">All Apps</h1>
        </header><!-- .page-header -->

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php
        $all_apps_args = array(
            'post_type' => 'app',
            'posts_per_page' => -1,
        );
        $all_apps = new WP_Query($all_apps_args);
        if ($all_apps->have_posts()) :
            while ($all_apps->have_posts()) : $all_apps->the_post();

                get_template_part( 'template-parts/content', 'app' );

            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>No apps found.</p>';
        endif;
        ?>
        </div>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
