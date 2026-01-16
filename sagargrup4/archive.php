<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SagarGrup
 */

get_header();
?>

	<div id="primary" class="content-area container mx-auto mt-8 mb-8 px-4">
		<main id="main" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header mb-8">
				<?php
					the_archive_title( '<h1 class="page-title text-4xl font-bold text-gray-800">', '</h1>' );
					the_archive_description( '<div class="archive-description text-gray-600 mt-2">', '</div>' );
				?>
			</header><!-- .page-header -->

			<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_type() );

			endwhile;
			?>
			</div>
			<?php

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
