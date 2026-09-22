<?php
/**
 * Template Name: Contato
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$eyebrow = function_exists('get_field') && get_field('idc_page_eyebrow')
	? (string) get_field('idc_page_eyebrow')
	: 'CONTATO';

$before = function_exists('get_field') && get_field('idc_page_title_before')
	? (string) get_field('idc_page_title_before')
	: 'Estamos prontos para';

$accent = function_exists('get_field') && get_field('idc_page_title_accent')
	? (string) get_field('idc_page_title_accent')
	: 'ouvir você';

$after = function_exists('get_field') && get_field('idc_page_title_after')
	? (string) get_field('idc_page_title_after')
	: '';

$lead = function_exists('get_field') && get_field('idc_page_lead')
	? (string) get_field('idc_page_lead')
	: 'Entre em contato por telefone, WhatsApp ou preencha o formulário abaixo.';
?>

<main id="main" class="site-main site-main--page">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'eyebrow'          => $eyebrow,
		'title_before'     => $before,
		'title_accent'     => $accent,
		'title_after'      => $after,
		'lead'             => $lead,
		'breadcrumb_label' => __('Contato', 'instituto-dr-chao'),
	]);
	get_template_part('template-parts/page/contato-content');
	?>
</main>

<?php
get_footer();
