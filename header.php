<?php
/**
 * Header.
 *
 * @package Instituto_Dr_Chao
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Ir para o conteúdo', 'instituto-dr-chao'); ?></a>
<header class="site-header" role="banner">
	<div class="site-branding">
		<?php if (has_custom_logo()) : ?>
			<?php the_custom_logo(); ?>
		<?php else : ?>
			<p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></p>
		<?php endif; ?>
	</div>
	<nav class="site-nav" aria-label="<?php esc_attr_e('Principal', 'instituto-dr-chao'); ?>">
		<?php
		wp_nav_menu([
			'theme_location' => 'primary',
			'container'      => false,
			'fallback_cb'    => false,
		]);
		?>
	</nav>
</header>
