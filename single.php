<?php
/**
 * Single post / fallback.
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$is_post = is_singular('post');
?>
<main id="main" class="site-main site-main--page<?php echo $is_post ? ' site-main--single-post' : ''; ?>">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<article <?php post_class('idc-single idc-container'); ?>>
				<header class="idc-single__header">
					<?php
					get_template_part('template-parts/components/breadcrumb', null, [
						'current' => get_the_title(),
						'items'   => [
							[
								'label' => __('Blog', 'instituto-dr-chao'),
								'url'   => get_permalink((int) get_option('page_for_posts')) ?: home_url('/blog/'),
							],
						],
					]);
					?>
					<?php
					$post_cats = get_the_category();
					if ($is_post && $post_cats) :
						?>
						<ul class="idc-single__cats">
							<?php foreach ($post_cats as $post_cat) : ?>
								<li>
									<a href="<?php echo esc_url(get_category_link((int) $post_cat->term_id)); ?>">
										<?php echo esc_html($post_cat->name); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<time class="idc-single__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d M Y')); ?></time>
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

	<?php if ($is_post) : ?>
		<?php
		get_template_part('template-parts/components/strip-cta', null, [
			'title'  => __('Quer saber se este tema se aplica a você?', 'instituto-dr-chao'),
			'lead'   => __('Fale com nossa equipe e agende uma avaliação pelo WhatsApp.', 'instituto-dr-chao'),
			'label'  => __('Agendar Consulta', 'instituto-dr-chao'),
			'origem' => 'blog-single',
			'align'  => 'center',
		]);
		?>
	<?php endif; ?>
</main>
<?php
get_footer();
