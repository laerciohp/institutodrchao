<?php
/**
 * Template Name: Ortopedia Regenerativa
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$eyebrow = (string) idc_page_field('idc_page_eyebrow', 'TRATAMENTOS · ESPECIALIDADE PRINCIPAL');
// Figma 133:1238 — "Ortopedia" serif/terracota + "Regenerativa" Montserrat.
$before = (string) idc_page_field('idc_page_title_before', '');
$accent = (string) idc_page_field('idc_page_title_accent', 'Ortopedia');
$after  = (string) idc_page_field('idc_page_title_after', ' Regenerativa');
// Migra seed antigo (H1 inteiro em before/accent).
if (($before === 'Ortopedia Regenerativa' && $accent === '') || $accent === 'Ortopedia Regenerativa') {
	$before = '';
	$accent = 'Ortopedia';
	$after  = ' Regenerativa';
}
$lead   = (string) idc_page_field(
	'idc_page_lead',
	'Tratamentos que estimulam a recuperação das articulações, reduzem a dor e ajudam você a recuperar seus movimentos e sua qualidade de vida.'
);

$hero_image = idc_image_url(
	idc_page_field('idc_page_hero_image', null),
	idc_theme_image('assets/images/pages/hero-ortopedia')
);
$cta_label = (string) idc_page_field('idc_page_hero_cta_label', __('Agendar Consulta', 'instituto-dr-chao'));

$treatments_title = (string) idc_page_field('idc_orto_treatments_title', __('Tratamentos regenerativos', 'instituto-dr-chao'));
$treatments       = idc_page_field('idc_orto_treatments', null);
if (!is_array($treatments) || $treatments === []) {
	$treatments = idc_default_ortopedia_treatments();
}
?>

<main id="main" class="site-main site-main--page idc-specialty">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'layout'           => 'split',
		'eyebrow'          => $eyebrow,
		'title_before'     => $before,
		'title_accent'     => $accent,
		'title_after'      => $after,
		'lead'             => $lead,
		'breadcrumb_label' => __('Ortopedia Regenerativa', 'instituto-dr-chao'),
		'breadcrumb_items' => [
			[
				'label' => __('Especialidades', 'instituto-dr-chao'),
				'url'   => home_url('/especialidades/'),
			],
		],
		'cta_label'        => $cta_label,
		'origem'           => 'ortopedia-regenerativa',
		'image'            => $hero_image,
		'image_alt'        => __('Ortopedia Regenerativa — Instituto Dr. Chao', 'instituto-dr-chao'),
	]);
	get_template_part('template-parts/page/treatment-faq-cards', null, [
		'title' => $treatments_title,
		'cards' => $treatments,
	]);
	get_template_part(
		'template-parts/components/strip-cta',
		null,
		idc_strip_cta_args_for_page('ortopedia-regenerativa')
	);
	?>
</main>

<?php
get_footer();
