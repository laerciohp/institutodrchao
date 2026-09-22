<?php
/**
 * CRM básico — Kanban Contatos / Currículos.
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Post types do CRM.
 *
 * @return list<string>
 */
function idc_crm_post_types(): array {
	return ['idc_contato', 'idc_curriculo'];
}

/**
 * Colunas do Kanban por CPT.
 *
 * @return array<string, string> slug => label
 */
function idc_crm_statuses(string $post_type): array {
	if ($post_type === 'idc_curriculo') {
		return [
			'novo'       => __('Novo', 'instituto-dr-chao'),
			'triagem'    => __('Triagem', 'instituto-dr-chao'),
			'entrevista' => __('Entrevista', 'instituto-dr-chao'),
			'contratado' => __('Contratado', 'instituto-dr-chao'),
			'arquivado'  => __('Arquivado', 'instituto-dr-chao'),
		];
	}

	return [
		'novo'         => __('Novo', 'instituto-dr-chao'),
		'em_contato'   => __('Em contato', 'instituto-dr-chao'),
		'em_andamento' => __('Em andamento', 'instituto-dr-chao'),
		'convertido'   => __('Convertido', 'instituto-dr-chao'),
		'arquivado'    => __('Arquivado', 'instituto-dr-chao'),
	];
}

/**
 * Status padrão.
 */
function idc_crm_default_status(string $post_type = 'idc_contato'): string {
	$statuses = idc_crm_statuses($post_type);
	return array_key_first($statuses) ?: 'novo';
}

/**
 * Lê status do card (migra legado sem meta → novo).
 */
function idc_crm_get_status(int $post_id): string {
	$post = get_post($post_id);
	if (!$post || !in_array($post->post_type, idc_crm_post_types(), true)) {
		return 'novo';
	}

	$status = (string) get_post_meta($post_id, '_idc_crm_status', true);
	$allowed = idc_crm_statuses($post->post_type);

	if ($status === '' || !isset($allowed[$status])) {
		$status = idc_crm_default_status($post->post_type);
		update_post_meta($post_id, '_idc_crm_status', $status);
	}

	return $status;
}

/**
 * Define status do card.
 */
function idc_crm_set_status(int $post_id, string $status): bool {
	$post = get_post($post_id);
	if (!$post || !in_array($post->post_type, idc_crm_post_types(), true)) {
		return false;
	}
	if (!isset(idc_crm_statuses($post->post_type)[$status])) {
		return false;
	}
	update_post_meta($post_id, '_idc_crm_status', $status);
	if ($status !== 'novo') {
		update_post_meta($post_id, '_idc_lido', 1);
	}
	return true;
}

/**
 * Contagem por status (badge “novos”).
 */
function idc_crm_count_by_status(string $post_type, string $status = 'novo'): int {
	$meta_query = [
		[
			'key'   => '_idc_crm_status',
			'value' => $status,
		],
	];

	// Legado sem meta conta como “novo”.
	if ($status === idc_crm_default_status($post_type)) {
		$meta_query = [
			'relation' => 'OR',
			[
				'key'   => '_idc_crm_status',
				'value' => $status,
			],
			[
				'key'     => '_idc_crm_status',
				'compare' => 'NOT EXISTS',
			],
		];
	}

	$q = new WP_Query([
		'post_type'              => $post_type,
		'post_status'            => ['private', 'publish'],
		'posts_per_page'         => 1,
		'fields'                 => 'ids',
		'no_found_rows'          => false,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
		'meta_query'             => $meta_query,
	]);
	return (int) $q->found_posts;
}

/**
 * Cards do Kanban agrupados por status.
 *
 * @return array<string, list<WP_Post>>
 */
function idc_crm_board_posts(string $post_type): array {
	$statuses = idc_crm_statuses($post_type);
	$board    = [];
	foreach (array_keys($statuses) as $slug) {
		$board[$slug] = [];
	}

	$q = new WP_Query([
		'post_type'              => $post_type,
		'post_status'            => ['private', 'publish'],
		'posts_per_page'         => 200,
		'orderby'                => 'date',
		'order'                  => 'DESC',
		'no_found_rows'          => true,
		'update_post_term_cache' => false,
	]);

	foreach ($q->posts as $post) {
		$status = idc_crm_get_status((int) $post->ID);
		if (!isset($board[$status])) {
			$status = idc_crm_default_status($post_type);
		}
		$board[$status][] = $post;
	}

	return $board;
}

/**
 * Submenus Kanban + reativa “Adicionar”.
 */
function idc_crm_admin_menus(): void {
	foreach (idc_crm_post_types() as $pt) {
		$parent = 'edit.php?post_type=' . $pt;
		$title  = $pt === 'idc_curriculo'
			? __('Kanban Currículos', 'instituto-dr-chao')
			: __('Kanban Contatos', 'instituto-dr-chao');

		add_submenu_page(
			$parent,
			$title,
			__('Kanban', 'instituto-dr-chao'),
			'edit_posts',
			'idc-crm-kanban-' . $pt,
			static function () use ($pt): void {
				idc_crm_render_kanban_page($pt);
			}
		);
	}
}
add_action('admin_menu', 'idc_crm_admin_menus', 20);

/**
 * Assets do CRM no admin.
 */
function idc_crm_admin_assets(string $hook): void {
	$is_kanban = isset($_GET['page']) && str_starts_with((string) $_GET['page'], 'idc-crm-kanban-');
	$screen    = function_exists('get_current_screen') ? get_current_screen() : null;
	$is_edit   = $screen && in_array($screen->post_type ?? '', idc_crm_post_types(), true);

	if (!$is_kanban && !$is_edit) {
		return;
	}

	wp_enqueue_style(
		'idc-admin-crm',
		idc_asset('assets/css/admin-crm.css'),
		[],
		IDC_THEME_VERSION
	);

	if ($is_kanban) {
		wp_enqueue_script(
			'idc-admin-crm',
			idc_asset('assets/js/admin-crm.js'),
			[],
			IDC_THEME_VERSION,
			true
		);
		wp_localize_script(
			'idc-admin-crm',
			'idcCrm',
			[
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'nonce'   => wp_create_nonce('idc_crm'),
				'i18n'    => [
					'error'   => __('Não foi possível atualizar. Tente novamente.', 'instituto-dr-chao'),
					'confirm' => __('Mover este card para a lixeira?', 'instituto-dr-chao'),
				],
			]
		);
	}
}
add_action('admin_enqueue_scripts', 'idc_crm_admin_assets');

/**
 * Página Kanban.
 */
function idc_crm_render_kanban_page(string $post_type): void {
	if (!current_user_can('edit_posts')) {
		wp_die(esc_html__('Sem permissão.', 'instituto-dr-chao'));
	}

	$statuses = idc_crm_statuses($post_type);
	$board    = idc_crm_board_posts($post_type);
	$label    = $post_type === 'idc_curriculo'
		? __('Currículos', 'instituto-dr-chao')
		: __('Contatos', 'instituto-dr-chao');
	$new_url  = admin_url('post-new.php?post_type=' . $post_type);
	$list_url = admin_url('edit.php?post_type=' . $post_type);

	echo '<div class="wrap idc-crm">';
	echo '<h1 class="wp-heading-inline">' . esc_html(sprintf(/* translators: %s: Contatos|Currículos */ __('CRM — %s', 'instituto-dr-chao'), $label)) . '</h1>';
	echo ' <a href="' . esc_url($new_url) . '" class="page-title-action">' . esc_html__('Adicionar', 'instituto-dr-chao') . '</a>';
	echo ' <a href="' . esc_url($list_url) . '" class="page-title-action">' . esc_html__('Lista', 'instituto-dr-chao') . '</a>';
	echo '<hr class="wp-header-end">';
	echo '<p class="idc-crm__hint">' . esc_html__('Arraste os cards entre as colunas para atualizar o status. Clique no nome para editar.', 'instituto-dr-chao') . '</p>';

	echo '<div class="idc-crm-board" data-post-type="' . esc_attr($post_type) . '">';
	foreach ($statuses as $slug => $status_label) {
		$cards = $board[$slug] ?? [];
		echo '<section class="idc-crm-col" data-status="' . esc_attr($slug) . '">';
		echo '<header class="idc-crm-col__head"><h2>' . esc_html($status_label) . '</h2>';
		echo '<span class="idc-crm-col__count">' . esc_html((string) count($cards)) . '</span></header>';
		echo '<div class="idc-crm-col__list" data-dropzone="1">';
		foreach ($cards as $post) {
			idc_crm_render_card($post);
		}
		echo '</div></section>';
	}
	echo '</div></div>';
}

/**
 * Card do Kanban.
 */
function idc_crm_render_card(WP_Post $post): void {
	$nome     = (string) get_post_meta($post->ID, '_idc_nome', true) ?: $post->post_title;
	$email    = (string) get_post_meta($post->ID, '_idc_email', true);
	$telefone = (string) get_post_meta($post->ID, '_idc_telefone', true);
	$extra    = $post->post_type === 'idc_curriculo'
		? (string) get_post_meta($post->ID, '_idc_area', true)
		: (string) get_post_meta($post->ID, '_idc_assunto', true);
	$edit     = get_edit_post_link($post->ID, 'raw') ?: '';
	$date     = get_the_date('d/m/Y H:i', $post);

	echo '<article class="idc-crm-card" draggable="true" data-id="' . esc_attr((string) $post->ID) . '">';
	echo '<h3 class="idc-crm-card__title">';
	if ($edit) {
		echo '<a href="' . esc_url($edit) . '">' . esc_html($nome) . '</a>';
	} else {
		echo esc_html($nome);
	}
	echo '</h3>';
	if ($extra !== '') {
		echo '<p class="idc-crm-card__meta">' . esc_html($extra) . '</p>';
	}
	if ($email !== '') {
		echo '<p class="idc-crm-card__meta"><a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></p>';
	}
	if ($telefone !== '') {
		echo '<p class="idc-crm-card__meta">' . esc_html($telefone) . '</p>';
	}
	echo '<footer class="idc-crm-card__foot">';
	echo '<time>' . esc_html($date) . '</time>';
	echo '<span class="idc-crm-card__actions">';
	if ($edit) {
		echo '<a class="button button-small" href="' . esc_url($edit) . '">' . esc_html__('Editar', 'instituto-dr-chao') . '</a> ';
	}
	echo '<button type="button" class="button button-small idc-crm-card__trash" data-id="' . esc_attr((string) $post->ID) . '">' . esc_html__('Lixeira', 'instituto-dr-chao') . '</button>';
	echo '</span></footer></article>';
}

/**
 * AJAX: mover status.
 */
function idc_crm_ajax_set_status(): void {
	if (!current_user_can('edit_posts')) {
		wp_send_json_error(['message' => 'forbidden'], 403);
	}
	check_ajax_referer('idc_crm', 'nonce');

	$post_id = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
	$status  = isset($_POST['status']) ? sanitize_key((string) wp_unslash($_POST['status'])) : '';

	if ($post_id < 1 || $status === '' || !idc_crm_set_status($post_id, $status)) {
		wp_send_json_error(['message' => 'invalid'], 400);
	}

	wp_send_json_success([
		'post_id' => $post_id,
		'status'  => $status,
	]);
}
add_action('wp_ajax_idc_crm_set_status', 'idc_crm_ajax_set_status');

/**
 * AJAX: lixeira.
 */
function idc_crm_ajax_trash(): void {
	if (!current_user_can('delete_posts')) {
		wp_send_json_error(['message' => 'forbidden'], 403);
	}
	check_ajax_referer('idc_crm', 'nonce');

	$post_id = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
	$post    = get_post($post_id);
	if (!$post || !in_array($post->post_type, idc_crm_post_types(), true)) {
		wp_send_json_error(['message' => 'invalid'], 400);
	}
	if (!current_user_can('delete_post', $post_id)) {
		wp_send_json_error(['message' => 'forbidden'], 403);
	}

	$result = wp_trash_post($post_id);
	if (!$result) {
		wp_send_json_error(['message' => 'fail'], 500);
	}

	wp_send_json_success(['post_id' => $post_id]);
}
add_action('wp_ajax_idc_crm_trash', 'idc_crm_ajax_trash');

/**
 * Meta box CRM editável (substitui só-leitura).
 */
function idc_crm_replace_metaboxes(): void {
	remove_meta_box('idc_contato_dados', 'idc_contato', 'normal');
	remove_meta_box('idc_curriculo_dados', 'idc_curriculo', 'normal');

	add_meta_box(
		'idc_crm_contato',
		__('CRM Contato', 'instituto-dr-chao'),
		'idc_crm_contato_metabox',
		'idc_contato',
		'normal',
		'high'
	);
	add_meta_box(
		'idc_crm_curriculo',
		__('CRM Currículo', 'instituto-dr-chao'),
		'idc_crm_curriculo_metabox',
		'idc_curriculo',
		'normal',
		'high'
	);
}
add_action('add_meta_boxes', 'idc_crm_replace_metaboxes', 20);

/**
 * Campos comuns do metabox.
 *
 * @param array<string, mixed> $extra
 */
function idc_crm_metabox_fields(WP_Post $post, array $extra = []): void {
	wp_nonce_field('idc_crm_save', 'idc_crm_nonce');

	$nome       = (string) get_post_meta($post->ID, '_idc_nome', true);
	$email      = (string) get_post_meta($post->ID, '_idc_email', true);
	$telefone   = (string) get_post_meta($post->ID, '_idc_telefone', true);
	$mensagem   = (string) get_post_meta($post->ID, '_idc_mensagem', true);
	$notas      = (string) get_post_meta($post->ID, '_idc_crm_notas', true);
	$resp       = (string) get_post_meta($post->ID, '_idc_crm_responsavel', true);
	$status     = idc_crm_get_status((int) $post->ID);
	$statuses   = idc_crm_statuses($post->post_type);
	$lgpd       = (bool) get_post_meta($post->ID, '_idc_lgpd', true);

	echo '<table class="form-table idc-crm-metabox"><tbody>';

	idc_crm_field_row('idc_crm_nome', __('Nome', 'instituto-dr-chao'), $nome);
	idc_crm_field_row('idc_crm_email', __('E-mail', 'instituto-dr-chao'), $email, 'email');
	idc_crm_field_row('idc_crm_telefone', __('Telefone', 'instituto-dr-chao'), $telefone);

	foreach ($extra as $key => $field) {
		$type = $field['type'] ?? 'text';
		if ($type === 'textarea') {
			echo '<tr><th><label for="' . esc_attr($key) . '">' . esc_html((string) $field['label']) . '</label></th><td>';
			echo '<textarea class="large-text" rows="3" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '">' . esc_textarea((string) ($field['value'] ?? '')) . '</textarea>';
			echo '</td></tr>';
		} elseif ($type === 'html') {
			echo '<tr><th>' . esc_html((string) $field['label']) . '</th><td>' . $field['value'] . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			idc_crm_field_row($key, (string) $field['label'], (string) ($field['value'] ?? ''), $type);
		}
	}

	echo '<tr><th><label for="idc_crm_mensagem">' . esc_html__('Mensagem', 'instituto-dr-chao') . '</label></th><td>';
	echo '<textarea class="large-text" rows="4" id="idc_crm_mensagem" name="idc_crm_mensagem">' . esc_textarea($mensagem) . '</textarea></td></tr>';

	echo '<tr><th><label for="idc_crm_status">' . esc_html__('Status (Kanban)', 'instituto-dr-chao') . '</label></th><td>';
	echo '<select id="idc_crm_status" name="idc_crm_status">';
	foreach ($statuses as $slug => $label) {
		echo '<option value="' . esc_attr($slug) . '" ' . selected($status, $slug, false) . '>' . esc_html($label) . '</option>';
	}
	echo '</select></td></tr>';

	idc_crm_field_row('idc_crm_responsavel', __('Responsável', 'instituto-dr-chao'), $resp);

	echo '<tr><th><label for="idc_crm_notas">' . esc_html__('Notas internas', 'instituto-dr-chao') . '</label></th><td>';
	echo '<textarea class="large-text" rows="4" id="idc_crm_notas" name="idc_crm_notas">' . esc_textarea($notas) . '</textarea></td></tr>';

	echo '<tr><th>' . esc_html__('LGPD', 'instituto-dr-chao') . '</th><td>';
	echo '<label><input type="checkbox" name="idc_crm_lgpd" value="1" ' . checked($lgpd, true, false) . '> ' . esc_html__('Aceite registrado', 'instituto-dr-chao') . '</label>';
	echo '</td></tr>';

	echo '</tbody></table>';

	$kanban = admin_url('edit.php?post_type=' . $post->post_type . '&page=idc-crm-kanban-' . $post->post_type);
	echo '<p><a class="button" href="' . esc_url($kanban) . '">' . esc_html__('Abrir Kanban', 'instituto-dr-chao') . '</a></p>';
}

/**
 * @param string $name
 * @param string $label
 * @param string $value
 * @param string $type
 */
function idc_crm_field_row(string $name, string $label, string $value, string $type = 'text'): void {
	echo '<tr><th><label for="' . esc_attr($name) . '">' . esc_html($label) . '</label></th><td>';
	echo '<input class="regular-text" type="' . esc_attr($type) . '" id="' . esc_attr($name) . '" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '">';
	echo '</td></tr>';
}

/**
 * Metabox Contato.
 */
function idc_crm_contato_metabox(WP_Post $post): void {
	idc_crm_metabox_fields(
		$post,
		[
			'idc_crm_assunto' => [
				'label' => __('Assunto', 'instituto-dr-chao'),
				'value' => (string) get_post_meta($post->ID, '_idc_assunto', true),
			],
		]
	);
}

/**
 * Metabox Currículo.
 */
function idc_crm_curriculo_metabox(WP_Post $post): void {
	$attach_id = (int) get_post_meta($post->ID, '_idc_curriculo_id', true);
	$cv_html   = '—';
	if ($attach_id > 0) {
		$url = wp_get_attachment_url($attach_id);
		if ($url) {
			$cv_html = '<a href="' . esc_url($url) . '" target="_blank" rel="noopener">' . esc_html(basename($url)) . '</a>';
		}
	}

	idc_crm_metabox_fields(
		$post,
		[
			'idc_crm_area' => [
				'label' => __('Área / vaga', 'instituto-dr-chao'),
				'value' => (string) get_post_meta($post->ID, '_idc_area', true),
			],
			'idc_crm_cv'   => [
				'label' => __('Arquivo', 'instituto-dr-chao'),
				'type'  => 'html',
				'value' => $cv_html,
			],
		]
	);
}

/**
 * Salva metabox CRM.
 */
function idc_crm_save_post(int $post_id, WP_Post $post): void {
	if (!in_array($post->post_type, idc_crm_post_types(), true)) {
		return;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	if (!isset($_POST['idc_crm_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['idc_crm_nonce'])), 'idc_crm_save')) {
		return;
	}
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	$nome = isset($_POST['idc_crm_nome']) ? sanitize_text_field(wp_unslash((string) $_POST['idc_crm_nome'])) : '';
	$email = isset($_POST['idc_crm_email']) ? sanitize_email(wp_unslash((string) $_POST['idc_crm_email'])) : '';
	$telefone = isset($_POST['idc_crm_telefone']) ? sanitize_text_field(wp_unslash((string) $_POST['idc_crm_telefone'])) : '';
	$mensagem = isset($_POST['idc_crm_mensagem']) ? sanitize_textarea_field(wp_unslash((string) $_POST['idc_crm_mensagem'])) : '';
	$notas = isset($_POST['idc_crm_notas']) ? sanitize_textarea_field(wp_unslash((string) $_POST['idc_crm_notas'])) : '';
	$resp = isset($_POST['idc_crm_responsavel']) ? sanitize_text_field(wp_unslash((string) $_POST['idc_crm_responsavel'])) : '';
	$status = isset($_POST['idc_crm_status']) ? sanitize_key((string) wp_unslash($_POST['idc_crm_status'])) : idc_crm_default_status($post->post_type);
	$lgpd = !empty($_POST['idc_crm_lgpd']);

	update_post_meta($post_id, '_idc_nome', $nome);
	update_post_meta($post_id, '_idc_email', $email);
	update_post_meta($post_id, '_idc_telefone', $telefone);
	update_post_meta($post_id, '_idc_mensagem', $mensagem);
	update_post_meta($post_id, '_idc_crm_notas', $notas);
	update_post_meta($post_id, '_idc_crm_responsavel', $resp);
	update_post_meta($post_id, '_idc_lgpd', $lgpd ? 1 : 0);
	idc_crm_set_status($post_id, $status);

	if ($post->post_type === 'idc_contato') {
		$assunto = isset($_POST['idc_crm_assunto']) ? sanitize_text_field(wp_unslash((string) $_POST['idc_crm_assunto'])) : '';
		update_post_meta($post_id, '_idc_assunto', $assunto);
	}
	if ($post->post_type === 'idc_curriculo') {
		$area = isset($_POST['idc_crm_area']) ? sanitize_text_field(wp_unslash((string) $_POST['idc_crm_area'])) : '';
		update_post_meta($post_id, '_idc_area', $area);
	}

	if ($nome !== '' && $nome !== $post->post_title) {
		remove_action('save_post', 'idc_crm_save_post', 20);
		wp_update_post(
			[
				'ID'         => $post_id,
				'post_title' => $nome,
			]
		);
		add_action('save_post', 'idc_crm_save_post', 20, 2);
	}

	// Inbox: mantém privado (visível no Kanban, fora do front público).
	if (in_array($post->post_status, ['draft', 'pending', 'publish'], true)) {
		remove_action('save_post', 'idc_crm_save_post', 20);
		wp_update_post(
			[
				'ID'          => $post_id,
				'post_status' => 'private',
			]
		);
		add_action('save_post', 'idc_crm_save_post', 20, 2);
	}
}
add_action('save_post', 'idc_crm_save_post', 20, 2);

/**
 * Default status em posts novos no admin.
 */
function idc_crm_default_on_new(int $post_id, WP_Post $post, bool $update): void {
	if ($update || !in_array($post->post_type, idc_crm_post_types(), true)) {
		return;
	}
	if (get_post_meta($post_id, '_idc_crm_status', true) === '') {
		update_post_meta($post_id, '_idc_crm_status', idc_crm_default_status($post->post_type));
	}
}
add_action('wp_insert_post', 'idc_crm_default_on_new', 10, 3);

/**
 * Coluna Status nas listas.
 *
 * @param array<string, string> $columns
 * @return array<string, string>
 */
function idc_crm_list_columns(array $columns): array {
	$new = [];
	foreach ($columns as $key => $label) {
		$new[$key] = $label;
		if ($key === 'title') {
			$new['idc_crm_status'] = __('Status', 'instituto-dr-chao');
		}
	}
	return $new;
}
add_filter('manage_idc_contato_posts_columns', 'idc_crm_list_columns', 20);
add_filter('manage_idc_curriculo_posts_columns', 'idc_crm_list_columns', 20);

/**
 * Conteúdo coluna status.
 */
function idc_crm_list_column_content(string $column, int $post_id): void {
	if ($column !== 'idc_crm_status') {
		return;
	}
	$post = get_post($post_id);
	if (!$post) {
		return;
	}
	$status   = idc_crm_get_status($post_id);
	$statuses = idc_crm_statuses($post->post_type);
	$label    = $statuses[$status] ?? $status;
	echo '<span class="idc-crm-status-pill idc-crm-status-pill--' . esc_attr($status) . '">' . esc_html($label) . '</span>';
}
add_action('manage_idc_contato_posts_custom_column', 'idc_crm_list_column_content', 20, 2);
add_action('manage_idc_curriculo_posts_custom_column', 'idc_crm_list_column_content', 20, 2);

/**
 * Badge do menu: cards em “novo”.
 */
function idc_crm_menu_badges(): void {
	global $menu;
	if (!is_array($menu)) {
		return;
	}

	$counts = [
		'edit.php?post_type=idc_contato'   => idc_crm_count_by_status('idc_contato', 'novo'),
		'edit.php?post_type=idc_curriculo' => idc_crm_count_by_status('idc_curriculo', 'novo'),
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
add_action('admin_menu', 'idc_crm_menu_badges', 10000);

/**
 * Migração one-shot: posts sem status → novo.
 */
function idc_crm_maybe_migrate_statuses(): void {
	if (get_option('idc_crm_migrated_v1') === '1') {
		return;
	}
	if (!current_user_can('manage_options')) {
		return;
	}

	foreach (idc_crm_post_types() as $pt) {
		$q = new WP_Query([
			'post_type'      => $pt,
			'post_status'    => ['private', 'publish'],
			'posts_per_page' => 500,
			'fields'         => 'ids',
			'meta_query'     => [
				[
					'key'     => '_idc_crm_status',
					'compare' => 'NOT EXISTS',
				],
			],
		]);
		foreach ($q->posts as $id) {
			update_post_meta((int) $id, '_idc_crm_status', idc_crm_default_status($pt));
		}
	}

	update_option('idc_crm_migrated_v1', '1', false);
}
add_action('admin_init', 'idc_crm_maybe_migrate_statuses');
