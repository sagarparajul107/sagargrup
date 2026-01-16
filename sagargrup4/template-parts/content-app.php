<?php
/**
 * Template part for displaying app content in index.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SagarGrup
 */

$app_logo = get_post_meta(get_the_ID(), '_app_logo', true);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-lg overflow-hidden transform hover:-translate-y-2 transition-transform duration-300'); ?>>
	<a href="<?php the_permalink(); ?>" class="block">
		<?php if ($app_logo) : ?>
			<img src="<?php echo esc_url($app_logo); ?>" alt="<?php the_title_attribute(); ?> Logo" class="w-full h-48 object-cover">
		<?php else : ?>
			<?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover']); ?>
		<?php endif; ?>
		<div class="p-4">
			<?php the_title( '<h2 class="entry-title text-lg font-bold text-gray-800">', '</h2>' ); ?>
		</div>
	</a>
</article><!-- #post-<?php the_ID(); ?> -->
