<?php
/**
 * Template Name: Contato
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$before = function_exists('get_field') && get_field('idc_page_title_before')
	? (string) get_field('idc_page_title_before')
	: 'Vamos conversar sobre o';

$accent = function_exists('get_field') && get_field('idc_page_title_accent')
	? (string) get_field('idc_page_title_accent')
	: ' seu cuidado?';

$after = function_exists('get_field') && get_field('idc_page_title_after')
	? (string) get_field('idc_page_title_after')
	: '';

$lead = function_exists('get_field') && get_field('idc_page_lead')
	? (string) get_field('idc_page_lead')
	: 'Nossa equipe está pronta para te receber com a atenção e precisão que sua saúde merece. Entre em contato para agendar uma consulta ou tirar dúvidas.';
?>

<main id="main" class="site-main site-main--page site-main--contato">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'layout'           => 'centered',
		'eyebrow'          => '',
		'title_before'     => $before,
		'title_accent'     => $accent,
		'title_after'      => $after,
		'lead'             => $lead,
		'breadcrumb_label' => __('Contato', 'instituto-dr-chao'),
	]);
	get_template_part('template-parts/page/contato-content');
	get_template_part('template-parts/components/strip-cta', null, [
		'origem' => 'contato',
		'decor'  => true,
		'align'  => 'center',
	]);
	?>
</main>

<?php
get_footer();
