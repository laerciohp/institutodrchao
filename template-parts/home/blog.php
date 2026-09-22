<?php
/**
 * Blog preview — posts nativos.
 *
 * @package Instituto_Dr_Chao
 */

$title = (string) idc_option('idc_blog_title', 'Últimas do Blog');
$lead  = (string) idc_option('idc_blog_lead', 'Informação de qualidade para a sua saúde.');
$cta_l = (string) idc_option('idc_blog_cta_label', 'Ver todos os artigos');
$blog  = get_permalink(get_option('page_for_posts')) ?: home_url('/blog/');

$query = new WP_Query([
	'post_type'           => 'post',
	'posts_per_page'      => 3,
	'ignore_sticky_posts' => true,
	'post_status'         => 'publish',
]);
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
					$cats = get_the_category();
					$cat  = $cats ? $cats[0]->name : '';
					?>
					<a class="idc-blog-card" href="<?php the_permalink(); ?>">
						<div class="idc-blog-card__media">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('medium_large', ['loading' => 'lazy']); ?>
							<?php endif; ?>
							<?php if ($cat !== '') : ?>
								<span class="idc-blog-card__cat"><?php echo esc_html($cat); ?></span>
							<?php endif; ?>
						</div>
						<div class="idc-blog-card__body">
							<h3 class="idc-blog-card__title"><?php the_title(); ?></h3>
							<p class="idc-blog-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
							<div class="idc-blog-card__meta">
								<span><?php echo esc_html(get_the_date('d M Y')); ?></span>
								<span><?php esc_html_e('Ler mais', 'instituto-dr-chao'); ?></span>
							</div>
						</div>
					</a>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e('Publique artigos no Blog para preenchê-los aqui automaticamente.', 'instituto-dr-chao'); ?></p>
		<?php endif; ?>
	</div>
</section>
