<?php
/**
 * Template Name: Fisioterapia
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$eyebrow = function_exists('get_field') && get_field('idc_page_eyebrow')
	? (string) get_field('idc_page_eyebrow')
	: 'FISIOTERAPIA';

$before = function_exists('get_field') && get_field('idc_page_title_before')
	? (string) get_field('idc_page_title_before')
	: 'Reabilitação especializada';

$accent = function_exists('get_field') && get_field('idc_page_title_accent')
	? (string) get_field('idc_page_title_accent')
	: 'em dor e movimento';

$after = function_exists('get_field') && get_field('idc_page_title_after')
	? (string) get_field('idc_page_title_after')
	: '';

$lead = function_exists('get_field') && get_field('idc_page_lead')
	? (string) get_field('idc_page_lead')
	: 'Protocolos personalizados para dor crônica, pós-operatório e recuperação funcional com equipamentos de última geração.';
?>

<main id="main" class="site-main site-main--page idc-specialty">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'eyebrow'          => $eyebrow,
		'title_before'     => $before,
		'title_accent'     => $accent,
		'title_after'      => $after,
		'lead'             => $lead,
		'breadcrumb_label' => __('Fisioterapia', 'instituto-dr-chao'),
	]);
	get_template_part('template-parts/page/specialty-sections', null, ['slug' => 'fisioterapia']);
	get_template_part('template-parts/page/faq-accordion');
	get_template_part('template-parts/components/strip-cta', null, [
		'origem' => 'fisioterapia',
	]);
	?>
</main>

<?php
get_footer();
