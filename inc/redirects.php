<?php
/**
 * Redirects de URLs legadas da produção.
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * /equipe/ → /corpo-clinico/
 * /equipe/{slug}/ → /corpo-clinico/{slug}/
 */
function idc_redirect_legacy_equipe(): void {
	if (is_admin() || wp_doing_ajax() || wp_doing_cron()) {
		return;
	}

	$path = (string) wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
	$path = untrailingslashit(strtolower($path));

	if ($path === '' || $path === '/') {
		return;
	}

	// Remove base path if site is in subdirectory.
	$home_path = (string) wp_parse_url(home_url('/'), PHP_URL_PATH);
	$home_path = untrailingslashit($home_path);
	if ($home_path !== '' && str_starts_with($path, $home_path)) {
		$path = substr($path, strlen($home_path)) ?: '';
		$path = untrailingslashit($path);
	}

	if ($path === '/equipe') {
		wp_safe_redirect(home_url('/corpo-clinico/'), 301);
		exit;
	}

	if (preg_match('#^/equipe/([^/]+)$#', $path, $m)) {
		wp_safe_redirect(home_url('/corpo-clinico/' . sanitize_title($m[1]) . '/'), 301);
		exit;
	}

	// Páginas de tratamento legadas → CPT /tratamentos/{slug}/.
	$legacy_treatments = [
		'/viscosuplementacao' => '/tratamentos/viscosuplementacao/',
		'/ozonioterapia'      => '/tratamentos/ozonioterapia/',
	];
	foreach ($legacy_treatments as $from => $to) {
		if ($path === $from) {
			wp_safe_redirect(home_url($to), 301);
			exit;
		}
	}
}
add_action('template_redirect', 'idc_redirect_legacy_equipe', 1);
