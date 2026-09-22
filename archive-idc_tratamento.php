<?php
/**
 * Archive — Tratamentos.
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$eyebrow = (string) idc_option('idc_tx_archive_eyebrow', __('TRATAMENTOS', 'instituto-dr-chao'));
$title   = (string) idc_option('idc_tx_archive_title', __('Conheça nossos tratamentos', 'instituto-dr-chao'));
$lead    = (string) idc_option(
	'idc_tx_archive_lead',
	__('Protocolos regenerativos e de reabilitação orientados pela equipe do Instituto Dr. Chao — da avaliação ao acompanhamento contínuo.', 'instituto-dr-chao')
);

// Divide título para accent no último termo, alinhado ao padrão do hub.
$title_parts  = preg_split('/\s+/', trim($title), -1, PREG_SPLIT_NO_EMPTY) ?: [];
$title_accent = $title_parts !== [] ? (' ' . array_pop($title_parts)) : '';
$title_before = $title_parts !== [] ? implode(' ', $title_parts) : $title;
if ($title_accent === '') {
	$title_before = __('Conheça nossos', 'instituto-dr-chao');
	$title_accent = __(' tratamentos', 'instituto-dr-chao');
}
?>
<main id="main" class="site-main site-main--page">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'layout'           => 'default',
		'decor'            => true,
		'eyebrow'          => $eyebrow,
		'title_before'     => $title_before,
		'title_accent'     => $title_accent,
		'lead'             => $lead,
		'breadcrumb_label' => __('Tratamentos', 'instituto-dr-chao'),
	]);
	?>

	<section class="idc-tratamentos-archive" aria-label="<?php esc_attr_e('Lista de tratamentos', 'instituto-dr-chao'); ?>">
		<span class="idc-tratamentos-archive__decor" aria-hidden="true"></span>
		<div class="idc-container">
			<?php if (have_posts()) : ?>
				<div class="idc-hub__grid idc-tratamentos-archive__grid">
					<?php
					while (have_posts()) :
						the_post();
						get_template_part('template-parts/page/treatment-archive-card');
					endwhile;
					?>
				</div>
				<div class="idc-blog-archive__nav">
					<?php the_posts_pagination(); ?>
				</div>
			<?php else : ?>
				<p class="idc-blog-archive__empty"><?php esc_html_e('Nenhum tratamento publicado ainda.', 'instituto-dr-chao'); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<?php
	get_template_part('template-parts/components/strip-cta', null, [
		'origem' => 'tratamentos',
		'decor'  => true,
		'align'  => 'center',
	]);
	?>
</main>
<?php
get_footer();
