<?php
/**
 * CPT Contato e Currículos — inbox no painel (envios dos formulários).
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Registra CPTs de formulário (somente admin).
 */
function idc_register_cpt_form_submissions(): void {
	$common = [
		'public'              => false,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => false,
		'exclude_from_search' => true,
		'has_archive'         => false,
		'rewrite'             => false,
		'capability_type'     => 'post',
		'map_meta_cap'        => true,
		'supports'            => ['title'],
	];

	register_post_type(
		'idc_contato',
		array_merge(
			$common,
			[
				'labels' => [
					'name'               => __('Contatos', 'instituto-dr-chao'),
					'singular_name'      => __('Contato', 'instituto-dr-chao'),
					'menu_name'          => __('Contatos', 'instituto-dr-chao'),
					'add_new'            => __('Adicionar', 'instituto-dr-chao'),
					'add_new_item'       => __('Adicionar contato', 'instituto-dr-chao'),
					'edit_item'          => __('Editar contato', 'instituto-dr-chao'),
					'new_item'           => __('Novo contato', 'instituto-dr-chao'),
					'search_items'       => __('Buscar contatos', 'instituto-dr-chao'),
					'not_found'          => __('Nenhum contato encontrado.', 'instituto-dr-chao'),
					'not_found_in_trash' => __('Nada na lixeira.', 'instituto-dr-chao'),
				],
				'menu_icon'  => 'dashicons-email-alt',
				'menu_position' => 26,
			]
		)
	);

	register_post_type(
		'idc_curriculo',
		array_merge(
			$common,
			[
				'labels' => [
					'name'               => __('Currículos', 'instituto-dr-chao'),
					'singular_name'      => __('Currículo', 'instituto-dr-chao'),
					'menu_name'          => __('Currículos', 'instituto-dr-chao'),
					'add_new'            => __('Adicionar', 'instituto-dr-chao'),
					'add_new_item'       => __('Adicionar currículo', 'instituto-dr-chao'),
					'edit_item'          => __('Editar currículo', 'instituto-dr-chao'),
					'new_item'           => __('Novo currículo', 'instituto-dr-chao'),
					'search_items'       => __('Buscar currículos', 'instituto-dr-chao'),
					'not_found'          => __('Nenhum currículo encontrado.', 'instituto-dr-chao'),
					'not_found_in_trash' => __('Nada na lixeira.', 'instituto-dr-chao'),
				],
				'menu_icon'  => 'dashicons-id-alt',
				'menu_position' => 27,
			]
		)
	);
}
add_action('init', 'idc_register_cpt_form_submissions');

/**
 * Meta boxes de leitura.
 */
function idc_form_submissions_meta_boxes(): void {
	add_meta_box(
		'idc_contato_dados',
		__('Dados do envio', 'instituto-dr-chao'),
		'idc_contato_metabox_render',
		'idc_contato',
		'normal',
		'high'
	);
	add_meta_box(
		'idc_curriculo_dados',
		__('Dados do candidato', 'instituto-dr-chao'),
		'idc_curriculo_metabox_render',
		'idc_curriculo',
		'normal',
		'high'
	);
}
add_action('add_meta_boxes', 'idc_form_submissions_meta_boxes');

/**
 * @param WP_Post $post
 */
function idc_contato_metabox_render(WP_Post $post): void {
	$rows = [
		'Nome'     => (string) get_post_meta($post->ID, '_idc_nome', true),
		'E-mail'   => (string) get_post_meta($post->ID, '_idc_email', true),
		'Telefone' => (string) get_post_meta($post->ID, '_idc_telefone', true),
		'Assunto'  => (string) get_post_meta($post->ID, '_idc_assunto', true),
		'LGPD'     => get_post_meta($post->ID, '_idc_lgpd', true) ? 'Aceito' : '—',
		'Mensagem' => (string) get_post_meta($post->ID, '_idc_mensagem', true),
	];
	idc_form_metabox_table($rows);
}

/**
 * @param WP_Post $post
 */
function idc_curriculo_metabox_render(WP_Post $post): void {
	$attach_id = (int) get_post_meta($post->ID, '_idc_curriculo_id', true);
	$cv_html   = '—';
	if ($attach_id > 0) {
		$url = wp_get_attachment_url($attach_id);
		if ($url) {
			$cv_html = '<a href="' . esc_url($url) . '" target="_blank" rel="noopener">' . esc_html(basename($url)) . '</a>';
		}
	}

	$rows = [
		'Nome'       => (string) get_post_meta($post->ID, '_idc_nome', true),
		'E-mail'     => (string) get_post_meta($post->ID, '_idc_email', true),
		'Telefone'   => (string) get_post_meta($post->ID, '_idc_telefone', true),
		'Área/vaga'  => (string) get_post_meta($post->ID, '_idc_area', true),
		'LGPD'       => get_post_meta($post->ID, '_idc_lgpd', true) ? 'Aceito' : '—',
		'Mensagem'   => (string) get_post_meta($post->ID, '_idc_mensagem', true),
		'Currículo'  => $cv_html,
	];
	idc_form_metabox_table($rows, true);
}

/**
 * @param array<string,string> $rows
 */
function idc_form_metabox_table(array $rows, bool $allow_html = false): void {
	echo '<table class="widefat striped"><tbody>';
	foreach ($rows as $label => $value) {
		echo '<tr><th style="width:140px;text-align:left">' . esc_html($label) . '</th><td>';
		if ($allow_html && $label === 'Currículo') {
			echo $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above with esc_url/esc_html
		} else {
			echo nl2br(esc_html((string) $value));
		}
		echo '</td></tr>';
	}
	echo '</tbody></table>';
}

/**
 * Colunas Contato.
 *
 * @param array<string,string> $columns
 * @return array<string,string>
 */
function idc_contato_columns(array $columns): array {
	return [
		'cb'           => $columns['cb'] ?? '',
		'title'        => __('Nome', 'instituto-dr-chao'),
		'idc_email'    => __('E-mail', 'instituto-dr-chao'),
		'idc_assunto'  => __('Assunto', 'instituto-dr-chao'),
		'date'         => __('Recebido', 'instituto-dr-chao'),
	];
}
add_filter('manage_idc_contato_posts_columns', 'idc_contato_columns');

/**
 * @param string $column
 * @param int    $post_id
 */
function idc_contato_column_content(string $column, int $post_id): void {
	if ($column === 'idc_email') {
		echo esc_html((string) get_post_meta($post_id, '_idc_email', true));
	}
	if ($column === 'idc_assunto') {
		echo esc_html((string) get_post_meta($post_id, '_idc_assunto', true));
	}
}
add_action('manage_idc_contato_posts_custom_column', 'idc_contato_column_content', 10, 2);

/**
 * Colunas Currículo.
 *
 * @param array<string,string> $columns
 * @return array<string,string>
 */
function idc_curriculo_columns(array $columns): array {
	return [
		'cb'        => $columns['cb'] ?? '',
		'title'     => __('Nome', 'instituto-dr-chao'),
		'idc_email' => __('E-mail', 'instituto-dr-chao'),
		'idc_area'  => __('Área', 'instituto-dr-chao'),
		'idc_cv'    => __('Arquivo', 'instituto-dr-chao'),
		'date'      => __('Recebido', 'instituto-dr-chao'),
	];
}
add_filter('manage_idc_curriculo_posts_columns', 'idc_curriculo_columns');

/**
 * @param string $column
 * @param int    $post_id
 */
function idc_curriculo_column_content(string $column, int $post_id): void {
	if ($column === 'idc_email') {
		echo esc_html((string) get_post_meta($post_id, '_idc_email', true));
	}
	if ($column === 'idc_area') {
		echo esc_html((string) get_post_meta($post_id, '_idc_area', true));
	}
	if ($column === 'idc_cv') {
		$attach_id = (int) get_post_meta($post_id, '_idc_curriculo_id', true);
		if ($attach_id > 0) {
			$url = wp_get_attachment_url($attach_id);
			if ($url) {
				echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener">PDF/DOC</a>';
				return;
			}
		}
		echo '—';
	}
}
add_action('manage_idc_curriculo_posts_custom_column', 'idc_curriculo_column_content', 10, 2);

/**
 * Salva envio de Contato no CPT.
 *
 * @param array{nome:string,email:string,telefone:string,assunto:string,mensagem:string,lgpd:bool} $data
 */
function idc_save_contato_submission(array $data): int {
	$post_id = wp_insert_post(
		[
			'post_type'   => 'idc_contato',
			'post_status' => 'private',
			'post_title'  => $data['nome'] !== '' ? $data['nome'] : __('Contato sem nome', 'instituto-dr-chao'),
		],
		true
	);

	if (is_wp_error($post_id) || !$post_id) {
		return 0;
	}

	update_post_meta($post_id, '_idc_nome', $data['nome']);
	update_post_meta($post_id, '_idc_email', $data['email']);
	update_post_meta($post_id, '_idc_telefone', $data['telefone']);
	update_post_meta($post_id, '_idc_assunto', $data['assunto']);
	update_post_meta($post_id, '_idc_mensagem', $data['mensagem']);
	update_post_meta($post_id, '_idc_lgpd', !empty($data['lgpd']) ? 1 : 0);
	update_post_meta($post_id, '_idc_crm_status', function_exists('idc_crm_default_status') ? idc_crm_default_status('idc_contato') : 'novo');

	return (int) $post_id;
}

/**
 * Salva envio de Currículo no CPT (+ attachment).
 *
 * @param array{nome:string,email:string,telefone:string,area:string,mensagem:string,lgpd:bool,attachment_id?:int} $data
 */
function idc_save_curriculo_submission(array $data): int {
	$post_id = wp_insert_post(
		[
			'post_type'   => 'idc_curriculo',
			'post_status' => 'private',
			'post_title'  => $data['nome'] !== '' ? $data['nome'] : __('Candidato sem nome', 'instituto-dr-chao'),
		],
		true
	);

	if (is_wp_error($post_id) || !$post_id) {
		return 0;
	}

	update_post_meta($post_id, '_idc_nome', $data['nome']);
	update_post_meta($post_id, '_idc_email', $data['email']);
	update_post_meta($post_id, '_idc_telefone', $data['telefone']);
	update_post_meta($post_id, '_idc_area', $data['area']);
	update_post_meta($post_id, '_idc_mensagem', $data['mensagem']);
	update_post_meta($post_id, '_idc_lgpd', !empty($data['lgpd']) ? 1 : 0);
	update_post_meta($post_id, '_idc_crm_status', function_exists('idc_crm_default_status') ? idc_crm_default_status('idc_curriculo') : 'novo');

	$attach_id = (int) ($data['attachment_id'] ?? 0);
	if ($attach_id > 0) {
		update_post_meta($post_id, '_idc_curriculo_id', $attach_id);
		wp_update_post(
			[
				'ID'          => $attach_id,
				'post_parent' => $post_id,
			]
		);
	}

	return (int) $post_id;
}

/**
 * Contagem de envios não lidos (sem meta _idc_lido).
 */
function idc_form_unread_count_meta(string $post_type): int {
	$q = new WP_Query([
		'post_type'              => $post_type,
		'post_status'            => ['private', 'publish'],
		'posts_per_page'         => 1,
		'fields'                 => 'ids',
		'no_found_rows'          => false,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
		'meta_query'             => [
			[
				'key'     => '_idc_lido',
				'compare' => 'NOT EXISTS',
			],
		],
	]);
	return (int) $q->found_posts;
}

/**
 * Badge no menu Contatos / Currículos.
 */
function idc_form_menu_badges_meta(): void {
	global $menu;
	if (!is_array($menu)) {
		return;
	}

	$counts = [
		'edit.php?post_type=idc_contato'   => idc_form_unread_count_meta('idc_contato'),
		'edit.php?post_type=idc_curriculo' => idc_form_unread_count_meta('idc_curriculo'),
	];

	foreach ($menu as $i => $item) {
		if (empty($item[2]) || !isset($counts[$item[2]])) {
			continue;
		}
		$menu[$i][0] = preg_replace('/\s*<span class="awaiting-mod">.*?<\/span>/', '', (string) $menu[$i][0]);
		$n           = $counts[$item[2]];
		if ($n < 1) {
			continue;
		}
		$menu[$i][0] .= sprintf(
			' <span class="awaiting-mod"><span class="pending-count">%d</span></span>',
			$n
		);
	}
}
add_action('admin_menu', 'idc_form_menu_badges_meta', 9999);

/**
 * Ao abrir um envio no editor, marca como lido.
 */
function idc_form_mark_read_on_edit(): void {
	$screen = function_exists('get_current_screen') ? get_current_screen() : null;
	if (!$screen || $screen->base !== 'post') {
		return;
	}
	if (!in_array($screen->post_type, ['idc_contato', 'idc_curriculo'], true)) {
		return;
	}
	$post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
	if ($post_id < 1) {
		return;
	}
	update_post_meta($post_id, '_idc_lido', 1);
}
add_action('current_screen', 'idc_form_mark_read_on_edit');

/**
 * Widget no Painel WP.
 */
function idc_form_dashboard_widget(): void {
	wp_add_dashboard_widget(
		'idc_form_inbox',
		__('IDC — CRM Contatos e Currículos', 'instituto-dr-chao'),
		'idc_form_dashboard_widget_render'
	);
}
add_action('wp_dashboard_setup', 'idc_form_dashboard_widget');

/**
 * Conteúdo do widget.
 */
function idc_form_dashboard_widget_render(): void {
	$c = function_exists('idc_crm_count_by_status')
		? idc_crm_count_by_status('idc_contato', 'novo')
		: idc_form_unread_count_meta('idc_contato');
	$v = function_exists('idc_crm_count_by_status')
		? idc_crm_count_by_status('idc_curriculo', 'novo')
		: idc_form_unread_count_meta('idc_curriculo');

	echo '<p>';
	printf(
		esc_html(_n('%d contato novo', '%d contatos novos', $c, 'instituto-dr-chao')),
		$c
	);
	echo ' — <a href="' . esc_url(admin_url('edit.php?post_type=idc_contato&page=idc-crm-kanban-idc_contato')) . '">' . esc_html__('Kanban Contatos', 'instituto-dr-chao') . '</a></p>';
	echo '<p>';
	printf(
		esc_html(_n('%d currículo novo', '%d currículos novos', $v, 'instituto-dr-chao')),
		$v
	);
	echo ' — <a href="' . esc_url(admin_url('edit.php?post_type=idc_curriculo&page=idc-crm-kanban-idc_curriculo')) . '">' . esc_html__('Kanban Currículos', 'instituto-dr-chao') . '</a></p>';
}
