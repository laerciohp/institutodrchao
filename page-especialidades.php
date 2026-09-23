<?php
/**
 * Template Name: Especialidades Hub
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$eyebrow = (string) idc_page_field('idc_page_eyebrow', 'NOSSOS TRATAMENTOS');
$before = (string) idc_page_field('idc_page_title_before', '');
$accent = (string) idc_page_field('idc_page_title_accent', 'Cuidar de você');
$after  = (string) idc_page_field('idc_page_title_after', ' é a nossa maior missão.');
$lead   = (string) idc_page_field(
	'idc_page_lead',
	'Cada pilar reúne especialistas que trabalham juntos para devolver movimento, bem-estar e qualidade de vida.'
);
?>

<main id="main" class="site-main site-main--page">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'layout'           => 'default',
		'decor'            => true,
		'eyebrow'          => $eyebrow,
		'title_before'     => $before,
		'title_accent'     => $accent,
		'title_after'      => $after,
		'lead'             => $lead,
		'breadcrumb_label' => __('Especialidades', 'instituto-dr-chao'),
	]);
	get_template_part('template-parts/page/hub-cards');
	$strip = idc_default_strip_cta_for_slug('especialidades');
	get_template_part('template-parts/components/strip-cta', null, [
		'origem' => 'especialidades',
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
