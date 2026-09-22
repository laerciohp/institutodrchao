<?php
/**
 * Single post / fallback.
 *
 * @package Instituto_Dr_Chao
 */

get_header();
?>
<main id="main" class="site-main site-main--page">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<article <?php post_class('idc-single idc-container'); ?>>
				<header class="idc-single__header">
					<?php
					get_template_part('template-parts/components/breadcrumb', null, [
						'current' => get_the_title(),
					]);
					?>
					<time class="idc-single__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
					<h1 class="idc-single__title"><?php the_title(); ?></h1>
				</header>
				<?php if (has_post_thumbnail()) : ?>
					<figure class="idc-single__thumb">
						<?php the_post_thumbnail('large'); ?>
					</figure>
				<?php endif; ?>
				<div class="idc-single__content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	<?php endif; ?>
</main>
<?php
get_footer();
