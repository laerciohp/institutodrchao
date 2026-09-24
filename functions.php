<?php
/**
 * Fun├º├Áes do tema Instituto Dr. Chao
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
if (is_readable(IDC_THEME_DIR . '/inc/cpt/depoimento.php')) {
	require_once IDC_THEME_DIR . '/inc/cpt/depoimento.php';
}
require_once IDC_THEME_DIR . '/inc/cpt/form-submissions.php';
require_once IDC_THEME_DIR . '/inc/crm/crm.php';
require_once IDC_THEME_DIR . '/inc/acf/options.php';
require_once IDC_THEME_DIR . '/inc/acf/home-fields.php';
require_once IDC_THEME_DIR . '/inc/defaults-pages.php';
require_once IDC_THEME_DIR . '/inc/seed-data-blog.php';
require_once IDC_THEME_DIR . '/inc/acf/page-fields.php';
require_once IDC_THEME_DIR . '/inc/acf/tratamento-fields.php';
require_once IDC_THEME_DIR . '/inc/forms.php';
require_once IDC_THEME_DIR . '/inc/mail.php';
require_once IDC_THEME_DIR . '/inc/admin-options-fallback.php';
require_once IDC_THEME_DIR . '/inc/setup-pages.php';
require_once IDC_THEME_DIR . '/inc/redirects.php';

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
		'primary'              => __('Menu principal', 'instituto-dr-chao'),
		'footer_tratamentos'   => __('Rodap├® ÔÇö Tratamentos', 'instituto-dr-chao'),
		'footer_institucional' => __('Rodap├® ÔÇö Institucional', 'instituto-dr-chao'),
		'footer_contato'       => __('Rodap├® ÔÇö Contato', 'instituto-dr-chao'),
	]);
}
add_action('after_setup_theme', 'idc_setup');

/**
 * Assets front-end.
 */
function idc_enqueue_assets(): void {
	wp_enqueue_style(
		'idc-fonts',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Roboto+Serif:opsz,wght@8..144,700;8..144,800&display=swap',
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
 * Classes nos <li> do menu principal.
 *
 * @param list<string> $classes
 * @param WP_Post      $item
 * @param stdClass     $args
 * @param int          $depth
 * @return list<string>
 */
function idc_nav_menu_css_class(array $classes, $item, $args, int $depth = 0): array {
	if (isset($args->theme_location) && $args->theme_location === 'primary') {
		$classes[] = 'idc-nav__item';
	}
	return $classes;
}
add_filter('nav_menu_css_class', 'idc_nav_menu_css_class', 10, 4);

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
 * Fallback do menu principal (quando ainda n├úo h├í menu cadastrado).
 *
 * @param array<string,mixed> $args
 */
function idc_nav_fallback(array $args = []): void {
	$items = [
		['label' => __('In├¡cio', 'instituto-dr-chao'), 'url' => home_url('/')],
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
 * Atualiza├º├Áes do tema via GitHub Releases.
 *
 * Fluxo:
 * 1. Suba a Version em style.css (ex.: 1.0.1)
 * 2. Fa├ºa commit + push
 * 3. Crie um Release no GitHub com tag v1.0.1 (mesmo n├║mero da Version)
 * 4. O WP detecta e exibe "Atualizar tema"
 *
 * Defina IDC_GITHUB_THEME_REPO no wp-config.php se o slug do repo mudar:
 *   define('IDC_GITHUB_THEME_REPO', 'seu-usuario/instituto-dr-chao');
 */
function idc_register_theme_updater(): void {
	// Blindagem: outro plugin/tema pode j├í ter carregado a mesma biblioteca
	// (Plugin Update Checker ├® embutida em muitos plugins). Requerer o
	// bundle de novo nesse caso causa "Cannot redeclare class" ÔåÆ tela de
	// erro cr├¡tico. Se a classe j├í existe, reaproveita-a sem requerer nada.
	if (!class_exists('YahnisElsts\\PluginUpdateChecker\\v5\\PucFactory')) {
		$bundled  = IDC_THEME_DIR . '/inc/plugin-update-checker/plugin-update-checker.php';
		$autoload = IDC_THEME_DIR . '/vendor/autoload.php';

		try {
			if (is_readable($bundled)) {
				require_once $bundled;
			} elseif (is_readable($autoload)) {
				require_once $autoload;
			} else {
				return;
			}
		} catch (\Throwable $e) {
			// Nunca deixar o updater (recurso n├úo essencial) derrubar o site.
			return;
		}
	}

	if (!class_exists('YahnisElsts\\PluginUpdateChecker\\v5\\PucFactory')) {
		return;
	}

	try {
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
	} catch (\Throwable $e) {
		// Idem: falha no updater n├úo deve gerar erro cr├¡tico no site.
	}
}
add_action('after_setup_theme', 'idc_register_theme_updater', 20);

/**
 * Exclui posts odontol├│gicos do arquivo do blog (escopo Figma: Orto/Fisio/Integrativa).
 *
 * @param WP_Query $query
 */
function idc_exclude_odonto_from_blog(WP_Query $query): void {
	if (is_admin() || !$query->is_main_query()) {
		return;
	}
	if (!$query->is_home() && !$query->is_category() && !$query->is_tag()) {
		return;
	}

	$slugs = idc_odonto_post_slugs();
	if ($slugs === []) {
		return;
	}

	$exclude_ids = get_posts([
		'post_type'              => 'post',
		'post_status'            => 'publish',
		'posts_per_page'         => 50,
		'post_name__in'          => $slugs,
		'fields'                 => 'ids',
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	]);

	if ($exclude_ids === []) {
		return;
	}

	$not_in = array_map('intval', (array) $query->get('post__not_in'));
	$query->set('post__not_in', array_values(array_unique(array_merge($not_in, $exclude_ids))));

	// Esconde categoria Odontologia dos filtros se existir.
	if ($query->is_home()) {
		$odonto = get_category_by_slug('odontologia');
		if ($odonto instanceof WP_Term) {
			$exclude_cats = array_map('intval', (array) $query->get('category__not_in'));
			$exclude_cats[] = (int) $odonto->term_id;
			$query->set('category__not_in', array_values(array_unique($exclude_cats)));
		}
	}
}
add_action('pre_get_posts', 'idc_exclude_odonto_from_blog');

/**
 * Migra├º├úo leve ao atualizar a Version do tema (flush CPT + sync Home page).
 *
 * Blindado com try/catch: se qualquer upgrade falhar, a vers├úo instalada ├®
 * marcada mesmo assim para n├úo reexecutar (e refalhar) em TODA requisi├º├úo ÔÇö
 * este hook roda em admin_init e init, ou seja, em toda p├ígina, wp-login,
 * REST API e admin. Uma falha aqui sem esse guarda derruba o site inteiro
 * em loop at├® a raiz ser corrigida.
 */
function idc_maybe_run_theme_upgrade(): void {
	$stored = (string) get_option('idc_theme_version_installed', '');
	if ($stored === IDC_THEME_VERSION) {
		return;
	}

	try {
		idc_run_theme_upgrade_steps();
	} catch (\Throwable $e) {
		if (function_exists('error_log')) {
			error_log('[instituto-dr-chao] Falha no upgrade do tema (' . IDC_THEME_VERSION . '): ' . $e->getMessage());
		}
	}

	update_option('idc_theme_version_installed', IDC_THEME_VERSION, false);
}
add_action('admin_init', 'idc_maybe_run_theme_upgrade', 5);
add_action('init', 'idc_maybe_run_theme_upgrade', 20);

/**
 * Passos de migra├º├úo propriamente ditos (extra├¡do para poder ser
 * envolvido em try/catch por idc_maybe_run_theme_upgrade).
 */
function idc_run_theme_upgrade_steps(): void {
	flush_rewrite_rules(false);

	// Sites que j├í passaram por 1.9.x n├úo devem reexecutar seeds for├ºados (183ÔÇô190).
	idc_mark_legacy_upgrades_done_if_needed();

	// Upgrades CMS: cada um roda no m├íximo uma vez (n├úo sobrescreve edi├º├Áes a cada bump).
	idc_run_upgrade_once('183', 'idc_upgrade_183_layout_cms');
	idc_run_upgrade_once('184', 'idc_upgrade_184_layout_cms');
	idc_run_upgrade_once('186', 'idc_upgrade_186_layout_cms');
	idc_run_upgrade_once('187', 'idc_upgrade_187_layout_cms');
	idc_run_upgrade_once('188', 'idc_upgrade_188_layout_cms');
	idc_run_upgrade_once('189', 'idc_upgrade_189_layout_cms');
	idc_run_upgrade_once('190', 'idc_upgrade_190_layout_cms');
	idc_run_upgrade_once('110', 'idc_upgrade_110_layout_cms');
	idc_run_upgrade_once('111', 'idc_upgrade_111_layout_cms');
	idc_run_upgrade_once('112', 'idc_upgrade_112_layout_cms');
	idc_run_upgrade_once('1124', 'idc_upgrade_1124_pillar_image');
	idc_run_upgrade_once('1125', 'idc_upgrade_1125_home_figma_images');
	idc_run_upgrade_once('1127', 'idc_upgrade_1127_pillar_attached');
	idc_run_upgrade_once('11210', 'idc_upgrade_11210_testimonials_figma');
	idc_run_upgrade_once('11211', 'idc_upgrade_11210_testimonials_figma');
	idc_run_upgrade_once('11212', 'idc_upgrade_11212_contato_figma');
	idc_run_upgrade_once('11213', 'idc_upgrade_11213_testimonials_cpt');
	idc_run_upgrade_once('11214', 'idc_upgrade_11214_testimonials_meta');
	idc_run_upgrade_once('11216', 'idc_upgrade_11216_testimonials_prod');
	idc_run_upgrade_once('11219', 'idc_upgrade_11219_diferenciais_figma');
	idc_run_upgrade_once('11220', 'idc_upgrade_11220_parity_figma');
	idc_run_upgrade_once('11220b', 'idc_upgrade_11220_parity_figma');
	idc_run_upgrade_once('11221', 'idc_upgrade_11221_specialty_titles_figma');
	idc_run_upgrade_once('11227', 'idc_upgrade_11227_blog_instituto_100');
	idc_run_upgrade_once('11228', 'idc_upgrade_11228_parity_100');

	// Copia Options da Home para a p├ígina In├¡cio (se vazia), para ÔÇ£Editar p├íginaÔÇØ funcionar.
	$front_id = (int) get_option('page_on_front');
	if ($front_id > 0 && function_exists('get_field') && function_exists('update_field')) {
		$keys = [
			'idc_hero_eyebrow',
			'idc_hero_since',
			'idc_hero_title_before',
			'idc_hero_title_accent',
			'idc_hero_title_after',
			'idc_hero_lead',
			'idc_hero_cta_primary',
			'idc_hero_cta_secondary',
			'idc_hero_secondary_url',
			'idc_hero_image',
			'idc_trust_items',
			'idc_pillars_eyebrow',
			'idc_pillars_title_before',
			'idc_pillars_title_accent',
			'idc_pillars_title_after',
			'idc_pillars_lead',
			'idc_pillars_cards',
			'idc_why_eyebrow',
			'idc_why_title',
			'idc_why_lead',
			'idc_why_items',
			'idc_why_image',
			'idc_testimonials_badge',
			'idc_testimonials_title',
			'idc_testimonials',
			'idc_team_title',
			'idc_team_lead',
			'idc_blog_title',
			'idc_blog_lead',
			'idc_cta_title_before',
			'idc_cta_title_accent',
			'idc_cta_lead',
			'idc_cta_primary_label',
			'idc_cta_secondary_label',
			'idc_cta_secondary_url',
		];
		foreach ($keys as $key) {
			$on_page = get_field($key, $front_id);
			if ($on_page !== null && $on_page !== false && $on_page !== '' && $on_page !== []) {
				continue;
			}
			$from_opt = get_field($key, 'option');
			if ($from_opt !== null && $from_opt !== false && $from_opt !== '' && $from_opt !== []) {
				update_field($key, $from_opt, $front_id);
			}
		}
	}
}

/**
 * v1.8.3 ÔÇö republica Carreiras se estiver fora do ar e alinha t├¡tulos ACF ao Figma.
 */
function idc_upgrade_183_layout_cms(): void {
	$pages = get_posts([
		'name'           => 'carreiras',
		'post_type'      => 'page',
		'post_status'    => ['publish', 'draft', 'pending', 'private', 'trash'],
		'posts_per_page' => 1,
		'no_found_rows'  => true,
	]);
	if ($pages !== []) {
		$page = $pages[0];
		if ($page->post_status !== 'publish') {
			wp_update_post([
				'ID'          => (int) $page->ID,
				'post_status' => 'publish',
			]);
		}
		update_post_meta((int) $page->ID, '_wp_page_template', 'page-carreiras.php');
		if (function_exists('update_field')) {
			update_field('idc_carreiras_form_lead', '', (int) $page->ID);
			update_field('idc_carreiras_form_title', 'Envie uma mensagem', (int) $page->ID);
		}
	}

	if (!function_exists('update_field')) {
		return;
	}

	$figma_titles = [
		'ortopedia-regenerativa' => [
			'idc_page_eyebrow'      => 'TRATAMENTOS ┬À ESPECIALIDADE PRINCIPAL',
			'idc_page_title_before' => 'Ortopedia Regenerativa',
			'idc_page_title_accent' => '',
			'idc_page_title_after'  => '',
		],
		'fisioterapia' => [
			'idc_page_eyebrow'      => '',
			'idc_page_title_before' => 'Fisioterapia Especializada em Dor',
			'idc_page_title_accent' => '',
			'idc_page_title_after'  => '',
		],
		'medicina-integrativa' => [
			'idc_page_eyebrow'      => '',
			'idc_page_title_before' => 'Medicina ',
			'idc_page_title_accent' => 'Integrativa',
			'idc_page_title_after'  => ' e Regenerativa.',
		],
		'blog' => [
			'idc_page_eyebrow'      => '',
			'idc_page_title_before' => '',
			'idc_page_title_accent' => 'Conhecimento',
			'idc_page_title_after'  => ' para o seu cuidado.',
			'idc_page_lead'         => 'Artigos, dicas e novidades sobre ortopedia, fisioterapia, medicina integrativa e bem-estar. Escritos por nossa equipe de especialistas para ajudar voc├¬ a viver com mais movimento e menos dor.',
		],
	];

	foreach ($figma_titles as $slug => $fields) {
		$page = get_page_by_path($slug);
		if (!$page instanceof WP_Post) {
			continue;
		}
		foreach ($fields as $key => $value) {
			update_field($key, $value, (int) $page->ID);
		}
	}
}

/**
 * v1.8.4 ÔÇö alinha Carreiras e archive de Tratamentos aos defaults do Figma.
 */
function idc_upgrade_184_layout_cms(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$carreiras = get_page_by_path('carreiras');
	if ($carreiras instanceof WP_Post) {
		update_field('idc_page_title_before', 'Fa├ºa parte do ', (int) $carreiras->ID);
		update_field('idc_page_title_accent', 'time', (int) $carreiras->ID);
		update_field('idc_page_title_after', '', (int) $carreiras->ID);
		update_field(
			'idc_page_lead',
			"No Instituto Dr. Chao, acreditamos que oferecer um atendimento excepcional come├ºa por ter uma equipe movida por empatia, dedica├º├úo e excel├¬ncia. Se voc├¬ compartilha do nosso compromisso de acolher, cuidar e transformar a jornada de diagn├│stico e reabilita├º├úo das pessoas, n├│s queremos conhecer voc├¬. Venha construir uma carreira com prop├│sito.\n\nPreencha o formul├írio abaixo, anexe seu curr├¡culo e d├¬ o primeiro passo para fazer a diferen├ºa em cada etapa do nosso trabalho.",
			(int) $carreiras->ID
		);
		update_field('idc_carreiras_form_title', 'Envie uma mensagem', (int) $carreiras->ID);
		update_field('idc_carreiras_form_lead', '', (int) $carreiras->ID);
	}
}

/**
 * v1.8.6 ÔÇö sync Hub cards + strips Especialidades/Orto/Fisio/Integrativa ao Figma.
 */
function idc_upgrade_186_layout_cms(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$hub = get_page_by_path('especialidades');
	if ($hub instanceof WP_Post) {
		update_field('idc_hub_cards', idc_default_hub_cards(), (int) $hub->ID);
		foreach (idc_default_strip_cta_for_slug('especialidades') as $key => $value) {
			update_field($key, $value, (int) $hub->ID);
		}
	}

	foreach (['ortopedia-regenerativa', 'fisioterapia', 'medicina-integrativa'] as $slug) {
		$page = get_page_by_path($slug);
		if (!$page instanceof WP_Post) {
			continue;
		}
		foreach (idc_default_strip_cta_for_slug($slug) as $key => $value) {
			update_field($key, $value, (int) $page->ID);
		}
	}

	$orto = get_page_by_path('ortopedia-regenerativa');
	if ($orto instanceof WP_Post) {
		update_field('idc_strip_secondary_label', 'Ver Fisioterapia Especializada', (int) $orto->ID);
		update_field('idc_strip_secondary_url', home_url('/fisioterapia/'), (int) $orto->ID);
	}

	$integrativa = get_page_by_path('medicina-integrativa');
	if ($integrativa instanceof WP_Post) {
		update_field('idc_strip_secondary_label', 'Voltar para Ortopedia Regenerativa', (int) $integrativa->ID);
		update_field('idc_strip_secondary_url', home_url('/ortopedia-regenerativa/'), (int) $integrativa->ID);
	}
}

/**
 * v1.8.7 ÔÇö sync lead/FAQ Orto, limpa footer Instala├º├Áes (Figma).
 */
function idc_upgrade_187_layout_cms(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$orto = get_page_by_path('ortopedia-regenerativa');
	if ($orto instanceof WP_Post) {
		$hero = idc_default_page_hero('ortopedia-regenerativa');
		if ($hero !== null) {
			foreach ($hero as $key => $value) {
				update_field($key, $value, (int) $orto->ID);
			}
		}

		$treatments = idc_default_ortopedia_treatments();
		$rows       = [];
		foreach ($treatments as $tx) {
			$faqs = [];
			foreach ($tx['faqs'] as $faq) {
				$faqs[] = [
					'question' => $faq['question'],
					'answer'   => $faq['answer'],
				];
			}
			$rows[] = [
				'title' => $tx['title'],
				'intro' => $tx['intro'] ?? '',
				'faqs'  => $faqs,
			];
		}
		update_field('idc_orto_treatments', $rows, (int) $orto->ID);
		update_field('idc_orto_treatments_title', 'Tratamentos regenerativos', (int) $orto->ID);
	}

	$carreiras = get_page_by_path('carreiras');
	if ($carreiras instanceof WP_Post) {
		update_field('idc_page_title_before', 'Fa├ºa parte do ', (int) $carreiras->ID);
		update_field('idc_page_title_accent', 'time', (int) $carreiras->ID);
		update_field('idc_page_title_after', '', (int) $carreiras->ID);
	}

	if (function_exists('idc_setup_remove_footer_instalacoes_link')) {
		idc_setup_remove_footer_instalacoes_link();
	}
}

/**
 * v1.8.8 ÔÇö alinha Home (hero Figma) + Hub title split + limpa leads fora do frame.
 */
function idc_upgrade_188_layout_cms(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$hero = [
		'idc_hero_title_before' => 'A dor n├úo precisa definir a',
		'idc_hero_title_accent' => 'sua vida.',
		'idc_hero_title_after'  => '',
		'idc_hero_lead'         => "Existimos para que ningu├®m seja definido pela sua dor.\nCombinamos vanguarda m├®dica e terapias integrativas em um ambiente pensado para a sua verdadeira recupera├º├úo e bem-estar cont├¡nuo.",
		'idc_hero_cta_primary'  => 'Agendar Consulta',
		'idc_hero_cta_secondary'=> 'Conhe├ºa os tratamentos',
	];

	foreach ($hero as $key => $value) {
		update_field($key, $value, 'option');
	}

	$front_id = (int) get_option('page_on_front');
	if ($front_id > 0) {
		foreach ($hero as $key => $value) {
			update_field($key, $value, $front_id);
		}
	}

	$hub = get_page_by_path('especialidades');
	if ($hub instanceof WP_Post) {
		$hub_fields = idc_default_page_hero('especialidades');
		if (is_array($hub_fields)) {
			foreach ($hub_fields as $key => $value) {
				update_field($key, $value, (int) $hub->ID);
			}
		}
		update_field('idc_hub_cards', idc_default_hub_cards(), (int) $hub->ID);
	}

	// Prefer fill do hub (JPG). PNG legado diverge do Figma e foi movido para _aside.
	$fisio = get_page_by_path('fisioterapia');
	if ($fisio instanceof WP_Post) {
		update_field('idc_page_hero_image', null, (int) $fisio->ID);
		$hero_fisio = idc_default_page_hero('fisioterapia');
		if (is_array($hero_fisio)) {
			foreach ($hero_fisio as $key => $value) {
				update_field($key, $value, (int) $fisio->ID);
			}
		}
	}

	$orto = get_page_by_path('ortopedia-regenerativa');
	if ($orto instanceof WP_Post) {
		update_field('idc_page_hero_image', null, (int) $orto->ID);
	}

	$integrativa = get_page_by_path('medicina-integrativa');
	if ($integrativa instanceof WP_Post) {
		update_field('idc_page_hero_image', null, (int) $integrativa->ID);
	}
}

/**
 * v1.8.9 ÔÇö badge depoimentos Figma + limpa override de imagem "Por que escolher".
 */
function idc_upgrade_189_layout_cms(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$badge = '4.9 ┬À 742 avalia├º├Áes';
	$title = 'O que dizem nossos pacientes';

	update_field('idc_testimonials_badge', $badge, 'option');
	update_field('idc_testimonials_title', $title, 'option');

	$front_id = (int) get_option('page_on_front');
	if ($front_id > 0) {
		update_field('idc_testimonials_badge', $badge, $front_id);
		update_field('idc_testimonials_title', $title, $front_id);
		// Preferir fallback do tema (PNG Figma) em vez de attachment antigo no ACF.
		update_field('idc_why_image', null, $front_id);
	}
	update_field('idc_why_image', null, 'option');

	// Garante defaults dos diferenciais (textos Figma).
	$why_items = idc_default_why_items();
	update_field('idc_why_items', $why_items, 'option');
	if ($front_id > 0) {
		update_field('idc_why_items', $why_items, $front_id);
	}
}

/**
 * v1.9.0 ÔÇö Home Figma SRIgo (hero/pilares/why/hub) + Instituto ess├¬ncia.
 */
function idc_upgrade_190_layout_cms(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$hero = [
		'idc_hero_title_before'  => 'Tratamos a',
		'idc_hero_title_accent'  => 'origem da dor.',
		'idc_hero_title_after'   => ' Voc├¬ sente a mudan├ºa.',
		'idc_hero_lead'          => "Existimos para que ningu├®m seja definido pela sua dor.\nCombinamos vanguarda m├®dica e terapias integrativas em um ambiente pensado para a sua verdadeira recupera├º├úo e bem-estar cont├¡nuo.",
		'idc_hero_cta_primary'   => 'Agendar Consulta',
		'idc_hero_cta_secondary' => 'Conhe├ºa os tratamentos',
	];

	foreach ($hero as $key => $value) {
		update_field($key, $value, 'option');
	}

	$front_id = (int) get_option('page_on_front');
	if ($front_id > 0) {
		foreach ($hero as $key => $value) {
			update_field($key, $value, $front_id);
		}
		update_field('idc_hero_image', null, $front_id);
		update_field('idc_why_image', null, $front_id);
		update_field('idc_pillars_cards', idc_default_pillars_cards(), $front_id);
		update_field('idc_why_items', idc_default_why_items(), $front_id);
	}

	update_field('idc_hero_image', null, 'option');
	update_field('idc_why_image', null, 'option');
	update_field('idc_pillars_cards', idc_default_pillars_cards(), 'option');
	update_field('idc_why_items', idc_default_why_items(), 'option');
	update_field('idc_footer_copy', 'Instituto Dr. Chao. Todos os direitos reservados.', 'option');

	$hub = get_page_by_path('especialidades');
	if ($hub instanceof WP_Post) {
		update_field('idc_hub_cards', idc_default_hub_cards(), (int) $hub->ID);
	}

	$orto = get_page_by_path('ortopedia-regenerativa');
	if ($orto instanceof WP_Post) {
		update_field('idc_orto_treatments', idc_default_ortopedia_treatments(), (int) $orto->ID);
	}

	$inst = get_page_by_path('o-instituto');
	if ($inst instanceof WP_Post) {
		update_field('idc_instituto_essencia_eyebrow', 'NOSSA ESS├èNCIA', (int) $inst->ID);
		update_field('idc_instituto_essencia_title', 'Cuidado que une experi├¬ncia, ci├¬ncia e acolhimento', (int) $inst->ID);
		update_field(
			'idc_instituto_essencia_lead',
			'Desde 1987, o Instituto Dr. Chao une precis├úo ortop├®dica, reabilita├º├úo e medicina integrativa para tratar a origem da dor ÔÇö com ci├¬ncia, escuta e acolhimento.',
			(int) $inst->ID
		);
		update_field('idc_instituto_cards', idc_default_instituto_cards(), (int) $inst->ID);
		update_field('idc_instituto_image', null, (int) $inst->ID);
	}
}

/**
 * Marca upgrades 183ÔÇô190 como conclu├¡dos se o site j├í estava em ÔëÑ1.9.0
 * (evita overwrite de conte├║do editorial no primeiro deploy com gates).
 */
function idc_mark_legacy_upgrades_done_if_needed(): void {
	$stored = (string) get_option('idc_theme_version_installed', '');
	if ($stored === '' || version_compare($stored, '1.9.0', '<')) {
		return;
	}
	foreach (['183', '184', '186', '187', '188', '189', '190'] as $flag) {
		$opt = 'idc_upgrade_' . $flag . '_done';
		if (!get_option($opt)) {
			update_option($opt, 1, false);
		}
	}
}

/**
 * v1.10.0 ÔÇö seeds seguros (s├│ se vazio) para archive de tratamentos.
 */
function idc_upgrade_110_layout_cms(): void {
	if (!function_exists('update_field') || !function_exists('get_field')) {
		return;
	}
	$pairs = [
		'idc_tx_archive_eyebrow' => 'TRATAMENTOS',
		'idc_tx_archive_title'   => 'Conhe├ºa nossos tratamentos',
		'idc_tx_archive_lead'    => 'Protocolos regenerativos e de reabilita├º├úo orientados pela equipe do Instituto Dr. Chao ÔÇö da avalia├º├úo ao acompanhamento cont├¡nuo.',
	];
	foreach ($pairs as $key => $default) {
		$current = get_field($key, 'option');
		if ($current === null || $current === false || $current === '') {
			update_field($key, $default, 'option');
		}
	}
}

/**
 * v1.11.0 ÔÇö migra especialidades para template flex├¡vel + se├º├Áes orden├íveis da Home.
 */
function idc_upgrade_111_layout_cms(): void {
	if (!function_exists('update_field') || !function_exists('get_field')) {
		return;
	}

	$map = [
		'ortopedia-regenerativa' => 'page-especialidade.php',
		'fisioterapia'           => 'page-especialidade.php',
		'medicina-integrativa'   => 'page-especialidade.php',
	];
	foreach ($map as $slug => $template) {
		$page = get_page_by_path($slug);
		if (!$page instanceof WP_Post) {
			continue;
		}
		$pid = (int) $page->ID;
		update_post_meta($pid, '_wp_page_template', $template);

		$layout = get_field('idc_specialty_blocks', $pid);
		if ($layout === null || $layout === false || $layout === '' || $layout === []) {
			$seed = idc_default_specialty_layout_for_slug($slug);
			if ($slug === 'ortopedia-regenerativa') {
				$existing = get_field('idc_orto_treatments', $pid);
				$title    = (string) (get_field('idc_orto_treatments_title', $pid) ?: 'Tratamentos regenerativos');
				if (is_array($existing) && $existing !== []) {
					$seed = [['acf_fc_layout' => 'treatment_faq', 'section_title' => $title, 'cards' => $existing]];
				}
			} elseif ($slug === 'fisioterapia') {
				$existing = get_field('idc_fisio_phases', $pid);
				$title    = (string) (get_field('idc_fisio_phases_title', $pid) ?: 'As 4 fases da recupera├º├úo');
				if (is_array($existing) && $existing !== []) {
					$seed = [['acf_fc_layout' => 'phases', 'section_title' => $title, 'phases' => $existing]];
				}
			} elseif ($slug === 'medicina-integrativa') {
				$existing = get_field('idc_integrativa_grid', $pid);
				$title    = (string) (get_field('idc_integrativa_grid_title', $pid) ?: 'Tratamentos Integrativos');
				if (is_array($existing) && $existing !== []) {
					$seed = [['acf_fc_layout' => 'bento', 'section_title' => $title, 'items' => $existing]];
				}
			}
			update_field('idc_specialty_blocks', $seed, $pid);
		}
	}

	$front_id = (int) get_option('page_on_front');
	if ($front_id > 0) {
		$sections = get_field('idc_home_sections', $front_id);
		if ($sections === null || $sections === false || $sections === []) {
			update_field('idc_home_sections', idc_default_home_sections(), $front_id);
		}
		$from_opt = get_field('idc_home_sections', 'option');
		if (($from_opt === null || $from_opt === false || $from_opt === []) && function_exists('update_field')) {
			update_field('idc_home_sections', idc_default_home_sections(), 'option');
		}
	}
}

/**
 * v1.12.0+ ÔÇö nota do design system no painel (op├º├úo informativa).
 */
function idc_upgrade_112_layout_cms(): void {
	if (!function_exists('update_field') || !function_exists('get_field')) {
		return;
	}
	$note = get_field('idc_design_guide_note', 'option');
	if ($note === null || $note === false || $note === '') {
		update_field(
			'idc_design_guide_note',
			"Edite Home e p├íginas pelos campos ACF do template (n├úo invente layouts fora dos blocos).\n" .
			"Especialidades: use o template ┬½Especialidade┬╗ com blocos flex├¡veis.\n" .
			"Tokens e componentes: docs/design-tokens.md e template-parts/components/.",
			'option'
		);
	}
}

/**
 * v1.12.4 ÔÇö legado (j├í rodou no staging); mantido como no-op.
 */
function idc_upgrade_1124_pillar_image(): void {
	// Intencionalmente vazio: a corre├º├úo definitiva est├í em 1125.
}

/**
 * v1.12.5 ÔÇö pillar Home = fill Figma 133:407 (exame joelho), n├úo hub anat├┤mico.
 */
function idc_upgrade_1125_home_figma_images(): void {
	if (!function_exists('update_field') || !function_exists('get_field')) {
		return;
	}

	$canonical = idc_theme_image('assets/images/pillar-ortopedia', 'png');
	$rewrite   = static function (array $cards) use ($canonical): array {
		foreach ($cards as &$card) {
			if (!is_array($card)) {
				continue;
			}
			$img = $card['image'] ?? null;
			$url = '';
			if (is_array($img) && !empty($img['url'])) {
				$url = (string) $img['url'];
			} elseif (is_string($img)) {
				$url = $img;
			}
			if ($url === '' || str_contains($url, 'hub-ortopedia') || str_contains($url, 'pillar-ortopedia')) {
				$card['image'] = $canonical;
			}
		}
		unset($card);
		return $cards;
	};

	$targets = ['option'];
	$front_id = (int) get_option('page_on_front');
	if ($front_id > 0) {
		$targets[] = $front_id;
	}

	foreach ($targets as $target) {
		$cards = get_field('idc_pillars_cards', $target);
		if (!is_array($cards) || $cards === []) {
			update_field('idc_pillars_cards', idc_default_pillars_cards(), $target);
			continue;
		}
		update_field('idc_pillars_cards', $rewrite($cards), $target);
	}

	// Hero / why: limpa overrides para voltar ao fallback do tema (fills Figma).
	foreach ($targets as $target) {
		update_field('idc_hero_image', null, $target);
		update_field('idc_why_image', null, $target);
	}

	$hero = [
		'idc_hero_title_before'    => 'A dor n├úo precisa definir a',
		'idc_hero_title_accent'    => 'sua vida.',
		'idc_hero_title_after'     => '',
		'idc_pillars_title_before' => 'Tr├¬s pilares de',
		'idc_pillars_title_accent' => 'cuidado',
		'idc_pillars_title_after'  => '',
	];
	foreach ($targets as $target) {
		foreach ($hero as $key => $value) {
			update_field($key, $value, $target);
		}
	}
}

/**
 * v1.12.7 ÔÇö pillar Ortopedia = foto anexada (exame do joelho).
 */
function idc_upgrade_1127_pillar_attached(): void {
	if (!function_exists('update_field') || !function_exists('get_field')) {
		return;
	}

	$canonical = idc_theme_image('assets/images/pillar-ortopedia', 'png');
	$targets   = ['option'];
	$front_id  = (int) get_option('page_on_front');
	if ($front_id > 0) {
		$targets[] = $front_id;
	}

	foreach ($targets as $target) {
		$cards = get_field('idc_pillars_cards', $target);
		if (!is_array($cards) || $cards === []) {
			update_field('idc_pillars_cards', idc_default_pillars_cards(), $target);
			continue;
		}
		foreach ($cards as &$card) {
			if (!is_array($card)) {
				continue;
			}
			$layout = (string) ($card['layout'] ?? '');
			if ($layout === 'primary' || $layout === '') {
				$card['image'] = $canonical;
			}
		}
		unset($card);
		update_field('idc_pillars_cards', $cards, $target);
	}
}

/**
 * v1.12.10/1.12.11 ÔÇö Depoimentos alinhados ao Figma 133:493 (badge + 3 cards).
 */
function idc_upgrade_11210_testimonials_figma(): void {
	if (function_exists('opcache_reset')) {
		@opcache_reset();
	}
	if (!function_exists('update_field')) {
		return;
	}

	$badge = '5.0 Avalia├º├úo M├®dia';
	$items = idc_default_testimonials();
	$targets = ['option'];
	$front_id = (int) get_option('page_on_front');
	if ($front_id > 0) {
		$targets[] = $front_id;
	}

	foreach ($targets as $target) {
		update_field('idc_testimonials_badge', $badge, $target);
		update_field('idc_testimonials_title', 'O que dizem nossos pacientes', $target);
		update_field('idc_testimonials', $items, $target);
	}
}

/**
 * v1.12.12 ÔÇö Contato alinhado ao Figma 133:2904 (labels dos bot├Áes).
 */
function idc_upgrade_11212_contato_figma(): void {
	if (function_exists('opcache_reset')) {
		@opcache_reset();
	}
	if (!function_exists('update_field')) {
		return;
	}

	$page = get_page_by_path('contato');
	if (!$page instanceof WP_Post) {
		$q = new WP_Query([
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'page-contato.php',
			'fields'         => 'ids',
			'no_found_rows'  => true,
		]);
		$cid = !empty($q->posts[0]) ? (int) $q->posts[0] : 0;
	} else {
		$cid = (int) $page->ID;
	}

	if ($cid <= 0) {
		return;
	}

	update_field('idc_contato_whatsapp_label', 'Iniciar conversa no WhatsApp', $cid);
	update_field('idc_contato_form_lead', '', $cid);
	update_field('idc_contato_label_ligar', 'Ligar para cl├¡nica', $cid);
	update_field('idc_contato_label_email_btn', 'Enviar e-mail', $cid);
	update_field('idc_contato_label_maps', 'Como chegar no Google Maps', $cid);
	update_field('idc_contato_label_horario', 'Hor├írio de Atendimento', $cid);
}

/**
 * v1.12.19 ÔÇö Auditoria pixel-perfect Figma SRIgo (frame 435:2, camada ativa):
 * corrige t├¡tulos/textos de "Por que escolher" (Diferenciais) que estavam
 * com copy antiga ("Corpo Cl├¡nico Renomado" etc.) divergente do Figma atual.
 */
function idc_upgrade_11219_diferenciais_figma(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$why_items = idc_default_why_items();
	$targets   = ['option'];
	$front_id  = (int) get_option('page_on_front');
	if ($front_id > 0) {
		$targets[] = $front_id;
	}

	foreach ($targets as $target) {
		update_field('idc_why_items', $why_items, $target);
	}
}

/**
 * v1.12.20 ÔÇö Paridade Figma: t├¡tulo dos Pilares + redirect Trabalhe Conosco.
 * Corrige "Tr├¬s pilares de cuidado completa" (after residual no ACF).
 */
function idc_upgrade_11220_parity_figma(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$targets  = ['option'];
	$front_id = (int) get_option('page_on_front');
	if ($front_id > 0) {
		$targets[] = $front_id;
	}

	$fields = [
		'idc_pillars_title_before' => 'Tr├¬s pilares de',
		'idc_pillars_title_accent' => 'cuidado',
	];

	foreach ($targets as $target) {
		foreach ($fields as $key => $value) {
			update_field($key, $value, $target);
		}
		// ACF: string vazia nem sempre limpa meta ÔÇö delete garante.
		if (function_exists('delete_field')) {
			delete_field('idc_pillars_title_after', $target);
		} else {
			update_field('idc_pillars_title_after', '', $target);
		}
	}
}

/**
 * Redirect can├┤nico: /trabalhe-conosco/ ÔåÆ /carreiras/ (slug da p├ígina Figma).
 */
function idc_redirect_trabalhe_conosco(): void {
	if (is_admin() || wp_doing_ajax() || wp_doing_cron()) {
		return;
	}

	$path = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
	if ($path === 'trabalhe-conosco') {
		wp_safe_redirect(home_url('/carreiras/'), 301);
		exit;
	}
}
add_action('template_redirect', 'idc_redirect_trabalhe_conosco', 1);

/**
 * v1.12.21 ÔÇö T├¡tulos Orto/Fisio em Montserrat (Figma), sem serif no H1 inteiro.
 * Serif fica s├│ em spans de destaque (ex.: "Integrativa", "seu cuidado?").
 */
function idc_upgrade_11221_specialty_titles_figma(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$pages = [
		'ortopedia-regenerativa' => [
			'idc_page_title_before' => 'Ortopedia Regenerativa',
			'idc_page_title_accent' => '',
			'idc_page_title_after'  => '',
		],
		'fisioterapia' => [
			'idc_page_title_before' => 'Fisioterapia Especializada em Dor',
			'idc_page_title_accent' => '',
			'idc_page_title_after'  => '',
		],
	];

	foreach ($pages as $slug => $fields) {
		$page = get_page_by_path($slug);
		if (!$page instanceof WP_Post) {
			continue;
		}
		$id = (int) $page->ID;
		update_field('idc_page_title_before', $fields['idc_page_title_before'], $id);
		if (function_exists('delete_field')) {
			delete_field('idc_page_title_accent', $id);
			delete_field('idc_page_title_after', $id);
		} else {
			update_field('idc_page_title_accent', '', $id);
			update_field('idc_page_title_after', '', $id);
		}
	}
}

/**
 * v1.12.27 ÔÇö Blog 100% Figma (5 filtros + t├¡tulo) + Instituto sem strip.
 * Autocontido (n├úo depende de seed/setup j├í atualizados no servidor).
 */
function idc_upgrade_11227_blog_instituto_100(): void {
	$cats = [
		[
			'slug'        => 'ortopedia-regenerativa',
			'name'        => 'Ortopedia',
			'description' => 'Artigos sobre ortopedia regenerativa, articula├º├Áes e recupera├º├úo musculoesquel├®tica.',
		],
		[
			'slug'        => 'dor-e-movimento',
			'name'        => 'Dor e Movimento',
			'description' => 'Dor cr├┤nica, postura, preven├º├úo e qualidade de movimento no dia a dia.',
		],
		[
			'slug'        => 'fisioterapia',
			'name'        => 'Fisioterapia',
			'description' => 'Reabilita├º├úo, dor, postura e preven├º├úo.',
		],
		[
			'slug'        => 'medicina-integrativa',
			'name'        => 'Medicina Integrativa',
			'description' => 'Abordagens integrativas, regenerativas e de bem-estar sist├¬mico.',
		],
	];

	foreach ($cats as $cat) {
		$existing = get_category_by_slug($cat['slug']);
		if ($existing instanceof WP_Term) {
			if ($existing->name !== $cat['name'] || $existing->description !== $cat['description']) {
				wp_update_term((int) $existing->term_id, 'category', [
					'name'        => $cat['name'],
					'description' => $cat['description'],
				]);
			}
			continue;
		}
		wp_insert_term($cat['name'], 'category', [
			'slug'        => $cat['slug'],
			'description' => $cat['description'],
		]);
	}

	$map = [
		'o-que-e-a-ortopedia-regenerativa-e-como-ela-transforma-vidas' => ['ortopedia-regenerativa'],
		'como-o-uso-do-celular-pode-piorar-a-sua-dor'                 => ['dor-e-movimento', 'fisioterapia'],
		'8-dicas-para-aliviar-as-dores-musculares-apos-o-treino-na-academia' => ['dor-e-movimento', 'fisioterapia'],
		'por-que-os-pes-sao-tao-importantes-para-a-movimentacao'      => ['dor-e-movimento', 'ortopedia-regenerativa'],
		'medicina-integrativa-o-cuidado-que-enxerga-voce-por-inteiro' => ['medicina-integrativa'],
	];
	foreach ($map as $slug => $cat_slugs) {
		$post = get_page_by_path($slug, OBJECT, 'post');
		if (!$post instanceof WP_Post) {
			continue;
		}
		wp_set_object_terms((int) $post->ID, $cat_slugs, 'category', false);
	}

	if (!function_exists('update_field')) {
		return;
	}

	$posts_page_id = (int) get_option('page_for_posts');
	if ($posts_page_id > 0) {
		update_field('idc_page_title_before', 'Conhecimento para o ', $posts_page_id);
		update_field('idc_page_title_accent', 'seu cuidado.', $posts_page_id);
		if (function_exists('delete_field')) {
			delete_field('idc_page_title_after', $posts_page_id);
			delete_field('idc_page_eyebrow', $posts_page_id);
		} else {
			update_field('idc_page_title_after', '', $posts_page_id);
			update_field('idc_page_eyebrow', '', $posts_page_id);
		}
	}
}

/**
 * v1.12.28 — Paridade Figma 100%: Orto H1 split, Fisio lead/eyebrow, Instituto accent.
 */
function idc_upgrade_11228_parity_100(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$orto = get_page_by_path('ortopedia-regenerativa');
	if ($orto instanceof WP_Post) {
		$id = (int) $orto->ID;
		if (function_exists('delete_field')) {
			delete_field('idc_page_title_before', $id);
		} else {
			update_field('idc_page_title_before', '', $id);
		}
		update_field('idc_page_title_accent', 'Ortopedia', $id);
		update_field('idc_page_title_after', ' Regenerativa', $id);
	}

	$fisio = get_page_by_path('fisioterapia');
	if ($fisio instanceof WP_Post) {
		$id = (int) $fisio->ID;
		if (function_exists('delete_field')) {
			delete_field('idc_page_eyebrow', $id);
			delete_field('idc_page_title_accent', $id);
			delete_field('idc_page_title_after', $id);
		} else {
			update_field('idc_page_eyebrow', '', $id);
			update_field('idc_page_title_accent', '', $id);
			update_field('idc_page_title_after', '', $id);
		}
		update_field('idc_page_title_before', 'Fisioterapia Especializada em Dor', $id);
		update_field(
			'idc_page_lead',
			'Nossa abordagem integra técnicas avançadas com um cuidado humanizado profundo. Através de uma jornada de 5 passos estruturada e 4 fases de recuperação distintas, desenhamos um caminho focado não apenas em tratar os sintomas, mas em restaurar a verdadeira função e o bem-estar do seu corpo.',
			$id
		);
	}

	$instituto = get_page_by_path('o-instituto');
	if ($instituto instanceof WP_Post) {
		$id = (int) $instituto->ID;
		if (function_exists('delete_field')) {
			delete_field('idc_page_eyebrow', $id);
			delete_field('idc_page_title_after', $id);
		} else {
			update_field('idc_page_eyebrow', '', $id);
			update_field('idc_page_title_after', '', $id);
		}
		update_field('idc_page_title_before', 'Existimos para que ninguém seja definido ', $id);
		update_field('idc_page_title_accent', 'pela sua dor.', $id);
	}
}

/**
 * Newsletter do Blog (admin-post) — MVP sem Mailchimp.
 */
function idc_handle_blog_newsletter(): void {
	$redirect = wp_get_referer() ?: home_url('/blog/');
	$redirect = remove_query_arg('idc_nl', $redirect);

	if (!isset($_POST['idc_nl_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash((string) $_POST['idc_nl_nonce'])), 'idc_blog_newsletter')) {
		wp_safe_redirect(add_query_arg('idc_nl', 'err', $redirect));
		exit;
	}

	$email = isset($_POST['email']) ? sanitize_email(wp_unslash((string) $_POST['email'])) : '';
	if ($email === '' || !is_email($email)) {
		wp_safe_redirect(add_query_arg('idc_nl', 'invalid', $redirect));
		exit;
	}

	$list = get_option('idc_newsletter_emails', []);
	if (!is_array($list)) {
		$list = [];
	}
	$list[] = [
		'email' => $email,
		'at'    => current_time('mysql'),
	];
	$list = array_slice($list, -500);
	update_option('idc_newsletter_emails', $list, false);

	$admin = (string) get_option('admin_email');
	if ($admin !== '') {
		wp_mail(
			$admin,
			'[Instituto Dr. Chao] Nova inscrição newsletter',
			sprintf("E-mail: %s\nData: %s\n", $email, current_time('mysql'))
		);
	}

	wp_safe_redirect(add_query_arg('idc_nl', 'ok', $redirect));
	exit;
}
add_action('admin_post_nopriv_idc_blog_newsletter', 'idc_handle_blog_newsletter');
add_action('admin_post_idc_blog_newsletter', 'idc_handle_blog_newsletter');

/**
 * Submenu — inscritos da newsletter (lista local, sem Mailchimp).
 */
function idc_register_newsletter_admin(): void {
	add_submenu_page(
		'idc-opcoes',
		__('Newsletter', 'instituto-dr-chao'),
		__('Newsletter', 'instituto-dr-chao'),
		'manage_options',
		'idc-newsletter',
		'idc_render_newsletter_admin'
	);
}
add_action('admin_menu', 'idc_register_newsletter_admin', 20);

/**
 * Tela admin: listar / exportar / limpar inscritos.
 */
function idc_render_newsletter_admin(): void {
	if (!current_user_can('manage_options')) {
		return;
	}

	if (isset($_POST['idc_nl_admin_action']) && check_admin_referer('idc_nl_admin')) {
		$action = sanitize_key((string) wp_unslash($_POST['idc_nl_admin_action']));
		if ($action === 'clear') {
			delete_option('idc_newsletter_emails');
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Lista limpa.', 'instituto-dr-chao') . '</p></div>';
		}
	}

	$list = get_option('idc_newsletter_emails', []);
	if (!is_array($list)) {
		$list = [];
	}

	if (isset($_GET['idc_nl_export']) && check_admin_referer('idc_nl_export')) {
		nocache_headers();
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=idc-newsletter.csv');
		$out = fopen('php://output', 'w');
		if ($out) {
			fputcsv($out, ['email', 'at'], ',', '"', '\\');
			foreach ($list as $row) {
				if (!is_array($row)) {
					continue;
				}
				fputcsv(
					$out,
					[
						(string) ($row['email'] ?? ''),
						(string) ($row['at'] ?? ''),
					],
					',',
					'"',
					'\\'
				);
			}
			fclose($out);
		}
		exit;
	}

	$export_url = wp_nonce_url(admin_url('admin.php?page=idc-newsletter&idc_nl_export=1'), 'idc_nl_export');
	?>
	<div class="wrap">
		<h1><?php esc_html_e('Newsletter — inscritos', 'instituto-dr-chao'); ?></h1>
		<p><?php esc_html_e('Inscrições capturadas no formulário do Blog (armazenamento local). Integração Mailchimp pode ser ligada depois.', 'instituto-dr-chao'); ?></p>
		<p>
			<a class="button button-primary" href="<?php echo esc_url($export_url); ?>"><?php esc_html_e('Exportar CSV', 'instituto-dr-chao'); ?></a>
		</p>
		<table class="widefat striped">
			<thead>
				<tr>
					<th><?php esc_html_e('E-mail', 'instituto-dr-chao'); ?></th>
					<th><?php esc_html_e('Data', 'instituto-dr-chao'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ($list === []) : ?>
					<tr><td colspan="2"><?php esc_html_e('Nenhuma inscrição ainda.', 'instituto-dr-chao'); ?></td></tr>
				<?php else : ?>
					<?php foreach (array_reverse($list) as $row) : ?>
						<?php if (!is_array($row)) { continue; } ?>
						<tr>
							<td><?php echo esc_html((string) ($row['email'] ?? '')); ?></td>
							<td><?php echo esc_html((string) ($row['at'] ?? '')); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		<?php if ($list !== []) : ?>
			<form method="post" style="margin-top:16px" onsubmit="return confirm('Limpar toda a lista?');">
				<?php wp_nonce_field('idc_nl_admin'); ?>
				<input type="hidden" name="idc_nl_admin_action" value="clear">
				<button type="submit" class="button button-link-delete"><?php esc_html_e('Limpar lista', 'instituto-dr-chao'); ?></button>
			</form>
		<?php endif; ?>
	</div>
	<?php
}

