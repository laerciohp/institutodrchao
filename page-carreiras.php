<?php
/**
 * Template Name: Carreiras
 *
 * Figma: Carreiras Desktop 242:16918 / Mobile 242:17166
 * Hero split (texto + foto com offset) → formulário full-width → mapa.
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$before = (string) idc_page_field('idc_page_title_before', 'Faça parte do ');
$accent = (string) idc_page_field('idc_page_title_accent', 'time');
$after  = (string) idc_page_field('idc_page_title_after', '');

$lead = (string) idc_page_field(
	'idc_page_lead',
	"No Instituto Dr. Chao, acreditamos que oferecer um atendimento excepcional começa por ter uma equipe movida por empatia, dedicação e excelência. Se você compartilha do nosso compromisso de acolher, cuidar e transformar a jornada de diagnóstico e reabilitação das pessoas, nós queremos conhecer você. Venha construir uma carreira com propósito.\n\nPreencha o formulário abaixo, anexe seu currículo e dê o primeiro passo para fazer a diferença em cada etapa do nosso trabalho."
);

$hero_image = idc_image_url(
	idc_page_field('idc_page_hero_image', null),
	idc_theme_image('assets/images/pages/hero-carreiras', 'jpg', true)
);
?>

<main id="main" class="site-main site-main--page site-main--carreiras">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'layout'                 => 'split',
		'modifier'               => 'carreiras',
		'eyebrow'                => '',
		'title_before'           => $before,
		'title_accent'           => $accent,
		'title_after'            => $after,
		'lead'                   => $lead,
		'breadcrumb_label'       => __('Carreiras', 'instituto-dr-chao'),
		'breadcrumb_home_label'  => __('Trabalhe conosco', 'instituto-dr-chao'),
		'breadcrumb_home_url'    => get_permalink() ?: home_url('/carreiras/'),
		'image'                  => $hero_image,
		'image_alt'              => __('Equipe e atendimento no Instituto Dr. Chao', 'instituto-dr-chao'),
	]);
	get_template_part('template-parts/page/carreiras-content');
	?>
</main>

<?php
get_footer();
