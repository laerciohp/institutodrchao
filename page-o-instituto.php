<?php
/**
 * Template Name: O Instituto
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$before = (string) idc_page_field('idc_page_title_before', '');
$accent = (string) idc_page_field('idc_page_title_accent', 'Existimos para que ninguém seja definido pela sua dor.');
$after  = (string) idc_page_field('idc_page_title_after', '');
$lead   = (string) idc_page_field(
	'idc_page_lead',
	'Desde 1987, o Instituto Dr. Chao vem transformando a maneira como olhamos para a ortopedia e reabilitação. Nossa história começou com a crença fundamental de que o tratamento médico deve transcender o sintoma, abraçando a complexidade do ser humano. Hoje, somos referência em cuidado integrado, unindo alta precisão técnica a uma escuta empática, garantindo que cada paciente encontre seu caminho único para o bem-estar duradouro e uma vida em movimento pleno.'
);

?>

<main id="main" class="site-main site-main--page">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'layout'           => 'centered',
		'eyebrow'          => (string) idc_page_field('idc_page_eyebrow', ''),
		'title_before'     => $before,
		'title_accent'     => $accent,
		'title_after'      => $after,
		'lead'             => $lead,
		'breadcrumb_label' => __('O Instituto', 'instituto-dr-chao'),
	]);
	get_template_part('template-parts/page/instituto-content');
	get_template_part('template-parts/components/strip-cta', null, [
		'origem' => 'o-instituto',
		'decor'  => true,
		'align'  => 'center',
	]);
	?>
</main>

<?php
get_footer();
