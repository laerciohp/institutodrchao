<?php
/**
 * Template Name: Fisioterapia
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$before = (string) idc_page_field('idc_page_title_before', '');
$accent = (string) idc_page_field('idc_page_title_accent', 'Fisioterapia Especializada em Dor');
$after  = (string) idc_page_field('idc_page_title_after', '');
$lead   = (string) idc_page_field(
	'idc_page_lead',
	'Nossa abordagem integra técnicas avançadas com um cuidado humanizado profundo. Através de 4 fases de recuperação distintas, desenhamos um caminho focado não apenas em tratar os sintomas, mas em restaurar a verdadeira função e o bem-estar do seu corpo.'
);

$hero_image = idc_image_url(
	idc_page_field('idc_page_hero_image', null),
	idc_theme_image('assets/images/pages/hero-fisioterapia')
);
$cta_label = (string) idc_page_field('idc_page_hero_cta_label', __('Agendar Consulta', 'instituto-dr-chao'));

$phases_title = (string) idc_page_field('idc_fisio_phases_title', __('As 4 fases da recuperação', 'instituto-dr-chao'));
$phases_raw   = idc_page_field('idc_fisio_phases', null);
$defaults     = idc_default_fisioterapia_phases();
$phases       = [];
if (is_array($phases_raw) && $phases_raw !== []) {
	foreach ($phases_raw as $index => $phase) {
		if (!is_array($phase)) {
			continue;
		}
		$fallback_icon = (string) ($defaults[$index]['icon'] ?? '');
		$phases[] = [
			'number' => (string) ($phase['number'] ?? ($defaults[$index]['number'] ?? '')),
			'label'  => (string) ($phase['label'] ?? ($defaults[$index]['label'] ?? '')),
			'title'  => (string) ($phase['title'] ?? ''),
			'text'   => (string) ($phase['text'] ?? ''),
			'icon'   => idc_icon_url($phase['icon'] ?? null, $fallback_icon),
		];
	}
}
if ($phases === []) {
	$phases = $defaults;
}
?>

<main id="main" class="site-main site-main--page idc-specialty">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'layout'           => 'split',
		'eyebrow'          => (string) idc_page_field('idc_page_eyebrow', __('ESPECIALIDADE', 'instituto-dr-chao')),
		'title_before'     => $before,
		'title_accent'     => $accent,
		'title_after'      => $after,
		'lead'             => $lead,
		'breadcrumb_label' => __('Fisioterapia', 'instituto-dr-chao'),
		'breadcrumb_items' => [
			[
				'label' => __('Especialidades', 'instituto-dr-chao'),
				'url'   => home_url('/especialidades/'),
			],
		],
		'cta_label'        => $cta_label,
		'origem'           => 'fisioterapia',
		'image'            => $hero_image,
		'image_alt'        => __('Fisioterapia Especializada em Dor — Instituto Dr. Chao', 'instituto-dr-chao'),
	]);
	get_template_part('template-parts/page/phases', null, [
		'title'  => $phases_title,
		'phases' => $phases,
	]);
	$strip = idc_default_strip_cta_for_slug('fisioterapia');
	get_template_part('template-parts/components/strip-cta', null, [
		'origem' => 'fisioterapia',
		'decor'  => true,
		'align'  => 'center',
		'title'  => $strip['idc_strip_title'],
		'lead'   => $strip['idc_strip_lead'],
		'label'  => $strip['idc_strip_label'],
	]);
	?>
</main>

<?php
get_footer();
