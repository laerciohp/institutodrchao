<?php
/**
 * Template Name: Medicina Integrativa
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$eyebrow = function_exists('get_field') && get_field('idc_page_eyebrow')
	? (string) get_field('idc_page_eyebrow')
	: 'MEDICINA INTEGRATIVA';

$before = function_exists('get_field') && get_field('idc_page_title_before')
	? (string) get_field('idc_page_title_before')
	: 'Cuidado sistêmico para';

$accent = function_exists('get_field') && get_field('idc_page_title_accent')
	? (string) get_field('idc_page_title_accent')
	: 'bem-estar contínuo';

$after = function_exists('get_field') && get_field('idc_page_title_after')
	? (string) get_field('idc_page_title_after')
	: '';

$lead = function_exists('get_field') && get_field('idc_page_lead')
	? (string) get_field('idc_page_lead')
	: 'Acupuntura, controle da dor crônica e suporte nutricional integrados ao seu plano de tratamento ortopédico e fisioterapêutico.';
?>

<main id="main" class="site-main site-main--page idc-specialty">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'eyebrow'          => $eyebrow,
		'title_before'     => $before,
		'title_accent'     => $accent,
		'title_after'      => $after,
		'lead'             => $lead,
		'breadcrumb_label' => __('Medicina Integrativa', 'instituto-dr-chao'),
	]);
	get_template_part('template-parts/page/specialty-sections', null, ['slug' => 'integrativa']);
	get_template_part('template-parts/page/faq-accordion');
	get_template_part('template-parts/components/strip-cta', null, [
		'origem' => 'medicina-integrativa',
	]);
	?>
</main>

<?php
get_footer();
