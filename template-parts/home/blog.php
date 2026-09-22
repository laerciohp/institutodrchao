<?php
/**
 * Blog preview — posts nativos (prioriza ortopedia/dor na home).
 *
 * @package Instituto_Dr_Chao
 */

$title = (string) idc_option('idc_blog_title', 'Últimas do Blog');
$lead  = (string) idc_option('idc_blog_lead', 'Informação de qualidade para a sua saúde.');
$cta_l = (string) idc_option('idc_blog_cta_label', 'Ver todos os artigos');
$blog  = get_permalink(get_option('page_for_posts')) ?: home_url('/blog/');

$preferred_slugs = [
	'o-que-e-a-ortopedia-regenerativa-e-como-ela-transforma-vidas',
	'como-o-uso-do-celular-pode-piorar-a-sua-dor',
	'8-dicas-para-aliviar-as-dores-musculares-apos-o-treino-na-academia',
];

$preferred_ids = [];
$preferred_q   = new WP_Query([
	'post_type'           => 'post',
	'post_name__in'       => $preferred_slugs,
	'posts_per_page'      => 3,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
]);

$by_slug = [];
foreach ($preferred_q->posts as $post) {
	$by_slug[$post->post_name] = (int) $post->ID;
}
foreach ($preferred_slugs as $slug) {
	if (isset($by_slug[$slug])) {
		$preferred_ids[] = $by_slug[$slug];
	}
}
wp_reset_postdata();

$odonto_slugs = idc_odonto_post_slugs();

$post_ids = $preferred_ids;
if (count($post_ids) < 3) {
	$fill = new WP_Query([
		'post_type'           => 'post',
		'posts_per_page'      => 12,
		'post__not_in'        => $post_ids,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	]);
	foreach ($fill->posts as $post) {
		if (in_array($post->post_name, $odonto_slugs, true)) {
			continue;
		}
		$post_ids[] = (int) $post->ID;
		if (count($post_ids) >= 3) {
			break;
		}
	}
	wp_reset_postdata();
}

if ($post_ids !== []) {
	$query = new WP_Query([
		'post_type'           => 'post',
		'post__in'            => $post_ids,
		'orderby'             => 'post__in',
		'posts_per_page'      => 3,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
	]);
} else {
	$query = new WP_Query([
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'post_status'         => 'publish',
	]);
}
?>
<section class="idc-blog-preview" aria-labelledby="idc-blog-title">
	<div class="idc-container">
		<header class="idc-blog-preview__header">
			<div>
				<h2 id="idc-blog-title" class="idc-blog-preview__title"><?php echo esc_html($title); ?></h2>
				<p class="idc-blog-preview__lead"><?php echo esc_html($lead); ?></p>
			</div>
			<a class="idc-blog-preview__all" href="<?php echo esc_url($blog); ?>">
				<?php echo esc_html($cta_l); ?>
				<img src="<?php echo esc_url(idc_asset('assets/icons/icon-arrow-accent.svg')); ?>" alt="" width="12" height="12" decoding="async">
			</a>
		</header>

		<?php if ($query->have_posts()) : ?>
			<div class="idc-blog-preview__grid">
				<?php
				while ($query->have_posts()) :
					$query->the_post();
					get_template_part('template-parts/blog/card', null, [
						'variant'   => 'home',
						'show_cat'  => true,
						'show_meta' => true,
						'heading'   => 'h3',
					]);
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e('Publique artigos no Blog para preenchê-los aqui automaticamente.', 'instituto-dr-chao'); ?></p>
		<?php endif; ?>
	</div>
</section>
