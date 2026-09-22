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
	<?php
	$scripts_head = idc_option('idc_scripts_head', '');
	if (is_string($scripts_head) && $scripts_head !== '') {
		echo $scripts_head; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- scripts configurados no painel.
	}
	wp_head();
	?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Ir para o conteúdo', 'instituto-dr-chao'); ?></a>

<header class="idc-header" role="banner">
	<div class="idc-header__inner">
		<?php get_template_part('template-parts/components/logo', null, ['variant' => 'dark']); ?>

		<nav class="idc-nav" aria-label="<?php esc_attr_e('Principal', 'instituto-dr-chao'); ?>">
			<?php
			wp_nav_menu([
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'idc-nav__list',
				'fallback_cb'    => 'idc_nav_fallback',
				'depth'          => 2,
				'link_before'    => '',
				'link_after'     => '',
			]);
			?>
		</nav>

		<?php
		get_template_part('template-parts/components/button', null, [
			'label'    => __('Agendar Consulta', 'instituto-dr-chao'),
			'variant'  => 'navy',
			'class'    => 'idc-header__cta',
			'origem'   => is_front_page() ? 'home' : 'header',
			'external' => true,
		]);
		?>

		<button
			type="button"
			class="idc-nav-toggle"
			data-idc-nav-toggle
			aria-expanded="false"
			aria-controls="idc-nav-drawer"
			aria-label="<?php esc_attr_e('Abrir menu', 'instituto-dr-chao'); ?>"
		>
			<img src="<?php echo esc_url(idc_asset('assets/icons/menu-hamburger.svg')); ?>" alt="" width="18" height="12" decoding="async">
		</button>
	</div>

	<div id="idc-nav-drawer" class="idc-nav-drawer" data-idc-nav-drawer>
		<nav aria-label="<?php esc_attr_e('Menu mobile', 'instituto-dr-chao'); ?>">
			<?php
			wp_nav_menu([
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'idc-nav-drawer__list',
				'fallback_cb'    => 'idc_nav_fallback',
				'depth'          => 2,
			]);
			?>
			<?php
			get_template_part('template-parts/components/button', null, [
				'label'    => __('Agendar Consulta', 'instituto-dr-chao'),
				'variant'  => 'navy',
				'class'    => 'idc-nav-drawer__cta',
				'origem'   => 'menu-mobile',
				'external' => true,
			]);
			?>
		</nav>
	</div>
</header>
