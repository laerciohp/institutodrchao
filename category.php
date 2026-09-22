<?php
/**
 * Arquivo de categoria do blog.
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$term = get_queried_object();
$cat_name = ($term instanceof WP_Term) ? $term->name : __('Categoria', 'instituto-dr-chao');
$cat_desc = ($term instanceof WP_Term) ? trim((string) $term->description) : '';
$categories = idc_blog_filter_categories();
$active_id  = ($term instanceof WP_Term) ? (string) $term->term_id : 'all';
?>
<main id="main" class="site-main site-main--page site-main--blog">
	<section class="idc-page-hero idc-page-hero--blog">
		<span class="idc-page-hero__decor idc-page-hero__decor--blog" aria-hidden="true"></span>
		<div class="idc-container">
			<div class="idc-page-hero__inner">
				<?php
				get_template_part('template-parts/components/breadcrumb', null, [
					'current' => $cat_name,
					'items'   => [
						[
							'label' => __('Blog', 'instituto-dr-chao'),
							'url'   => get_permalink((int) get_option('page_for_posts')) ?: home_url('/blog/'),
						],
					],
				]);
				?>
				<p class="idc-eyebrow idc-eyebrow--accent idc-page-hero__eyebrow"><?php esc_html_e('BLOG', 'instituto-dr-chao'); ?></p>
				<h1 class="idc-page-hero__title">
					<span class="idc-page-hero__title-accent"><?php echo esc_html($cat_name); ?></span>
				</h1>
				<?php if ($cat_desc !== '') : ?>
					<p class="idc-page-hero__lead"><?php echo esc_html($cat_desc); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="idc-blog-archive idc-container">
		<?php if (!empty($categories)) : ?>
			<div class="idc-blog-filters" role="navigation" aria-label="<?php esc_attr_e('Categorias do blog', 'instituto-dr-chao'); ?>">
				<a
					class="idc-blog-filters__btn<?php echo $active_id === 'all' ? ' is-active' : ''; ?>"
					href="<?php echo esc_url(get_permalink((int) get_option('page_for_posts')) ?: home_url('/blog/')); ?>"
				>
					<?php esc_html_e('Todos', 'instituto-dr-chao'); ?>
				</a>
				<?php foreach ($categories as $cat) : ?>
					<a
						class="idc-blog-filters__btn<?php echo (string) $cat->term_id === $active_id ? ' is-active' : ''; ?>"
						href="<?php echo esc_url(get_category_link((int) $cat->term_id)); ?>"
					>
						<?php echo esc_html($cat->name); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if (have_posts()) : ?>
			<div class="idc-blog-archive__grid">
				<?php
				while (have_posts()) :
					the_post();
					get_template_part('template-parts/blog/card', null, [
						'variant'   => 'archive',
						'show_cat'  => true,
						'show_meta' => true,
						'heading'   => 'h2',
					]);
				endwhile;
				?>
			</div>
			<div class="idc-blog-archive__nav">
				<?php the_posts_pagination(); ?>
			</div>
		<?php else : ?>
			<p class="idc-blog-archive__empty"><?php esc_html_e('Nenhum post nesta categoria.', 'instituto-dr-chao'); ?></p>
		<?php endif; ?>
	</section>

	<?php
	get_template_part('template-parts/components/strip-cta', null, [
		'origem' => 'blog-categoria',
		'decor'  => true,
		'align'  => 'center',
	]);
	?>
</main>
<?php
get_footer();
