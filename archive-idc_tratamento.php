<?php
/**
 * Archive — Tratamentos.
 *
 * @package Instituto_Dr_Chao
 */

get_header();
?>
<main id="main" class="site-main site-main--page">
	<?php
	get_template_part('template-parts/page/page-hero', null, [
		'eyebrow'          => __('TRATAMENTOS', 'instituto-dr-chao'),
		'title_before'     => __('Conheça nossos', 'instituto-dr-chao'),
		'title_accent'     => __(' tratamentos', 'instituto-dr-chao'),
		'lead'             => __('Protocolos regenerativos e de reabilitação orientados pela equipe do Instituto Dr. Chao.', 'instituto-dr-chao'),
		'breadcrumb_label' => __('Tratamentos', 'instituto-dr-chao'),
		'centered'         => true,
	]);
	?>

	<section class="idc-blog-archive idc-container" aria-label="<?php esc_attr_e('Lista de tratamentos', 'instituto-dr-chao'); ?>">
		<?php if (have_posts()) : ?>
			<div class="idc-hub__grid idc-tratamentos-archive__grid">
				<?php
				while (have_posts()) :
					the_post();
					$excerpt = get_the_excerpt();
					$thumb   = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
					?>
					<article class="idc-hub-card">
						<?php if ($thumb) : ?>
							<div class="idc-hub-card__media">
								<img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" decoding="async">
							</div>
						<?php endif; ?>
						<div class="idc-hub-card__body">
							<h2 class="idc-hub-card__title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
							<?php if ($excerpt !== '') : ?>
								<p class="idc-hub-card__text"><?php echo esc_html($excerpt); ?></p>
							<?php endif; ?>
							<a class="idc-hub-card__link" href="<?php the_permalink(); ?>">
								<?php esc_html_e('Saiba mais', 'instituto-dr-chao'); ?>
							</a>
						</div>
					</article>
					<?php
				endwhile;
				?>
			</div>
			<div class="idc-blog-archive__nav">
				<?php the_posts_pagination(); ?>
			</div>
		<?php else : ?>
			<p class="idc-blog-archive__empty"><?php esc_html_e('Nenhum tratamento publicado ainda.', 'instituto-dr-chao'); ?></p>
		<?php endif; ?>
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
