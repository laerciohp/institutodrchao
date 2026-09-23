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
		'footer_tratamentos'   => __('Rodapé — Tratamentos', 'instituto-dr-chao'),
		'footer_institucional' => __('Rodapé — Institucional', 'instituto-dr-chao'),
		'footer_contato'       => __('Rodapé — Contato', 'instituto-dr-chao'),
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

/**
 * Exclui posts odontológicos do arquivo do blog (escopo Figma: Orto/Fisio/Integrativa).
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
 * Migração leve ao atualizar a Version do tema (flush CPT + sync Home page).
 */
function idc_maybe_run_theme_upgrade(): void {
	$stored = (string) get_option('idc_theme_version_installed', '');
	if ($stored === IDC_THEME_VERSION) {
		return;
	}

	flush_rewrite_rules(false);

	idc_upgrade_183_layout_cms();
	idc_upgrade_184_layout_cms();
	idc_upgrade_186_layout_cms();
	idc_upgrade_187_layout_cms();
	idc_upgrade_188_layout_cms();
	idc_upgrade_189_layout_cms();
	idc_upgrade_190_layout_cms();

	// Copia Options da Home para a página Início (se vazia), para “Editar página” funcionar.
	$front_id = (int) get_option('page_on_front');
	if ($front_id > 0 && function_exists('get_field') && function_exists('update_field')) {
		$keys = [
			'idc_hero_eyebrow',
			'idc_hero_desde',
			'idc_hero_title_before',
			'idc_hero_title_accent',
			'idc_hero_title_after',
			'idc_hero_lead',
			'idc_hero_primary_label',
			'idc_hero_secondary_label',
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

	update_option('idc_theme_version_installed', IDC_THEME_VERSION, false);
}
add_action('admin_init', 'idc_maybe_run_theme_upgrade', 5);
add_action('init', 'idc_maybe_run_theme_upgrade', 20);

/**
 * v1.8.3 — republica Carreiras se estiver fora do ar e alinha títulos ACF ao Figma.
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
			'idc_page_eyebrow'      => 'TRATAMENTOS · ESPECIALIDADE PRINCIPAL',
			'idc_page_title_before' => '',
			'idc_page_title_accent' => 'Ortopedia Regenerativa',
			'idc_page_title_after'  => '',
		],
		'fisioterapia' => [
			'idc_page_eyebrow'      => '',
			'idc_page_title_before' => '',
			'idc_page_title_accent' => 'Fisioterapia Especializada em Dor',
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
			'idc_page_lead'         => 'Artigos, dicas e novidades sobre ortopedia, fisioterapia, medicina integrativa e bem-estar. Escritos por nossa equipe de especialistas para ajudar você a viver com mais movimento e menos dor.',
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
 * v1.8.4 — alinha Carreiras e archive de Tratamentos aos defaults do Figma.
 */
function idc_upgrade_184_layout_cms(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$carreiras = get_page_by_path('carreiras');
	if ($carreiras instanceof WP_Post) {
		update_field('idc_page_title_before', 'Faça parte do ', (int) $carreiras->ID);
		update_field('idc_page_title_accent', 'time', (int) $carreiras->ID);
		update_field('idc_page_title_after', '', (int) $carreiras->ID);
		update_field(
			'idc_page_lead',
			"No Instituto Dr. Chao, acreditamos que oferecer um atendimento excepcional começa por ter uma equipe movida por empatia, dedicação e excelência. Se você compartilha do nosso compromisso de acolher, cuidar e transformar a jornada de diagnóstico e reabilitação das pessoas, nós queremos conhecer você. Venha construir uma carreira com propósito.\n\nPreencha o formulário abaixo, anexe seu currículo e dê o primeiro passo para fazer a diferença em cada etapa do nosso trabalho.",
			(int) $carreiras->ID
		);
		update_field('idc_carreiras_form_title', 'Envie uma mensagem', (int) $carreiras->ID);
		update_field('idc_carreiras_form_lead', '', (int) $carreiras->ID);
	}
}

/**
 * v1.8.6 — sync Hub cards + strips Especialidades/Orto/Fisio/Integrativa ao Figma.
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
 * v1.8.7 — sync lead/FAQ Orto, limpa footer Instalações (Figma).
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
		update_field('idc_page_title_before', 'Faça parte do ', (int) $carreiras->ID);
		update_field('idc_page_title_accent', 'time', (int) $carreiras->ID);
		update_field('idc_page_title_after', '', (int) $carreiras->ID);
	}

	if (function_exists('idc_setup_remove_footer_instalacoes_link')) {
		idc_setup_remove_footer_instalacoes_link();
	}
}

/**
 * v1.8.8 — alinha Home (hero Figma) + Hub title split + limpa leads fora do frame.
 */
function idc_upgrade_188_layout_cms(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$hero = [
		'idc_hero_title_before' => 'A dor não precisa definir a',
		'idc_hero_title_accent' => 'sua vida.',
		'idc_hero_title_after'  => '',
		'idc_hero_lead'         => "Existimos para que ninguém seja definido pela sua dor.\nCombinamos vanguarda médica e terapias integrativas em um ambiente pensado para a sua verdadeira recuperação e bem-estar contínuo.",
		'idc_hero_cta_primary'  => 'Agendar Consulta',
		'idc_hero_cta_secondary'=> 'Conheça os tratamentos',
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

	// Prefer PNG de maior fidelidade quando existir no tema (Figma export).
	$fisio = get_page_by_path('fisioterapia');
	if ($fisio instanceof WP_Post) {
		$png = IDC_THEME_DIR . '/assets/images/pages/hero-fisioterapia.png';
		if (is_readable($png)) {
			// Mantém ACF vazio para o template usar o fallback do tema (png/jpg).
			update_field('idc_page_hero_image', null, (int) $fisio->ID);
		}
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
 * v1.8.9 — badge depoimentos Figma + limpa override de imagem "Por que escolher".
 */
function idc_upgrade_189_layout_cms(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$badge = '4.9 · 742 avaliações';
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
 * v1.9.0 — Home Figma SRIgo (hero/pilares/why/hub) + Instituto essência.
 */
function idc_upgrade_190_layout_cms(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$hero = [
		'idc_hero_title_before'  => 'Tratamos a',
		'idc_hero_title_accent'  => 'origem da dor.',
		'idc_hero_title_after'   => ' Você sente a mudança.',
		'idc_hero_lead'          => "Existimos para que ninguém seja definido pela sua dor.\nCombinamos vanguarda médica e terapias integrativas em um ambiente pensado para a sua verdadeira recuperação e bem-estar contínuo.",
		'idc_hero_cta_primary'   => 'Agendar Consulta',
		'idc_hero_cta_secondary' => 'Conheça os tratamentos',
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
		update_field('idc_instituto_essencia_eyebrow', 'NOSSA ESSÊNCIA', (int) $inst->ID);
		update_field('idc_instituto_essencia_title', 'Cuidado que une experiência, ciência e acolhimento', (int) $inst->ID);
		update_field(
			'idc_instituto_essencia_lead',
			'Desde 1987, o Instituto Dr. Chao une precisão ortopédica, reabilitação e medicina integrativa para tratar a origem da dor — com ciência, escuta e acolhimento.',
			(int) $inst->ID
		);
		update_field('idc_instituto_cards', idc_default_instituto_cards(), (int) $inst->ID);
		update_field('idc_instituto_image', null, (int) $inst->ID);
	}
}

