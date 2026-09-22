<?php
/**
 * Template Name: Carreiras
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$eyebrow = function_exists('get_field') && get_field('idc_page_eyebrow')
	? (string) get_field('idc_page_eyebrow')
	: 'TRABALHE CONOSCO';

$before = function_exists('get_field') && get_field('idc_page_title_before')
	? (string) get_field('idc_page_title_before')
	: 'Faça parte do';

$accent = function_exists('get_field') && get_field('idc_page_title_accent')
	? (string) get_field('idc_page_title_accent')
	: 'time';

$after = function_exists('get_field') && get_field('idc_page_title_after')
	? (string) get_field('idc_page_title_after')
	: '';

$lead = function_exists('get_field') && get_field('idc_page_lead')
	? (string) get_field('idc_page_lead')
	: 'No Instituto Dr. Chao, acreditamos que oferecer um atendimento excepcional começa por ter uma equipe movida por empatia, dedicação e excelência. Se você compartilha do nosso compromisso de acolher, cuidar e transformar a jornada de diagnóstico e reabilitação das pessoas, nós queremos conhecer você. Venha construir uma carreira com propósito. Preencha o formulário abaixo, anexe seu currículo e dê o primeiro passo para fazer a diferença em cada etapa do nosso trabalho.';
?>

<main id="main" class="site-main site-main--page">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'eyebrow'          => $eyebrow,
		'title_before'     => $before,
		'title_accent'     => $accent,
		'title_after'      => $after,
		'lead'             => $lead,
		'breadcrumb_label' => __('Carreiras', 'instituto-dr-chao'),
	]);
	get_template_part('template-parts/page/carreiras-content');
	?>
</main>

<?php
get_footer();
