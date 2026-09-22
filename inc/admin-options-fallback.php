<?php
/**
 * Fallback de IDC Opções quando ACF PRO (Options Page) não está disponível.
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * ACF Options Page disponível?
 */
function idc_has_acf_options_page(): bool {
	return function_exists('acf_add_options_page');
}

/**
 * Lê settings nativos (fallback).
 *
 * @return array<string, mixed>
 */
function idc_native_settings(): array {
	$stored = get_option('idc_theme_settings', []);
	return is_array($stored) ? $stored : [];
}

/**
 * Valor nativo por chave.
 *
 * @param mixed $default
 * @return mixed
 */
function idc_native_setting(string $key, $default = '') {
	$all = idc_native_settings();
	if (!array_key_exists($key, $all)) {
		return $default;
	}
	$value = $all[$key];
	return ($value === null || $value === '') ? $default : $value;
}

/**
 * Registra menu fallback.
 */
function idc_register_native_options_menu(): void {
	if (idc_has_acf_options_page()) {
		return;
	}

	add_menu_page(
		__('Instituto Dr. Chao', 'instituto-dr-chao'),
		__('IDC Opções', 'instituto-dr-chao'),
		'edit_theme_options',
		'idc-opcoes',
		'idc_render_native_options_page',
		'dashicons-admin-generic',
		58
	);
}
add_action('admin_menu', 'idc_register_native_options_menu');

/**
 * Campos do formulário nativo.
 *
 * @return array<string, array{label:string,type:string,section:string,default?:mixed,options?:array<string,string>}>
 */
function idc_native_option_fields(): array {
	return [
		'idc_email'            => [
			'label'   => __('E-mail de atendimento', 'instituto-dr-chao'),
			'type'    => 'email',
			'section' => 'contato',
			'default' => 'atendimento@institutodrchao.com.br',
		],
		'idc_telefone'         => [
			'label'   => __('Telefone', 'instituto-dr-chao'),
			'type'    => 'text',
			'section' => 'contato',
			'default' => '(11) 2218-8080',
		],
		'idc_whatsapp'         => [
			'label'   => __('WhatsApp (DDI+DDD+número)', 'instituto-dr-chao'),
			'type'    => 'text',
			'section' => 'contato',
			'default' => '5511943356377',
		],
		'idc_endereco'         => [
			'label'   => __('Endereço', 'instituto-dr-chao'),
			'type'    => 'textarea',
			'section' => 'contato',
			'default' => "Rua Maria Cândida, 1.788\nVila Guilherme — São Paulo, SP\nCEP 02071-003",
		],
		'idc_horario'          => [
			'label'   => __('Horário', 'instituto-dr-chao'),
			'type'    => 'text',
			'section' => 'contato',
			'default' => 'Seg. à Sex. das 08h às 18h',
		],
		'idc_smtp_enabled'     => [
			'label'   => __('Usar SMTP', 'instituto-dr-chao'),
			'type'    => 'checkbox',
			'section' => 'smtp',
			'default' => 0,
		],
		'idc_smtp_host'        => [
			'label'   => __('SMTP Host', 'instituto-dr-chao'),
			'type'    => 'text',
			'section' => 'smtp',
			'default' => '',
		],
		'idc_smtp_port'        => [
			'label'   => __('Porta', 'instituto-dr-chao'),
			'type'    => 'number',
			'section' => 'smtp',
			'default' => 587,
		],
		'idc_smtp_encryption'  => [
			'label'   => __('Criptografia', 'instituto-dr-chao'),
			'type'    => 'select',
			'section' => 'smtp',
			'default' => 'tls',
			'options' => [
				'tls'  => 'TLS',
				'ssl'  => 'SSL',
				'none' => __('Nenhuma', 'instituto-dr-chao'),
			],
		],
		'idc_smtp_user'        => [
			'label'   => __('Usuário SMTP', 'instituto-dr-chao'),
			'type'    => 'text',
			'section' => 'smtp',
			'default' => '',
		],
		'idc_smtp_pass'        => [
			'label'   => __('Senha SMTP', 'instituto-dr-chao'),
			'type'    => 'password',
			'section' => 'smtp',
			'default' => '',
		],
		'idc_smtp_from_email'  => [
			'label'   => __('From (e-mail)', 'instituto-dr-chao'),
			'type'    => 'email',
			'section' => 'smtp',
			'default' => '',
		],
		'idc_smtp_from_name'   => [
			'label'   => __('From (nome)', 'instituto-dr-chao'),
			'type'    => 'text',
			'section' => 'smtp',
			'default' => '',
		],
	];
}

/**
 * Salva POST do formulário nativo.
 */
function idc_save_native_options(): void {
	if (!isset($_POST['idc_native_options_nonce']) || !current_user_can('edit_theme_options')) {
		return;
	}
	if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['idc_native_options_nonce'])), 'idc_native_options')) {
		return;
	}

	$current = idc_native_settings();
	$fields  = idc_native_option_fields();

	foreach ($fields as $key => $field) {
		$type = $field['type'];
		if ($type === 'checkbox') {
			$current[$key] = !empty($_POST[$key]) ? 1 : 0;
			continue;
		}

		$raw = isset($_POST[$key]) ? wp_unslash($_POST[$key]) : '';
		if ($type === 'textarea') {
			$current[$key] = sanitize_textarea_field((string) $raw);
		} elseif ($type === 'email') {
			$current[$key] = sanitize_email((string) $raw);
		} elseif ($type === 'number') {
			$current[$key] = (int) $raw;
		} elseif ($type === 'password') {
			$pass = (string) $raw;
			// Mantém senha anterior se o campo vier vazio.
			if ($pass !== '') {
				$current[$key] = $pass;
			} elseif (!isset($current[$key])) {
				$current[$key] = '';
			}
		} else {
			$current[$key] = sanitize_text_field((string) $raw);
		}
	}

	update_option('idc_theme_settings', $current, false);

	wp_safe_redirect(add_query_arg(['page' => 'idc-opcoes', 'updated' => '1'], admin_url('admin.php')));
	exit;
}
add_action('admin_post_idc_save_native_options', 'idc_save_native_options');

/**
 * Render da página.
 */
function idc_render_native_options_page(): void {
	if (!current_user_can('edit_theme_options')) {
		wp_die(esc_html__('Sem permissão.', 'instituto-dr-chao'));
	}

	$fields   = idc_native_option_fields();
	$settings = idc_native_settings();

	echo '<div class="wrap">';
	echo '<h1>' . esc_html__('IDC Opções', 'instituto-dr-chao') . '</h1>';
	echo '<p>' . esc_html__('ACF Free detectado: esta tela nativa cobre Contato e SMTP. Conteúdo da Home continua em Páginas → Início (campos ACF).', 'instituto-dr-chao') . '</p>';

	if (!empty($_GET['updated'])) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Configurações salvas.', 'instituto-dr-chao') . '</p></div>';
	}

	echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
	echo '<input type="hidden" name="action" value="idc_save_native_options">';
	wp_nonce_field('idc_native_options', 'idc_native_options_nonce');

	$sections = [
		'contato' => __('Contato', 'instituto-dr-chao'),
		'smtp'    => __('E-mail / SMTP', 'instituto-dr-chao'),
	];

	foreach ($sections as $section_key => $section_label) {
		echo '<h2>' . esc_html($section_label) . '</h2>';
		echo '<table class="form-table" role="presentation"><tbody>';

		foreach ($fields as $key => $field) {
			if ($field['section'] !== $section_key) {
				continue;
			}
			$value = array_key_exists($key, $settings) ? $settings[$key] : ($field['default'] ?? '');
			echo '<tr><th scope="row"><label for="' . esc_attr($key) . '">' . esc_html($field['label']) . '</label></th><td>';

			if ($field['type'] === 'textarea') {
				echo '<textarea class="large-text" rows="4" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '">' . esc_textarea((string) $value) . '</textarea>';
			} elseif ($field['type'] === 'checkbox') {
				echo '<label><input type="checkbox" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="1" ' . checked(!empty($value), true, false) . '> ' . esc_html__('Ativar', 'instituto-dr-chao') . '</label>';
			} elseif ($field['type'] === 'select') {
				echo '<select id="' . esc_attr($key) . '" name="' . esc_attr($key) . '">';
				foreach (($field['options'] ?? []) as $opt_val => $opt_label) {
					echo '<option value="' . esc_attr($opt_val) . '" ' . selected((string) $value, (string) $opt_val, false) . '>' . esc_html($opt_label) . '</option>';
				}
				echo '</select>';
			} elseif ($field['type'] === 'password') {
				echo '<input class="regular-text" type="password" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="" autocomplete="new-password" placeholder="' . esc_attr__('Deixe em branco para manter', 'instituto-dr-chao') . '">';
			} else {
				$input_type = $field['type'] === 'number' ? 'number' : ($field['type'] === 'email' ? 'email' : 'text');
				echo '<input class="regular-text" type="' . esc_attr($input_type) . '" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr((string) $value) . '">';
			}

			echo '</td></tr>';
		}

		echo '</tbody></table>';
	}

	submit_button(__('Salvar configurações', 'instituto-dr-chao'));
	echo '</form>';

	$test_url = wp_nonce_url(
		add_query_arg(['idc_smtp_test' => '1'], admin_url('admin.php?page=idc-opcoes')),
		'idc_smtp_test'
	);
	echo '<p><a class="button" href="' . esc_url($test_url) . '">' . esc_html__('Enviar e-mail de teste SMTP', 'instituto-dr-chao') . '</a></p>';
	echo '</div>';
}
