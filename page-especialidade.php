<?php
/**
 * Template Name: Especialidade
 * Template flexível para novas especialidades (e migração Orto/Fisio/Integrativa).
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$slug = get_post_field('post_name', get_queried_object_id()) ?: 'especialidade';
$hero = idc_default_page_hero($slug) ?? [
	'idc_page_eyebrow'      => 'ESPECIALIDADE',
	'idc_page_title_before' => '',
	'idc_page_title_accent' => get_the_title(),
	'idc_page_title_after'  => '',
	'idc_page_lead'         => '',
];

$before = (string) idc_page_field('idc_page_title_before', (string) ($hero['idc_page_title_before'] ?? ''));
$accent = (string) idc_page_field('idc_page_title_accent', (string) ($hero['idc_page_title_accent'] ?? get_the_title()));
$after  = (string) idc_page_field('idc_page_title_after', (string) ($hero['idc_page_title_after'] ?? ''));
$lead   = (string) idc_page_field('idc_page_lead', (string) ($hero['idc_page_lead'] ?? ''));
$eyebrow = (string) idc_page_field('idc_page_eyebrow', (string) ($hero['idc_page_eyebrow'] ?? ''));

$hero_fallback = match ($slug) {
	'ortopedia-regenerativa' => idc_theme_image('assets/images/pages/hero-ortopedia'),
	'fisioterapia'           => idc_theme_image('assets/images/pages/hero-fisioterapia'),
	'medicina-integrativa'   => idc_theme_image('assets/images/pages/hub-integrativa'),
	default                  => idc_theme_image('assets/images/pages/hub-ortopedia'),
};
$hero_image = idc_image_url(idc_page_field('idc_page_hero_image', null), $hero_fallback);
$cta_label  = (string) idc_page_field('idc_page_hero_cta_label', __('Agendar Consulta', 'instituto-dr-chao'));

$blocks = idc_page_field('idc_specialty_blocks', null);
if (!is_array($blocks) || $blocks === []) {
	$blocks = idc_default_specialty_layout_for_slug($slug);
}

$modifier = $slug === 'medicina-integrativa' ? 'integrativa' : '';
?>

<main id="main" class="site-main site-main--page idc-specialty">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'layout'           => 'split',
		'modifier'         => $modifier,
		'eyebrow'          => $eyebrow,
		'title_before'     => $before,
		'title_accent'     => $accent,
		'title_after'      => $after,
		'lead'             => $lead,
		'breadcrumb_label' => get_the_title(),
		'breadcrumb_items' => [
			[
				'label' => __('Especialidades', 'instituto-dr-chao'),
				'url'   => home_url('/especialidades/'),
			],
		],
		'cta_label'        => $cta_label,
		'origem'           => $slug,
		'image'            => $hero_image,
		'image_alt'        => get_the_title() . ' — Instituto Dr. Chao',
	]);

	foreach ($blocks as $block) {
		if (!is_array($block)) {
			continue;
		}
		$layout = (string) ($block['acf_fc_layout'] ?? '');
		switch ($layout) {
			case 'rich_text':
				$body = (string) ($block['body'] ?? '');
				if ($body !== '') {
					echo '<section class="idc-specialty-rich"><div class="idc-container idc-prose">';
					echo wp_kses_post(wpautop($body));
					echo '</div></section>';
				}
				break;

			case 'treatment_faq':
				$cards = [];
				foreach ((array) ($block['cards'] ?? []) as $card) {
					if (!is_array($card)) {
						continue;
					}
					$cards[] = [
						'title' => (string) ($card['title'] ?? ''),
						'intro' => (string) ($card['intro'] ?? ''),
						'faqs'  => is_array($card['faqs'] ?? null) ? $card['faqs'] : [],
						'icon'  => idc_icon_url($card['icon'] ?? null, ''),
					];
				}
				if ($cards === [] && $slug === 'ortopedia-regenerativa') {
					$cards = idc_default_ortopedia_treatments();
				}
				get_template_part('template-parts/page/treatment-faq-cards', null, [
					'title' => (string) ($block['section_title'] ?? __('Tratamentos', 'instituto-dr-chao')),
					'cards' => $cards,
				]);
				break;

			case 'phases':
				$phases = [];
				$defaults = idc_default_fisioterapia_phases();
				foreach ((array) ($block['phases'] ?? []) as $index => $phase) {
					if (!is_array($phase)) {
						continue;
					}
					$phases[] = [
						'number' => (string) ($phase['number'] ?? ($defaults[$index]['number'] ?? '')),
						'label'  => (string) ($phase['label'] ?? ($defaults[$index]['label'] ?? '')),
						'title'  => (string) ($phase['title'] ?? ''),
						'text'   => (string) ($phase['text'] ?? ''),
						'icon'   => idc_icon_url($phase['icon'] ?? null, (string) ($defaults[$index]['icon'] ?? '')),
					];
				}
				if ($phases === []) {
					$phases = $defaults;
				}
				get_template_part('template-parts/page/phases', null, [
					'title'  => (string) ($block['section_title'] ?? __('As 4 fases da recuperação', 'instituto-dr-chao')),
					'phases' => $phases,
				]);
				break;

			case 'bento':
				$items = [];
				$defaults = idc_default_integrativa_grid();
				foreach ((array) ($block['items'] ?? []) as $index => $item) {
					if (!is_array($item)) {
						continue;
					}
					$items[] = [
						'title' => (string) ($item['title'] ?? ''),
						'text'  => (string) ($item['text'] ?? ''),
						'icon'  => idc_icon_url($item['icon'] ?? null, (string) ($defaults[$index]['icon'] ?? '')),
					];
				}
				if ($items === []) {
					$items = $defaults;
				}
				get_template_part('template-parts/page/integrativa-grid', null, [
					'title' => (string) ($block['section_title'] ?? __('Tratamentos', 'instituto-dr-chao')),
					'items' => $items,
				]);
				break;

			case 'faq':
				$faqs = [];
				foreach ((array) ($block['items'] ?? []) as $item) {
					if (!is_array($item)) {
						continue;
					}
					$faqs[] = [
						'question' => (string) ($item['question'] ?? ''),
						'answer'   => (string) ($item['answer'] ?? ''),
					];
				}
				if ($faqs === []) {
					$faqs = idc_default_specialty_faq();
				}
				get_template_part('template-parts/components/accordion', null, [
					'title' => (string) ($block['section_title'] ?? __('Perguntas frequentes', 'instituto-dr-chao')),
					'items' => $faqs,
				]);
				break;

			case 'strip_cta':
				get_template_part(
					'template-parts/components/strip-cta',
					null,
					idc_strip_cta_args_for_page($slug, [
						'title'           => (string) ($block['title'] ?? ''),
						'lead'            => (string) ($block['lead'] ?? ''),
						'label'           => (string) ($block['label'] ?? ''),
						'secondary_label' => (string) ($block['secondary_label'] ?? ''),
						'secondary_url'   => (string) ($block['secondary_url'] ?? ''),
					])
				);
				break;
		}
	}

	// Strip final (campos da página), se nenhum bloco strip_cta foi usado.
	$has_strip_block = false;
	foreach ($blocks as $block) {
		if (is_array($block) && ($block['acf_fc_layout'] ?? '') === 'strip_cta') {
			$has_strip_block = true;
			break;
		}
	}
	if (!$has_strip_block) {
		$strip_overrides = [];
		if ($slug === 'ortopedia-regenerativa') {
			$strip_overrides = [
				'secondary_label' => (string) idc_page_field('idc_strip_secondary_label', __('Ver Fisioterapia Especializada', 'instituto-dr-chao')),
				'secondary_url'   => (string) idc_page_field('idc_strip_secondary_url', home_url('/fisioterapia/')),
			];
		} elseif ($slug === 'medicina-integrativa') {
			$strip_overrides = [
				'secondary_label' => (string) idc_page_field('idc_strip_secondary_label', __('Voltar para Ortopedia Regenerativa', 'instituto-dr-chao')),
				'secondary_url'   => (string) idc_page_field('idc_strip_secondary_url', home_url('/ortopedia-regenerativa/')),
			];
		}
		get_template_part('template-parts/components/strip-cta', null, idc_strip_cta_args_for_page($slug, $strip_overrides));
	}
	?>
</main>

<?php
get_footer();
