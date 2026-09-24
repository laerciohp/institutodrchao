<?php
/**
 * Template Name: O Instituto
 *
 * @package Instituto_Dr_Chao
 */

get_header();

// Figma 133:2168 — accent só em "pela sua dor."
$before = (string) idc_page_field('idc_page_title_before', 'Existimos para que ninguém seja definido ');
$accent = (string) idc_page_field('idc_page_title_accent', 'pela sua dor.');
$after  = (string) idc_page_field('idc_page_title_after', '');

// Migra seed antigo (H1 inteiro no accent).
$legacy_full = 'Existimos para que ninguém seja definido pela sua dor.';
if ($accent === $legacy_full || ($before === '' && $accent === $legacy_full)) {
	$before = 'Existimos para que ninguém seja definido ';
	$accent = 'pela sua dor.';
	$after  = '';
}

$figma_lead = 'Desde 1987, o Instituto Dr. Chao vem transformando a maneira como olhamos para a ortopedia e reabilitação. Nossa história começou com a crença fundamental de que o tratamento médico deve transcender o sintoma, abraçando a complexidade do ser humano. Hoje, somos referência em cuidado integrado, unindo alta precisão técnica a uma escuta empática, garantindo que cada paciente encontre seu caminho único para o bem-estar duradouro e uma vida em movimento pleno.';
$lead       = (string) idc_page_field('idc_page_lead', $figma_lead);
$short_seed = 'Desde 1987, unimos vanguarda médica e terapias integrativas em um ambiente pensado para a sua verdadeira recuperação.';
if ($lead === '' || $lead === $short_seed) {
	$lead = $figma_lead;
}

?>

<main id="main" class="site-main site-main--page site-main--instituto">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'layout'           => 'centered',
		'modifier'         => 'instituto',
		// Figma Desktop: sem eyebrow no hero (só breadcrumb + H1 + lead).
		'eyebrow'          => '',
		'title_before'     => $before,
		'title_accent'     => $accent,
		'title_after'      => $after,
		'lead'             => $lead,
		'breadcrumb_label' => __('O Instituto', 'instituto-dr-chao'),
		'decor'            => true,
	]);
	get_template_part('template-parts/page/instituto-content');
	// Figma 133:2168 — conteúdo vai direto ao footer (sem strip CTA).
	?>
</main>

<?php
get_footer();
