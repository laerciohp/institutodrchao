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

require_once IDC_THEME_DIR . '/inc/helpers.php';
require_once IDC_THEME_DIR . '/inc/defaults-home.php';
require_once IDC_THEME_DIR . '/inc/cpt/profissional.php';
require_once IDC_THEME_DIR . '/inc/cpt/tratamento.php';
require_once IDC_THEME_DIR . '/inc/acf/options.php';
require_once IDC_THEME_DIR . '/inc/acf/home-fields.php';
require_once IDC_THEME_DIR . '/inc/defaults-pages.php';
require_once IDC_THEME_DIR . '/inc/acf/page-fields.php';

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
	add_theme_support('editor-styles');
	add_theme_support('responsive-embeds');

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
		'idc-fonts',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Roboto+Serif:opsz,wght@8..144,800&display=swap',
		[],
		null
	);

	$styles = [
		'idc-tokens' => '/assets/css/tokens.css',
		'idc-base'   => '/assets/css/base.css',
		'idc-header' => '/assets/css/header.css',
		'idc-footer' => '/assets/css/footer.css',
		'idc-hero'   => '/assets/css/hero.css',
		'idc-home'   => '/assets/css/home.css',
		'idc-pages'  => '/assets/css/pages.css',
	];

	$deps = ['idc-fonts'];
	foreach ($styles as $handle => $path) {
		wp_enqueue_style(
			$handle,
			IDC_THEME_URI . $path,
			$deps,
			IDC_THEME_VERSION
		);
		$deps[] = $handle;
	}

	wp_enqueue_style(
		'idc-main',
		get_stylesheet_uri(),
		$deps,
		IDC_THEME_VERSION
	);

	wp_enqueue_script(
		'idc-main',
		IDC_THEME_URI . '/assets/js/main.js',
		[],
		IDC_THEME_VERSION,
		true
	);
}
add_action('wp_enqueue_scripts', 'idc_enqueue_assets');

/**
 * Classes nos links do menu.
 *
 * @param array<int,string> $atts
 * @param WP_Post           $item
 * @param stdClass          $args
 * @return array<int,string>
 */
function idc_nav_link_attributes(array $atts, $item, $args): array {
	if (isset($args->theme_location) && $args->theme_location === 'primary') {
		$atts['class'] = trim(($atts['class'] ?? '') . ' idc-nav__link');
		if (in_array('current-menu-item', $item->classes, true)) {
			$atts['class'] .= ' idc-nav__link--active';
		}
	}
	return $atts;
}
add_filter('nav_menu_link_attributes', 'idc_nav_link_attributes', 10, 3);

/**
 * Chevron em itens com filhos (Especialidades).
 *
 * @param string   $title
 * @param WP_Post  $item
 * @param stdClass $args
 * @param int      $depth
 */
function idc_nav_title_chevron(string $title, $item, $args, int $depth): string {
	if (
		isset($args->theme_location)
		&& $args->theme_location === 'primary'
		&& $depth === 0
		&& in_array('menu-item-has-children', $item->classes, true)
	) {
		$icon = idc_asset('assets/icons/chevron-down.svg');
		$title .= ' <span class="idc-nav__chevron" aria-hidden="true"><img src="' . esc_url($icon) . '" alt="" width="8" height="5"></span>';
	}
	return $title;
}
add_filter('nav_menu_item_title', 'idc_nav_title_chevron', 10, 4);

/**
 * Fallback do menu principal (quando ainda não há menu cadastrado).
 *
 * @param array<string,mixed> $args
 */
function idc_nav_fallback(array $args = []): void {
	$items = [
		['label' => __('Início', 'instituto-dr-chao'), 'url' => home_url('/')],
		['label' => __('Especialidades', 'instituto-dr-chao'), 'url' => home_url('/especialidades/'), 'chevron' => true],
		['label' => __('O Instituto', 'instituto-dr-chao'), 'url' => home_url('/o-instituto/')],
		['label' => __('Blog', 'instituto-dr-chao'), 'url' => get_permalink(get_option('page_for_posts')) ?: home_url('/blog/')],
		['label' => __('Contato', 'instituto-dr-chao'), 'url' => home_url('/contato/')],
	];

	$class = is_string($args['menu_class'] ?? null) ? $args['menu_class'] : 'idc-nav__list';
	$path  = (string) wp_parse_url(home_url(add_query_arg([])), PHP_URL_PATH);

	echo '<ul class="' . esc_attr($class) . '">';
	foreach ($items as $item) {
		$item_path = (string) wp_parse_url($item['url'], PHP_URL_PATH);
		$active    = trailingslashit($path) === trailingslashit($item_path);
		$link_class = 'idc-nav__link' . ($active ? ' idc-nav__link--active' : '');
		echo '<li class="idc-nav__item' . ($active ? ' current-menu-item' : '') . '">';
		echo '<a class="' . esc_attr($link_class) . '" href="' . esc_url($item['url']) . '">';
		echo esc_html($item['label']);
		if (!empty($item['chevron'])) {
			echo ' <span class="idc-nav__chevron" aria-hidden="true"><img src="' . esc_url(idc_asset('assets/icons/chevron-down.svg')) . '" alt="" width="8" height="5"></span>';
		}
		echo '</a></li>';
	}
	echo '</ul>';
}

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
		: 'laerciohp/institutodrchao';

	$checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
		'https://github.com/' . $repo . '/',
		IDC_THEME_DIR . '/style.css',
		'instituto-dr-chao'
	);

	$checker->setBranch('main');
	$checker->getVcsApi()->enableReleaseAssets();

	if (defined('IDC_GITHUB_TOKEN') && IDC_GITHUB_TOKEN) {
		$checker->setAuthentication(IDC_GITHUB_TOKEN);
	}
}
add_action('after_setup_theme', 'idc_register_theme_updater', 20);
