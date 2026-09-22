<?php
/**
 * Front page — Home (ordem Figma).
 *
 * @package Instituto_Dr_Chao
 */

get_header();
?>

<main id="main" class="site-main">
	<?php get_template_part('template-parts/home/hero'); ?>
	<?php get_template_part('template-parts/home/trust-bar'); ?>
	<?php get_template_part('template-parts/home/pillars'); ?>
	<?php get_template_part('template-parts/home/why'); ?>
	<?php get_template_part('template-parts/home/testimonials'); ?>
	<?php get_template_part('template-parts/home/team'); ?>
	<?php get_template_part('template-parts/home/blog'); ?>
	<?php get_template_part('template-parts/home/final-cta'); ?>
</main>

<?php
get_footer();
