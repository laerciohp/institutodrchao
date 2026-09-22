<?php
/**
 * Single — Tratamento.
 *
 * @package Instituto_Dr_Chao
 */

get_header();

while (have_posts()) :
	the_post();

	$eyebrow = (string) idc_page_field('idc_tx_eyebrow', __('TRATAMENTO', 'instituto-dr-chao'));
	$lead    = (string) idc_page_field('idc_tx_lead', get_the_excerpt());
	$faqs    = idc_page_field('idc_tx_faqs', []);
	if (!is_array($faqs)) {
		$faqs = [];
	}
	$cta_label = (string) idc_page_field('idc_tx_cta_label', __('Agendar Consulta', 'instituto-dr-chao'));
	$rel_label = (string) idc_page_field('idc_tx_related_label', __('Ver Ortopedia Regenerativa', 'instituto-dr-chao'));
	$rel_url   = (string) idc_page_field('idc_tx_related_url', '/ortopedia-regenerativa/');
	if ($rel_url !== '' && str_starts_with($rel_url, '/') && !str_starts_with($rel_url, '//')) {
		$rel_url = home_url($rel_url);
	}

	$thumb = get_the_post_thumbnail_url(get_the_ID(), 'large');
	$terms = get_the_terms(get_the_ID(), 'idc_especialidade');
	$term  = (is_array($terms) && $terms !== [] && !is_wp_error($terms)) ? $terms[0] : null;

	$breadcrumb_items = [
		[
			'label' => __('Tratamentos', 'instituto-dr-chao'),
			'url'   => get_post_type_archive_link('idc_tratamento') ?: home_url('/tratamentos/'),
		],
	];
	if ($term instanceof WP_Term) {
		$term_link = get_term_link($term);
		if (!is_wp_error($term_link) && is_string($term_link) && $term_link !== '') {
			$breadcrumb_items[] = [
				'label' => $term->name,
				'url'   => $term_link,
			];
		}
	}

	$related = new WP_Query([
		'post_type'      => 'idc_tratamento',
		'posts_per_page' => 3,
		'post__not_in'   => [get_the_ID()],
		'orderby'        => 'rand',
		'no_found_rows'  => true,
	]);
	?>
	<main id="main" class="site-main site-main--page idc-specialty idc-treatment-single-page">
		<?php
		get_template_part('template-parts/page/page-hero', null, [
			'layout'           => $thumb ? 'split' : 'centered',
			'eyebrow'          => $eyebrow,
			'title_accent'     => get_the_title(),
			'lead'             => $lead,
			'breadcrumb_label' => get_the_title(),
			'breadcrumb_items' => $breadcrumb_items,
			'cta_label'        => $cta_label,
			'origem'           => 'tratamento-' . get_post_field('post_name'),
			'image'            => $thumb ?: '',
			'image_alt'        => get_the_title(),
		]);
		?>

		<section class="idc-treatment-single idc-specialty__block">
			<div class="idc-container">
				<div class="idc-treatment-single__content idc-single__content">
					<?php the_content(); ?>
				</div>
			</div>
		</section>

		<?php if ($faqs !== []) : ?>
			<?php
			get_template_part('template-parts/page/faq-accordion', null, [
				'title' => __('Dúvidas frequentes', 'instituto-dr-chao'),
				'items' => $faqs,
			]);
			?>
		<?php endif; ?>

		<?php if ($related->have_posts()) : ?>
			<section class="idc-tratamentos-related idc-specialty__block" aria-labelledby="idc-tx-related-title">
				<div class="idc-container">
					<header class="idc-specialty__block-header">
						<p class="idc-eyebrow idc-eyebrow--accent"><?php esc_html_e('CONTINUE EXPLORANDO', 'instituto-dr-chao'); ?></p>
						<h2 id="idc-tx-related-title" class="idc-specialty__block-title">
							<?php esc_html_e('Outros tratamentos', 'instituto-dr-chao'); ?>
						</h2>
					</header>
					<div class="idc-hub-cards__grid idc-tratamentos-archive__grid">
						<?php
						$i = 0;
						while ($related->have_posts()) :
							$related->the_post();
							$tones = ['sand', 'peach', 'cream'];
							get_template_part('template-parts/page/treatment-archive-card', null, [
								'tone' => $tones[$i % 3],
							]);
							$i++;
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php
		get_template_part('template-parts/components/strip-cta', null, [
			'origem'          => 'tratamento-' . get_post_field('post_name'),
			'label'           => $cta_label,
			'secondary_label' => $rel_label,
			'secondary_url'   => $rel_url,
			'decor'           => true,
			'align'           => 'center',
		]);
		?>
	</main>
	<?php
endwhile;

get_footer();
