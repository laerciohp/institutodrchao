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
 * Prefere PNG de export Figma quando existir; senão extensão informada (jpg/webp).
 *
 * Passe $prefer_ext = true para forçar a extensão pedida (ex.: hub JPG quando PNG legado diverge).
 */
function idc_theme_image(string $relative_without_ext, string $fallback_ext = 'jpg', bool $prefer_ext = false): string {
	$base = ltrim($relative_without_ext, '/');
	$ext  = ltrim($fallback_ext, '.');
	if ($prefer_ext) {
		$forced = IDC_THEME_DIR . '/' . $base . '.' . $ext;
		if (is_readable($forced)) {
			return idc_asset($base . '.' . $ext);
		}
	}
	$png = IDC_THEME_DIR . '/' . $base . '.png';
	if (is_readable($png)) {
		return idc_asset($base . '.png');
	}
	return idc_asset($base . '.' . $ext);
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
 * Executa um upgrade de CMS no máximo uma vez (flag em options).
 *
 * @param callable():void $callback
 */
function idc_run_upgrade_once(string $flag, callable $callback): void {
	$option = 'idc_upgrade_' . $flag . '_done';
	if (get_option($option)) {
		return;
	}
	$callback();
	update_option($option, 1, false);
}

/**
 * Resolve args do strip CTA: ACF da página atual → defaults do slug → overrides.
 *
 * @param array<string,mixed> $overrides
 * @return array{origem:string,decor:bool,align:string,title:string,lead:string,label:string,secondary_label:string,secondary_url:string}
 */
function idc_strip_cta_args_for_page(string $slug, array $overrides = []): array {
	$defaults = function_exists('idc_default_strip_cta_for_slug')
		? idc_default_strip_cta_for_slug($slug)
		: [
			'idc_strip_title' => '',
			'idc_strip_lead'  => '',
			'idc_strip_label' => '',
		];

	$title = (string) idc_page_field('idc_strip_title', (string) ($defaults['idc_strip_title'] ?? ''));
	$lead  = (string) idc_page_field('idc_strip_lead', (string) ($defaults['idc_strip_lead'] ?? ''));
	$label = (string) idc_page_field('idc_strip_label', (string) ($defaults['idc_strip_label'] ?? ''));
	$sec_l = (string) idc_page_field('idc_strip_secondary_label', '');
	$sec_u = (string) idc_page_field('idc_strip_secondary_url', '');

	$args = [
		'origem'          => $slug,
		'decor'           => true,
		'align'           => 'center',
		'title'           => $title,
		'lead'            => $lead,
		'label'           => $label,
		'secondary_label' => $sec_l,
		'secondary_url'   => $sec_u !== '' ? $sec_u : '',
	];

	foreach ($overrides as $key => $value) {
		if ($value === null || $value === '') {
			unset($overrides[$key]);
		}
	}

	return array_merge($args, $overrides);
}

/**
 * Seções padrão da Home (ordem Figma) — usados pelo flexible content.
 *
 * @return list<array{acf_fc_layout:string}>
 */
function idc_default_home_sections(): array {
	return [
		['acf_fc_layout' => 'hero'],
		['acf_fc_layout' => 'trust'],
		['acf_fc_layout' => 'pillars'],
		['acf_fc_layout' => 'why'],
		['acf_fc_layout' => 'testimonials'],
		['acf_fc_layout' => 'team'],
		['acf_fc_layout' => 'blog'],
		['acf_fc_layout' => 'cta'],
	];
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

/**
 * Fallback de capa Figma para cards do blog (home/arquivo) sem featured image.
 *
 * @param string $slug  post_name
 * @param int    $index índice do card na grade (ciclo 0–2)
 */
function idc_blog_figma_fallback_image(string $slug = '', int $index = 0): string {
	$map = [
		'o-que-e-a-ortopedia-regenerativa-e-como-ela-transforma-vidas' => 'assets/images/blog/figma-joelho-corrida.jpg',
		'como-o-uso-do-celular-pode-piorar-a-sua-dor'                 => 'assets/images/blog/figma-acupuntura.jpg',
		'8-dicas-para-aliviar-as-dores-musculares-apos-o-treino-na-academia' => 'assets/images/blog/figma-fisio-pos-op.jpg',
		'medicina-integrativa-o-cuidado-que-enxerga-voce-por-inteiro' => 'assets/images/blog/figma-acupuntura.jpg',
	];

	if ($slug !== '' && isset($map[$slug])) {
		return idc_asset($map[$slug]);
	}

	$cycle = [
		'assets/images/blog/figma-joelho-corrida.jpg',
		'assets/images/blog/figma-acupuntura.jpg',
		'assets/images/blog/figma-fisio-pos-op.jpg',
	];

	return idc_asset($cycle[abs($index) % 3]);
}

/**
 * Meta/ACF de um profissional.
 */
function idc_profissional_meta(int $post_id, string $key): string {
	if (function_exists('get_field')) {
		$value = get_field($key, $post_id);
		if ($value !== null && $value !== false && $value !== '') {
			return is_string($value) ? trim($value) : trim((string) $value);
		}
	}
	return trim((string) get_post_meta($post_id, $key, true));
}

/**
 * Excerpt HTML limpo para card/modal (lista curta ou texto).
 */
function idc_profissional_excerpt_html(int $post_id, int $limit_items = 3): string {
	$custom = idc_profissional_meta($post_id, 'idc_bio_excerpt');
	if ($custom !== '') {
		return wpautop(esc_html($custom));
	}

	$content = (string) get_post_field('post_content', $post_id);
	if ($content === '') {
		return '';
	}

	if (preg_match_all('/<li\b[^>]*>(.*?)<\/li>/is', $content, $matches) && !empty($matches[1])) {
		$items = array_slice($matches[1], 0, max(1, $limit_items));
		$html  = '<ul class="idc-team-excerpt">';
		foreach ($items as $item) {
			$html .= '<li>' . wp_kses_post($item) . '</li>';
		}
		$html .= '</ul>';
		return $html;
	}

	$plain = wp_trim_words(wp_strip_all_tags($content), 36, '…');
	return $plain !== '' ? '<p>' . esc_html($plain) . '</p>' : '';
}

/**
 * Contatos/redes opcionais do profissional (só itens preenchidos).
 *
 * @return list<array{type:string,label:string,url:string,icon:string}>
 */
function idc_profissional_contacts(int $post_id): array {
	$out = [];

	$email = idc_profissional_meta($post_id, 'idc_prof_email');
	if ($email !== '' && is_email($email)) {
		$out[] = [
			'type'  => 'email',
			'label' => __('E-mail', 'instituto-dr-chao'),
			'url'   => 'mailto:' . $email,
			'icon'  => 'assets/icons/icon-email.svg',
		];
	}

	$wa = preg_replace('/\D+/', '', idc_profissional_meta($post_id, 'idc_prof_whatsapp')) ?: '';
	if ($wa !== '') {
		$msg = rawurlencode(
			sprintf(
				/* translators: %s: professional name */
				__('Olá, gostaria de falar com %s.', 'instituto-dr-chao'),
				get_the_title($post_id)
			)
		);
		$out[] = [
			'type'  => 'whatsapp',
			'label' => __('WhatsApp', 'instituto-dr-chao'),
			'url'   => 'https://api.whatsapp.com/send?phone=' . $wa . '&text=' . $msg,
			'icon'  => 'assets/icons/icon-whatsapp.svg',
		];
	}

	$instagram = idc_profissional_meta($post_id, 'idc_prof_instagram');
	if ($instagram !== '') {
		$out[] = [
			'type'  => 'instagram',
			'label' => __('Instagram', 'instituto-dr-chao'),
			'url'   => $instagram,
			'icon'  => 'assets/icons/social-instagram.svg',
		];
	}

	$linkedin = idc_profissional_meta($post_id, 'idc_prof_linkedin');
	if ($linkedin !== '') {
		$out[] = [
			'type'  => 'linkedin',
			'label' => __('LinkedIn', 'instituto-dr-chao'),
			'url'   => $linkedin,
			'icon'  => 'assets/icons/social-linkedin.svg',
		];
	}

	return $out;
}
