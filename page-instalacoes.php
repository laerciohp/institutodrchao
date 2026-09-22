<?php
/**
 * Template Name: Instalações
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$eyebrow = function_exists('get_field') && get_field('idc_page_eyebrow')
	? (string) get_field('idc_page_eyebrow')
	: 'INSTALAÇÕES';

$before = function_exists('get_field') && get_field('idc_page_title_before')
	? (string) get_field('idc_page_title_before')
	: 'Um ambiente pensado para';

$accent = function_exists('get_field') && get_field('idc_page_title_accent')
	? (string) get_field('idc_page_title_accent')
	: 'o seu cuidado';

$after = function_exists('get_field') && get_field('idc_page_title_after')
	? (string) get_field('idc_page_title_after')
	: '';

$lead = function_exists('get_field') && get_field('idc_page_lead')
	? (string) get_field('idc_page_lead')
	: 'Salas amplas, equipamentos modernos e espaços de acolhimento — tudo preparado para a sua jornada de recuperação.';
?>

<main id="main" class="site-main site-main--page">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'eyebrow'          => $eyebrow,
		'title_before'     => $before,
		'title_accent'     => $accent,
		'title_after'      => $after,
		'lead'             => $lead,
		'breadcrumb_label' => __('Instalações', 'instituto-dr-chao'),
		'centered'         => true,
	]);
	get_template_part('template-parts/page/instalacoes-content');
	get_template_part('template-parts/components/strip-cta', null, [
		'origem' => 'instalacoes',
		'align'  => 'center',
	]);
	?>
</main>

<?php
get_footer();
