<?php
/**
 * Funções do tema Instituto Dr. Chao
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

define('IDC_THEME_VERSION', wp_get_theme(get_template())->get('Version') ?: '1.0.0');
define('IDC_THEME_DIR', get_template_directory());
define('IDC_THEME_URI', get_template_directory_uri());

/**
 * Setup do tema.
 */
function idc_setup(): void {
	load_theme_textdomain('instituto-dr-chao', IDC_THEME_DIR . '/languages');

	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	]);
	add_theme_support('custom-logo', [
		'height'      => 80,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	]);

	register_nav_menus([
		'primary' => __('Menu principal', 'instituto-dr-chao'),
		'footer'  => __('Menu rodapé', 'instituto-dr-chao'),
	]);
}
add_action('after_setup_theme', 'idc_setup');

/**
 * Assets front-end.
 */
function idc_enqueue_assets(): void {
	wp_enqueue_style(
		'idc-main',
		get_stylesheet_uri(),
		[],
		IDC_THEME_VERSION
	);
}
add_action('wp_enqueue_scripts', 'idc_enqueue_assets');

/**
 * Atualizações do tema via GitHub Releases.
 *
 * Fluxo:
 * 1. Suba a Version em style.css (ex.: 1.0.1)
 * 2. Faça commit + push
 * 3. Crie um Release no GitHub com tag v1.0.1 (mesmo número da Version)
 * 4. O WP detecta e exibe "Atualizar tema"
 *
 * Defina IDC_GITHUB_THEME_REPO no wp-config.php se o slug do repo mudar:
 *   define('IDC_GITHUB_THEME_REPO', 'seu-usuario/instituto-dr-chao');
 */
function idc_register_theme_updater(): void {
	$bundled  = IDC_THEME_DIR . '/inc/plugin-update-checker/plugin-update-checker.php';
	$autoload = IDC_THEME_DIR . '/vendor/autoload.php';

	if (is_readable($bundled)) {
		require_once $bundled;
	} elseif (is_readable($autoload)) {
		require_once $autoload;
	} else {
		return;
	}

	if (!class_exists(\YahnisElsts\PluginUpdateChecker\v5\PucFactory::class)) {
		return;
	}

	$repo = defined('IDC_GITHUB_THEME_REPO')
		? IDC_GITHUB_THEME_REPO
		: 'PLACEHOLDER_USER/instituto-dr-chao';

	$checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
		'https://github.com/' . $repo . '/',
		IDC_THEME_DIR . '/style.css',
		'instituto-dr-chao'
	);

	$checker->setBranch('main');
	$checker->getVcsApi()->enableReleaseAssets();

	// Token opcional para repo privado (Personal Access Token com repo read).
	if (defined('IDC_GITHUB_TOKEN') && IDC_GITHUB_TOKEN) {
		$checker->setAuthentication(IDC_GITHUB_TOKEN);
	}
}
add_action('after_setup_theme', 'idc_register_theme_updater', 20);
