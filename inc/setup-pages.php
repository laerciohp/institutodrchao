<?php
/**
 * Cria páginas institucionais, menu e front page na ativação do tema.
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Páginas a garantir (slug => [title, template]).
 *
 * @return array<string, array{title:string, template:string}>
 */
function idc_setup_pages_map(): array {
	return [
		'especialidades'          => ['title' => 'Especialidades', 'template' => 'page-especialidades.php'],
		'ortopedia-regenerativa'  => ['title' => 'Ortopedia Regenerativa', 'template' => 'page-ortopedia.php'],
		'fisioterapia'            => ['title' => 'Fisioterapia', 'template' => 'page-fisioterapia.php'],
		'medicina-integrativa'    => ['title' => 'Medicina Integrativa', 'template' => 'page-medicina-integrativa.php'],
		'o-instituto'             => ['title' => 'O Instituto', 'template' => 'page-o-instituto.php'],
		'contato'                 => ['title' => 'Contato', 'template' => 'page-contato.php'],
		'carreiras'               => ['title' => 'Trabalhe Conosco', 'template' => 'page-carreiras.php'],
		'privacidade'             => ['title' => 'Privacidade', 'template' => 'page-privacidade.php'],
		'blog'                    => ['title' => 'Blog', 'template' => ''],
	];
}

/**
 * Garante páginas + leitura + menu.
 */
function idc_setup_ensure_pages(): void {
	if (!current_user_can('manage_options') && !doing_action('after_switch_theme')) {
		return;
	}

	$page_ids = [];

	foreach (idc_setup_pages_map() as $slug => $meta) {
		$existing = get_page_by_path($slug);
		if ($existing instanceof WP_Post) {
			$page_ids[$slug] = (int) $existing->ID;
			if ($meta['template'] !== '') {
				update_post_meta($existing->ID, '_wp_page_template', $meta['template']);
			}
			continue;
		}

		$id = wp_insert_post([
			'post_title'   => $meta['title'],
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => '',
		], true);

		if (is_wp_error($id) || !$id) {
			continue;
		}

		$page_ids[$slug] = (int) $id;
		if ($meta['template'] !== '') {
			update_post_meta((int) $id, '_wp_page_template', $meta['template']);
		}
	}

	// Front page: cria "Início" se necessário.
	$front_id = (int) get_option('page_on_front');
	if ($front_id <= 0) {
		$home = get_page_by_path('inicio');
		if (!$home) {
			$home_id = wp_insert_post([
				'post_title'  => 'Início',
				'post_name'   => 'inicio',
				'post_status' => 'publish',
				'post_type'   => 'page',
			]);
			if (!is_wp_error($home_id) && $home_id) {
				$front_id = (int) $home_id;
			}
		} else {
			$front_id = (int) $home->ID;
		}
		if ($front_id > 0) {
			update_option('show_on_front', 'page');
			update_option('page_on_front', $front_id);
		}
	}

	if (!empty($page_ids['blog'])) {
		update_option('page_for_posts', $page_ids['blog']);
	}

	idc_setup_ensure_menu($page_ids, $front_id);
	idc_setup_seed_options();
}
add_action('after_switch_theme', 'idc_setup_ensure_pages');

/**
 * Botão admin para (re)criar páginas.
 */
function idc_setup_admin_notice(): void {
	if (!current_user_can('manage_options')) {
		return;
	}

	if (isset($_GET['idc_setup_pages']) && check_admin_referer('idc_setup_pages')) {
		idc_setup_ensure_pages();
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Páginas, menu e opções IDC atualizados.', 'instituto-dr-chao') . '</p></div>';
	}

	$screen = function_exists('get_current_screen') ? get_current_screen() : null;
	if (!$screen || !in_array($screen->id, ['themes', 'toplevel_page_idc-opcoes', 'dashboard'], true)) {
		return;
	}

	$url = wp_nonce_url(admin_url('themes.php?idc_setup_pages=1'), 'idc_setup_pages');
	echo '<div class="notice notice-info"><p>';
	echo esc_html__('Instituto Dr. Chao: ', 'instituto-dr-chao');
	echo '<a href="' . esc_url($url) . '">' . esc_html__('criar/atualizar páginas, menu e dados da clínica', 'instituto-dr-chao') . '</a>';
	echo '</p></div>';
}
add_action('admin_notices', 'idc_setup_admin_notice');

/**
 * Menu principal.
 *
 * @param array<string,int> $page_ids
 */
function idc_setup_ensure_menu(array $page_ids, int $front_id): void {
	$menu_name = 'Menu Principal IDC';
	$menu      = wp_get_nav_menu_object($menu_name);

	if (!$menu) {
		$menu_id = wp_create_nav_menu($menu_name);
	} else {
		$menu_id = (int) $menu->term_id;
	}

	if (is_wp_error($menu_id) || !$menu_id) {
		return;
	}

	$items = wp_get_nav_menu_items($menu_id);
	if (empty($items)) {
		$order = [
			['title' => 'Início', 'id' => $front_id],
			['title' => 'Especialidades', 'id' => $page_ids['especialidades'] ?? 0],
			['title' => 'O Instituto', 'id' => $page_ids['o-instituto'] ?? 0],
			['title' => 'Blog', 'id' => $page_ids['blog'] ?? 0],
			['title' => 'Contato', 'id' => $page_ids['contato'] ?? 0],
		];
		$i = 1;
		foreach ($order as $row) {
			if (empty($row['id'])) {
				continue;
			}
			wp_update_nav_menu_item($menu_id, 0, [
				'menu-item-title'     => $row['title'],
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $row['id'],
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => $i++,
			]);
		}
	}

	$locations            = get_theme_mod('nav_menu_locations', []);
	$locations['primary'] = $menu_id;
	set_theme_mod('nav_menu_locations', $locations);
}

/**
 * Seed das opções ACF com dados reais da clínica (só se vazios).
 */
function idc_setup_seed_options(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$defaults = [
		'idc_telefone'           => '(11) 2218-8080',
		'idc_whatsapp'           => '551122188080',
		'idc_whatsapp_mensagem'  => 'Olá! Gostaria de agendar uma consulta no Instituto Dr. Chao.',
		'idc_endereco'           => "Rua Maria Cândida, 1.788\nVila Guilherme — São Paulo, SP\nCEP 02071-003",
		'idc_email'              => 'atendimento@institutodrchao.com.br',
		'idc_email_rh'           => 'rh@institutodrchao.com.br',
		'idc_horario'            => 'Seg. à Sex. das 08h às 18h',
		'idc_map_embed'          => 'https://www.google.com/maps?q=Rua+Maria+C%C3%A2ndida,+1788,+Vila+Guilherme,+S%C3%A3o+Paulo&output=embed',
		'idc_instagram'          => 'https://www.instagram.com/institutodrchao/',
	];

	foreach ($defaults as $key => $value) {
		$current = get_field($key, 'option');
		if ($current === null || $current === false || $current === '') {
			update_field($key, $value, 'option');
		}
	}
}
