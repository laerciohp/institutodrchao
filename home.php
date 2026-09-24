<?php
/**
 * Listagem do blog.
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$posts_page_id = (int) get_option('page_for_posts');

$eyebrow = '';
// Figma Blog 133:2534 — accent só em "seu cuidado." (Montserrat + terracota).
$before  = __('Conhecimento para o ', 'instituto-dr-chao');
$accent  = __('seu cuidado.', 'instituto-dr-chao');
$after   = '';
$lead    = __('Artigos, dicas e novidades sobre ortopedia, fisioterapia, medicina integrativa e bem-estar. Escritos por nossa equipe de especialistas para ajudar você a viver com mais movimento e menos dor.', 'instituto-dr-chao');

if ($posts_page_id > 0 && function_exists('get_field')) {
	$acf_eyebrow = trim((string) (get_field('idc_page_eyebrow', $posts_page_id) ?: ''));
	$acf_before  = trim((string) (get_field('idc_page_title_before', $posts_page_id) ?: ''));
	$acf_accent  = trim((string) (get_field('idc_page_title_accent', $posts_page_id) ?: ''));
	$acf_after   = trim((string) (get_field('idc_page_title_after', $posts_page_id) ?: ''));
	$acf_lead    = trim((string) (get_field('idc_page_lead', $posts_page_id) ?: ''));

	if ($acf_eyebrow !== '') {
		$eyebrow = $acf_eyebrow;
	}
	if ($acf_before !== '') {
		$before = $acf_before;
	}
	if ($acf_accent !== '') {
		$accent = $acf_accent;
	}
	if ($acf_after !== '') {
		$after = $acf_after;
	}
	if ($acf_lead !== '') {
		$lead = $acf_lead;
	}
}

// Garante título legível mesmo com ACF vazio / só espaços / seed antigo.
$legacy_accent = __('Conhecimento', 'instituto-dr-chao');
$legacy_after  = __(' para o seu cuidado.', 'instituto-dr-chao');
if ($accent === $legacy_accent && ($after === $legacy_after || trim($after) === 'para o seu cuidado.')) {
	$before = __('Conhecimento para o ', 'instituto-dr-chao');
	$accent = __('seu cuidado.', 'instituto-dr-chao');
	$after  = '';
}
if ($accent === '' && $before === '' && $after === '') {
	$before = __('Conhecimento para o ', 'instituto-dr-chao');
	$accent = __('seu cuidado.', 'instituto-dr-chao');
}
if ($lead === '') {
	$lead = __('Artigos, dicas e novidades sobre ortopedia, fisioterapia, medicina integrativa e bem-estar. Escritos por nossa equipe de especialistas para ajudar você a viver com mais movimento e menos dor.', 'instituto-dr-chao');
}

$categories = idc_blog_filter_categories();
?>
<main id="main" class="site-main site-main--page site-main--blog">
	<section class="idc-page-hero idc-page-hero--blog">
		<span class="idc-page-hero__decor idc-page-hero__decor--blog" aria-hidden="true"></span>
		<div class="idc-container">
			<div class="idc-page-hero__inner">
				<?php
				get_template_part('template-parts/components/breadcrumb', null, [
					'current' => __('Blog', 'instituto-dr-chao'),
				]);
				?>
				<?php if ($eyebrow !== '') : ?>
					<p class="idc-eyebrow idc-eyebrow--accent idc-page-hero__eyebrow"><?php echo esc_html($eyebrow); ?></p>
				<?php endif; ?>
				<h1 class="idc-page-hero__title">
					<?php if ($before !== '') : ?>
						<?php echo esc_html($before); ?>
					<?php endif; ?>
					<?php if ($accent !== '') : ?>
						<span class="idc-page-hero__title-accent"><?php echo esc_html($accent); ?></span>
					<?php endif; ?>
					<?php if ($after !== '') : ?>
						<?php echo esc_html($after); ?>
					<?php endif; ?>
				</h1>
				<?php if ($lead !== '') : ?>
					<p class="idc-page-hero__lead"><?php echo esc_html($lead); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="idc-blog-archive idc-container">
		<?php if (!empty($categories)) : ?>
			<div class="idc-blog-filters" data-idc-blog-filters role="toolbar" aria-label="<?php esc_attr_e('Filtrar por categoria', 'instituto-dr-chao'); ?>">
				<button type="button" class="idc-blog-filters__btn is-active" data-idc-blog-filter="all" aria-pressed="true">
					<?php esc_html_e('Todos', 'instituto-dr-chao'); ?>
				</button>
				<?php foreach ($categories as $cat) : ?>
					<button
						type="button"
						class="idc-blog-filters__btn"
						data-idc-blog-filter="<?php echo esc_attr((string) $cat->term_id); ?>"
						aria-pressed="false"
					>
						<?php echo esc_html($cat->name); ?>
					</button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if (have_posts()) : ?>
			<div class="idc-blog-archive__grid" data-idc-blog-grid>
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
			<p class="idc-blog-archive__empty idc-blog-archive__empty--filter" hidden data-idc-blog-empty>
				<?php esc_html_e('Nenhum post nesta categoria.', 'instituto-dr-chao'); ?>
			</p>
		<?php else : ?>
			<p class="idc-blog-archive__empty"><?php esc_html_e('Nenhum post publicado ainda.', 'instituto-dr-chao'); ?></p>
		<?php endif; ?>
	</section>

	<?php get_template_part('template-parts/blog/newsletter'); ?>
</main>
<?php
get_footer();
