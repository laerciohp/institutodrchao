<?php
/**
 * Template principal.
 *
 * @package Instituto_Dr_Chao
 */

get_header();
?>

<main id="main" class="site-main">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<article <?php post_class(); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<p><?php esc_html_e('Nenhum conteúdo encontrado.', 'instituto-dr-chao'); ?></p>
	<?php endif; ?>
</main>

<?php
get_footer();
