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
	get_template_part('template-parts/components/strip-cta', null, idc_strip_cta_args_for_page('especialidades'));
	?>
</main>

<?php
get_footer();
