<?php get_header(); ?>

<div id="primary" class="content-area container mx-auto mt-8 mb-8 px-4">
    <div class="flex flex-wrap -mx-4">

        <!-- Main Content -->
        <main id="main" class="site-main w-full md:w-2/3 px-4">

            <?php
            while ( have_posts() ) : the_post();

                // Get custom fields
                $playstore_link = get_post_meta(get_the_ID(), '_app_playstore_link', true);
                $screenshots = get_post_meta(get_the_ID(), '_app_screenshots', true);
                $app_logo = get_post_meta(get_the_ID(), '_app_logo', true);
                $app_banner = get_post_meta(get_the_ID(), '_app_banner', true);
                $app_category = get_the_term_list(get_the_ID(), 'app-category', '', ', ');

                ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class('app-single bg-white rounded-lg shadow-lg overflow-hidden'); ?>>
                    <?php if ($app_banner) : ?>
                        <div class="app-banner h-64 bg-cover bg-center" style="background-image: url('<?php echo esc_url($app_banner); ?>');">
                        </div>
                    <?php endif; ?>

                    <header class="app-header p-6 flex items-center">
                        <?php if ($app_logo) : ?>
                            <div class="app-logo mr-6 flex-shrink-0">
                                <img src="<?php echo esc_url($app_logo); ?>" alt="<?php the_title_attribute(); ?> Logo" class="w-32 h-32 rounded-2xl shadow-lg object-cover">
                            </div>
                        <?php endif; ?>

                        <div class="app-title-meta">
                            <?php the_title('<h1 class="entry-title text-3xl font-bold text-gray-800">', '</h1>'); ?>
                            <?php if ($app_category) : ?>
                                <div class="app-category text-gray-600 mt-1">
                                    <strong>Category:</strong> <?php echo $app_category; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </header>

                    <div class="entry-content p-6 border-t border-gray-200">
                        <?php the_content(); ?>
                    </div>

                    <?php if ($playstore_link) : ?>
                        <div class="app-download p-6 text-center">
                            <a href="<?php echo esc_url($playstore_link); ?>" target="_blank" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1">
                                <i class="fab fa-google-play mr-2"></i> View on Google Play
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($screenshots) && is_array($screenshots)) : ?>
                        <div class="screenshots-section p-6 border-t border-gray-200">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">Screenshots</h2>
                            <div class="screenshots-gallery grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                <?php foreach ($screenshots as $screenshot) : ?>
                                    <a href="<?php echo esc_url($screenshot); ?>" data-lightbox="screenshots" class="block overflow-hidden rounded-lg border-2 border-transparent hover:border-blue-500 transition-all duration-300 transform hover:scale-105">
                                        <img src="<?php echo esc_url($screenshot); ?>" alt="Screenshot" class="w-full h-full object-cover">
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </article><!-- #post-<?php the_ID(); ?> -->

            <?php endwhile; // End of the loop. ?>

        </main><!-- #main -->

        <!-- Sidebar -->
        <aside id="secondary" class="widget-area w-full md:w-1/3 px-4 mt-8 md:mt-0">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="widget-title text-xl font-bold text-gray-800 mb-4 border-b pb-2">Latest Apps</h3>
                <ul>
                    <?php
                    $latest_apps_args = array(
                        'post_type' => 'app',
                        'posts_per_page' => 5,
                        'post__not_in' => array(get_the_ID()), // Exclude current post
                    );
                    $latest_apps = new WP_Query($latest_apps_args);
                    if ($latest_apps->have_posts()) :
                        while ($latest_apps->have_posts()) : $latest_apps->the_post();
                            $latest_app_logo = get_post_meta(get_the_ID(), '_app_logo', true);
                    ?>
                            <li class="mb-4 flex items-center">
                                <?php if ($latest_app_logo) : ?>
                                    <img src="<?php echo esc_url($latest_app_logo); ?>" alt="<?php the_title_attribute(); ?> Logo" class="w-12 h-12 rounded-full mr-4 object-cover">
                                <?php endif; ?>
                                <div>
                                    <a href="<?php the_permalink(); ?>" class="font-semibold text-gray-700 hover:text-blue-500"><?php the_title(); ?></a>
                                </div>
                            </li>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<p>No other apps found.</p>';
                    endif;
                    ?>
                </ul>
            </div>
        </aside>

    </div><!-- .flex -->
</div><!-- #primary -->

<?php get_footer(); ?>
