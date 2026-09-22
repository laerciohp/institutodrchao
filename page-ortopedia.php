<?php
/**
 * Template Name: Ortopedia Regenerativa
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$eyebrow = function_exists('get_field') && get_field('idc_page_eyebrow')
	? (string) get_field('idc_page_eyebrow')
	: 'ORTOPEDIA REGENERATIVA';

$before = function_exists('get_field') && get_field('idc_page_title_before')
	? (string) get_field('idc_page_title_before')
	: 'Precisão diagnóstica e';

$accent = function_exists('get_field') && get_field('idc_page_title_accent')
	? (string) get_field('idc_page_title_accent')
	: 'tratamento regenerativo';

$after = function_exists('get_field') && get_field('idc_page_title_after')
	? (string) get_field('idc_page_title_after')
	: 'de alta complexidade';

$lead = function_exists('get_field') && get_field('idc_page_lead')
	? (string) get_field('idc_page_lead')
	: 'Diagnóstico preciso, tratamentos conservadores e cirúrgicos com foco na recuperação funcional e regeneração tecidual.';
?>

<main id="main" class="site-main site-main--page idc-specialty">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'eyebrow'          => $eyebrow,
		'title_before'     => $before,
		'title_accent'     => $accent,
		'title_after'      => $after,
		'lead'             => $lead,
		'breadcrumb_label' => __('Ortopedia Regenerativa', 'instituto-dr-chao'),
	]);
	get_template_part('template-parts/page/specialty-sections', null, ['slug' => 'ortopedia']);
	get_template_part('template-parts/page/faq-accordion');
	get_template_part('template-parts/components/strip-cta', null, [
		'origem' => 'ortopedia-regenerativa',
	]);
	?>
</main>

<?php
get_footer();
