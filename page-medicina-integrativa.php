<?php
/**
 * Template Name: Medicina Integrativa
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$before = (string) idc_page_field('idc_page_title_before', 'Medicina ');
$accent = (string) idc_page_field('idc_page_title_accent', 'Integrativa');
$after  = (string) idc_page_field('idc_page_title_after', ' e Regenerativa.');

// Figma 133:1837 — lead do hero (seed/ACF antigo encurtava).
$figma_lead = 'Uma abordagem que enxerga o paciente como um todo. A medicina integrativa atua em sinergia com os tratamentos ortopédicos, buscando equilibrar o organismo, reduzir inflamações sistêmicas e otimizar a capacidade natural de cura e regeneração celular do corpo.';
$lead       = (string) idc_page_field('idc_page_lead', $figma_lead);
$short_seed = 'Acupuntura, controle da dor crônica e suporte nutricional integrados ao seu plano de tratamento ortopédico e fisioterapêutico.';
if ($lead === '' || $lead === $short_seed) {
	$lead = $figma_lead;
}

$hero_image = idc_image_url(
	idc_page_field('idc_page_hero_image', null),
	idc_theme_image('assets/images/pages/hero-integrativa', 'jpg', true)
);

$grid_title = (string) idc_page_field('idc_integrativa_grid_title', __('Tratamentos Integrativos', 'instituto-dr-chao'));
$grid_raw   = idc_page_field('idc_integrativa_grid', null);
$defaults   = idc_default_integrativa_grid();
$grid       = [];
if (is_array($grid_raw) && $grid_raw !== []) {
	foreach ($grid_raw as $index => $item) {
		if (!is_array($item)) {
			continue;
		}
		$fallback_icon = (string) ($defaults[$index]['icon'] ?? '');
		$grid[] = [
			'title' => (string) ($item['title'] ?? ''),
			'text'  => (string) ($item['text'] ?? ''),
			'icon'  => idc_icon_url($item['icon'] ?? null, $fallback_icon),
		];
	}
}
if ($grid === []) {
	$grid = $defaults;
}
?>

<main id="main" class="site-main site-main--page idc-specialty">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'layout'           => 'split',
		'modifier'         => 'integrativa',
		'eyebrow'          => (string) idc_page_field('idc_page_eyebrow', ''),
		'title_before'     => $before,
		'title_accent'     => $accent,
		'title_after'      => $after,
		'lead'             => $lead,
		'breadcrumb_label' => __('Medicina Integrativa', 'instituto-dr-chao'),
		'breadcrumb_items' => [
			[
				'label' => __('Especialidades', 'instituto-dr-chao'),
				'url'   => home_url('/especialidades/'),
			],
		],
		'cta_label'        => (string) idc_page_field('idc_page_hero_cta_label', __('Agendar Consulta', 'instituto-dr-chao')),
		'origem'           => 'medicina-integrativa',
		'image'            => $hero_image,
		'image_alt'        => __('Medicina Integrativa e Regenerativa — Instituto Dr. Chao', 'instituto-dr-chao'),
	]);
	get_template_part('template-parts/page/integrativa-grid', null, [
		'title' => $grid_title,
		'items' => $grid,
	]);
	get_template_part(
		'template-parts/components/strip-cta',
		null,
		idc_strip_cta_args_for_page('medicina-integrativa', [
			'secondary_label' => (string) idc_page_field('idc_strip_secondary_label', __('Voltar para Ortopedia Regenerativa', 'instituto-dr-chao')),
			'secondary_url'   => (string) idc_page_field('idc_strip_secondary_url', home_url('/ortopedia-regenerativa/')),
		])
	);
	?>
</main>

<?php
get_footer();
