<?php
/**
 * Taxonomy archive — Especialidade (tratamentos filtrados).
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$term = get_queried_object();
$name = $term instanceof WP_Term ? $term->name : __('Especialidade', 'instituto-dr-chao');
$desc = $term instanceof WP_Term ? (string) $term->description : '';
?>

<main id="main" class="site-main site-main--page">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'layout'           => 'default',
		'decor'            => true,
		'eyebrow'          => __('ESPECIALIDADE', 'instituto-dr-chao'),
		'title_before'     => '',
		'title_accent'     => $name,
		'title_after'      => '',
		'lead'             => $desc !== '' ? $desc : sprintf(
			/* translators: %s: specialty name */
			__('Tratamentos relacionados a %s no Instituto Dr. Chao.', 'instituto-dr-chao'),
			$name
		),
		'breadcrumb_label' => $name,
		'breadcrumb_items' => [
			[
				'label' => __('Tratamentos', 'instituto-dr-chao'),
				'url'   => get_post_type_archive_link('idc_tratamento') ?: home_url('/tratamentos/'),
			],
		],
	]);
	?>

	<section class="idc-tratamentos-archive" aria-label="<?php esc_attr_e('Lista de tratamentos', 'instituto-dr-chao'); ?>">
		<span class="idc-tratamentos-archive__decor" aria-hidden="true"></span>
		<div class="idc-container">
			<?php if (have_posts()) : ?>
				<div class="idc-hub-cards__grid idc-tratamentos-archive__grid">
					<?php
					$i = 0;
					while (have_posts()) :
						the_post();
						$tones = ['sand', 'peach', 'cream'];
						get_template_part('template-parts/page/treatment-archive-card', null, [
							'tone' => $tones[$i % 3],
						]);
						$i++;
					endwhile;
					?>
				</div>
				<div class="idc-blog-archive__nav">
					<?php the_posts_pagination(); ?>
				</div>
			<?php else : ?>
				<p class="idc-blog-archive__empty"><?php esc_html_e('Nenhum tratamento nesta especialidade ainda.', 'instituto-dr-chao'); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<?php
	get_template_part('template-parts/components/strip-cta', null, idc_strip_cta_args_for_page('especialidades', [
		'origem' => 'especialidade-tax',
	]));
	?>
</main>

<?php
get_footer();
