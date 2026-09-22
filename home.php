<?php
/**
 * Listagem do blog.
 *
 * @package Instituto_Dr_Chao
 */

get_header();
?>
<main id="main" class="site-main site-main--page site-main--blog">
	<section class="idc-page-hero idc-page-hero--centered">
		<div class="idc-container">
			<div class="idc-page-hero__inner">
				<?php
				get_template_part('template-parts/components/breadcrumb', null, [
					'current' => __('Blog', 'instituto-dr-chao'),
				]);
				?>
				<h1 class="idc-page-hero__title">
					<span class="idc-page-hero__title-accent"><?php esc_html_e('Conteúdos', 'instituto-dr-chao'); ?></span>
					<?php esc_html_e(' para sua saúde', 'instituto-dr-chao'); ?>
				</h1>
				<p class="idc-page-hero__lead"><?php esc_html_e('Artigos sobre ortopedia, reabilitação e medicina integrativa.', 'instituto-dr-chao'); ?></p>
			</div>
		</div>
	</section>

	<section class="idc-blog-archive idc-container">
		<?php if (have_posts()) : ?>
			<div class="idc-blog-archive__grid">
				<?php while (have_posts()) : the_post(); ?>
					<article <?php post_class('idc-blog-card'); ?>>
						<a class="idc-blog-card__link" href="<?php the_permalink(); ?>">
							<?php if (has_post_thumbnail()) : ?>
								<div class="idc-blog-card__media">
									<?php the_post_thumbnail('medium_large'); ?>
								</div>
							<?php endif; ?>
							<div class="idc-blog-card__body">
								<time class="idc-blog-card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
								<h2 class="idc-blog-card__title"><?php the_title(); ?></h2>
								<p class="idc-blog-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
							</div>
						</a>
					</article>
				<?php endwhile; ?>
			</div>
			<div class="idc-blog-archive__nav">
				<?php the_posts_pagination(); ?>
			</div>
		<?php else : ?>
			<p class="idc-blog-archive__empty"><?php esc_html_e('Nenhum post publicado ainda.', 'instituto-dr-chao'); ?></p>
		<?php endif; ?>
	</section>
</main>
<?php
get_footer();
