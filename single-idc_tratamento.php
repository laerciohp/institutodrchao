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
	?>
	<main id="main" class="site-main site-main--page idc-specialty">
		<?php
		get_template_part('template-parts/page/page-hero', null, [
			'layout'           => $thumb ? 'split' : 'centered',
			'eyebrow'          => $eyebrow,
			'title_accent'     => get_the_title(),
			'lead'             => $lead,
			'breadcrumb_label' => get_the_title(),
			'breadcrumb_items' => [
				[
					'label' => __('Tratamentos', 'instituto-dr-chao'),
					'url'   => get_post_type_archive_link('idc_tratamento') ?: home_url('/tratamentos/'),
				],
			],
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

				<?php if ($faqs !== []) : ?>
					<div class="idc-accordion idc-treatment-single__faqs" data-idc-accordion>
						<h2 class="idc-specialty__block-title"><?php esc_html_e('Dúvidas frequentes', 'instituto-dr-chao'); ?></h2>
						<?php foreach ($faqs as $index => $item) :
							$question = (string) ($item['question'] ?? '');
							$answer   = (string) ($item['answer'] ?? '');
							if ($question === '') {
								continue;
							}
							$id = 'idc-tx-single-faq-' . ($index + 1);
							?>
							<div class="idc-accordion__item">
								<h3 class="idc-accordion__heading">
									<button
										type="button"
										class="idc-accordion__trigger"
										id="<?php echo esc_attr($id); ?>-trigger"
										aria-expanded="false"
										aria-controls="<?php echo esc_attr($id); ?>-panel"
										data-idc-accordion-trigger
									>
										<span><?php echo esc_html($question); ?></span>
										<img class="idc-accordion__chevron" src="<?php echo esc_url(idc_asset('assets/icons/chevron-down.svg')); ?>" alt="" width="8" height="5" decoding="async" aria-hidden="true">
									</button>
								</h3>
								<div
									class="idc-accordion__panel"
									id="<?php echo esc_attr($id); ?>-panel"
									role="region"
									aria-labelledby="<?php echo esc_attr($id); ?>-trigger"
									hidden
								>
									<p><?php echo esc_html($answer); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>

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
