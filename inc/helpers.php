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
 * Lê opção ACF (com fallback).
 *
 * @param mixed $default
 * @return mixed
 */
function idc_option(string $key, $default = '') {
	if (function_exists('get_field')) {
		$value = get_field($key, 'option');
		if ($value !== null && $value !== false && $value !== '') {
			return $value;
		}
	}
	return $default;
}

/**
 * Número WhatsApp limpo (somente dígitos, com DDI).
 */
function idc_whatsapp_number(): string {
	$raw = (string) idc_option('idc_whatsapp', '5511999999999');
	return preg_replace('/\D+/', '', $raw) ?: '5511999999999';
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
			'Olá! Gostaria de agendar uma consulta no Instituto Dr. Chao.'
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
