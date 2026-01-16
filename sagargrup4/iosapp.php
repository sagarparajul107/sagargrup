<?php
/*
Template Name: iOS Apps
*/
get_header();
?>

<div id="primary" class="content-area container mx-auto mt-8 mb-8 px-4">
    <main id="main" class="site-main">

        <header class="page-header mb-8">
            <h1 class="page-title text-4xl font-bold text-gray-800">iOS Apps</h1>
        </header><!-- .page-header -->

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php
        $ios_apps_args = array(
            'post_type' => 'app',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'app-category',
                    'field'    => 'slug',
                    'terms'    => 'ios',
                ),
            ),
        );
        $ios_apps = new WP_Query($ios_apps_args);
        if ($ios_apps->have_posts()) :
            while ($ios_apps->have_posts()) : $ios_apps->the_post();

                get_template_part( 'template-parts/content', 'app' );

            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>No iOS apps found.</p>';
        endif;
        ?>
        </div>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
