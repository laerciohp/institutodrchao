<?php
/**
 * Helpers do tema.
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * URL de asset do tema.
 */
function idc_asset(string $relative): string {
	return trailingslashit(IDC_THEME_URI) . ltrim($relative, '/');
}

/**
 * Prefere PNG de export Figma quando existir; senão JPG/WebP informado.
 */
function idc_theme_image(string $relative_without_ext, string $fallback_ext = 'jpg'): string {
	$base = ltrim($relative_without_ext, '/');
	$png  = IDC_THEME_DIR . '/' . $base . '.png';
	if (is_readable($png)) {
		return idc_asset($base . '.png');
	}
	return idc_asset($base . '.' . ltrim($fallback_ext, '.'));
}

/**
 * Lê campo ACF da Home (página Início) ou Options, com fallback.
 *
 * Prioridade: meta da front page → IDC Opções → $default.
 * Assim “Editar página” na Home e IDC Opções → Home funcionam.
 *
 * @param mixed $default
 * @return mixed
 */
function idc_option(string $key, $default = '') {
	if (function_exists('get_field')) {
		$front_id = (int) get_option('page_on_front');
		if ($front_id > 0) {
			$value = get_field($key, $front_id);
			if ($value !== null && $value !== false && $value !== '') {
				return $value;
			}
		}

		$value = get_field($key, 'option');
		if ($value !== null && $value !== false && $value !== '') {
			return $value;
		}
	}

	// Fallback nativo (ACF Free sem Options Page).
	if (function_exists('idc_native_setting')) {
		$native = idc_native_settings();
		if (array_key_exists($key, $native)) {
			return $native[$key];
		}
	}

	return $default;
}

/**
 * Número WhatsApp limpo (somente dígitos, com DDI).
 */
function idc_whatsapp_number(): string {
	$raw = (string) idc_option('idc_whatsapp', '5511943356377');
	return preg_replace('/\D+/', '', $raw) ?: '5511943356377';
}

/**
 * Monta URL do WhatsApp com mensagem e origem.
 *
 * @param array{origem?:string,mensagem?:string} $args
 */
function idc_whatsapp_url(array $args = []): string {
	$origem   = sanitize_title((string) ($args['origem'] ?? 'site'));
	$mensagem = (string) ($args['mensagem'] ?? '');

	if ($mensagem === '') {
		$mensagem = (string) idc_option(
			'idc_whatsapp_mensagem',
			'Olá, gostaria de realizar um agendamento!'
		);
	}

	$mensagem .= "\n\n[origem: {$origem}]";

	$query = http_build_query([
		'phone' => idc_whatsapp_number(),
		'text'  => $mensagem,
	], '', '&', PHP_QUERY_RFC3986);

	$url = 'https://api.whatsapp.com/send?' . $query;

	/**
	 * Permite filtrar a URL final do WhatsApp.
	 */
	return (string) apply_filters('idc_whatsapp_url', $url, $origem, $args);
}

/**
 * Link WhatsApp com origem da página/contexto atual.
 */
function idc_whatsapp_url_for_context(string $origem = ''): string {
	if ($origem === '') {
		if (is_front_page()) {
			$origem = 'home';
		} elseif (is_singular('idc_tratamento')) {
			$origem = 'tratamento-' . get_post_field('post_name');
		} elseif (is_page()) {
			$origem = get_post_field('post_name') ?: 'pagina';
		} else {
			$origem = 'site';
		}
	}

	return idc_whatsapp_url(['origem' => $origem]);
}

/**
 * Echo escapado de atributo href WhatsApp.
 */
function idc_the_whatsapp_url(string $origem = ''): void {
	echo esc_url(idc_whatsapp_url_for_context($origem));
}

/**
 * Lê campo ACF da página atual com fallback tipado.
 *
 * @param mixed $default
 * @return mixed
 */
function idc_page_field(string $key, $default = '') {
	if (!function_exists('get_field')) {
		return $default;
	}
	$value = get_field($key);
	if ($value === null || $value === false || $value === '') {
		return $default;
	}
	if (is_array($value) && $value === []) {
		return $default;
	}
	return $value;
}

/**
 * Slugs de posts odontológicos (fora do escopo Figma Orto/Fisio/Integrativa).
 *
 * @return list<string>
 */
function idc_odonto_post_slugs(): array {
	return [
		'gengiva-inflamada-sinais-e-sintomas',
		'bruxismo-sintomas-causas-tratamentos',
		'dente-do-siso-inflamado',
		'dor-de-dente-causas-e-tratamentos',
		'gengiva-inchada-o-que-pode-ser',
	];
}

/**
 * Categorias exibidas nos filtros do blog (pilares Figma, sem “Sem categoria” / odonto).
 *
 * @return list<WP_Term>
 */
function idc_blog_filter_categories(): array {
	$exclude = [];
	$default_id = (int) get_option('default_category');
	if ($default_id > 0) {
		$exclude[] = $default_id;
	}
	foreach (['uncategorized', 'sem-categoria', 'odontologia'] as $slug) {
		$term = get_category_by_slug($slug);
		if ($term instanceof WP_Term) {
			$exclude[] = (int) $term->term_id;
		}
	}
	$exclude = array_values(array_unique(array_filter($exclude)));

	$preferred = [];
	foreach (idc_default_blog_categories() as $def) {
		$term = get_category_by_slug($def['slug']);
		if ($term instanceof WP_Term && (int) $term->count > 0) {
			$preferred[] = $term;
		}
	}

	$others = get_categories([
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
		'exclude'    => $exclude,
	]);

	$seen = array_map(static fn(WP_Term $t): int => (int) $t->term_id, $preferred);
	foreach ($others as $term) {
		if (!$term instanceof WP_Term) {
			continue;
		}
		$id = (int) $term->term_id;
		if (in_array($id, $seen, true) || in_array($id, $exclude, true)) {
			continue;
		}
		$preferred[] = $term;
		$seen[]      = $id;
	}

	return $preferred;
}

/**
 * Normaliza ícone ACF (array image | URL string) para URL.
 *
 * @param mixed $icon
 */
function idc_icon_url($icon, string $fallback = ''): string {
	return idc_image_url($icon, $fallback);
}
