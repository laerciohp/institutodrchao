<?php
/**
 * E-mail / SMTP — configura PHPMailer via IDC Opções.
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * SMTP habilitado?
 */
function idc_smtp_enabled(): bool {
	return (bool) idc_option('idc_smtp_enabled', false)
		&& (string) idc_option('idc_smtp_host', '') !== '';
}

/**
 * Remetente padrão (From).
 *
 * @return array{email:string,name:string}
 */
function idc_mail_from(): array {
	$email = (string) idc_option('idc_smtp_from_email', '');
	if (!is_email($email)) {
		$email = (string) idc_option('idc_email', get_option('admin_email'));
	}
	if (!is_email($email)) {
		$email = (string) get_option('admin_email');
	}

	$name = (string) idc_option('idc_smtp_from_name', get_bloginfo('name'));
	if ($name === '') {
		$name = get_bloginfo('name') ?: 'Instituto Dr. Chao';
	}

	return [
		'email' => $email,
		'name'  => $name,
	];
}

/**
 * Configura PHPMailer com SMTP das Options.
 *
 * @param PHPMailer\PHPMailer\PHPMailer $phpmailer
 */
function idc_phpmailer_smtp($phpmailer): void {
	if (!idc_smtp_enabled()) {
		return;
	}

	$host = (string) idc_option('idc_smtp_host', '');
	$port = (int) idc_option('idc_smtp_port', 587);
	$user = (string) idc_option('idc_smtp_user', '');
	$pass = (string) idc_option('idc_smtp_pass', '');
	$enc  = (string) idc_option('idc_smtp_encryption', 'tls');

	$phpmailer->isSMTP();
	$phpmailer->Host       = $host;
	$phpmailer->Port       = $port > 0 ? $port : 587;
	$phpmailer->SMTPAuth   = $user !== '';
	$phpmailer->Username   = $user;
	$phpmailer->Password   = $pass;
	$phpmailer->Timeout    = 20;

	if ($enc === 'ssl') {
		$phpmailer->SMTPSecure = 'ssl';
	} elseif ($enc === 'tls') {
		$phpmailer->SMTPSecure = 'tls';
	} else {
		$phpmailer->SMTPSecure = '';
		$phpmailer->SMTPAutoTLS = false;
	}

	$from = idc_mail_from();
	$phpmailer->setFrom($from['email'], $from['name'], false);
}
add_action('phpmailer_init', 'idc_phpmailer_smtp');

/**
 * From padrão mesmo sem SMTP (melhora deliverability).
 */
function idc_mail_from_email(string $email): string {
	$from = idc_mail_from();
	return is_email($from['email']) ? $from['email'] : $email;
}
add_filter('wp_mail_from', 'idc_mail_from_email');

/**
 * @param string $name
 */
function idc_mail_from_name(string $name): string {
	$from = idc_mail_from();
	return $from['name'] !== '' ? $from['name'] : $name;
}
add_filter('wp_mail_from_name', 'idc_mail_from_name');

/**
 * Envia e-mail de teste (admin).
 */
function idc_smtp_maybe_send_test(): void {
	if (!is_admin() || !current_user_can('manage_options')) {
		return;
	}
	if (empty($_GET['idc_smtp_test']) || !isset($_GET['_wpnonce'])) {
		return;
	}
	if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'idc_smtp_test')) {
		return;
	}

	$to = (string) idc_option('idc_email', get_option('admin_email'));
	if (!is_email($to)) {
		$to = (string) get_option('admin_email');
	}

	$ok = wp_mail(
		$to,
		'[IDC] Teste SMTP — ' . wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES),
		"Este é um e-mail de teste do tema Instituto Dr. Chao.\n\nSMTP ativo: " . (idc_smtp_enabled() ? 'sim' : 'não') . "\nHorário: " . gmdate('c') . "\n"
	);

	$redirect = add_query_arg(
		[
			'page'           => 'idc-opcoes',
			'idc_smtp_result' => $ok ? 'ok' : 'fail',
		],
		admin_url('admin.php')
	);
	wp_safe_redirect($redirect);
	exit;
}
add_action('admin_init', 'idc_smtp_maybe_send_test');

/**
 * Aviso do resultado do teste SMTP.
 */
function idc_smtp_admin_notice(): void {
	if (!isset($_GET['page'], $_GET['idc_smtp_result']) || $_GET['page'] !== 'idc-opcoes') {
		return;
	}
	$result = sanitize_key((string) $_GET['idc_smtp_result']);
	if ($result === 'ok') {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('E-mail de teste enviado. Verifique a caixa de entrada.', 'instituto-dr-chao') . '</p></div>';
	} elseif ($result === 'fail') {
		echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__('Falha ao enviar o teste. Confira host, porta, usuário, senha e criptografia SMTP.', 'instituto-dr-chao') . '</p></div>';
	}
}
add_action('admin_notices', 'idc_smtp_admin_notice');

/**
 * Link “Enviar teste” na página de opções (via notice contextual).
 */
function idc_smtp_options_help_notice(): void {
	if (!isset($_GET['page']) || $_GET['page'] !== 'idc-opcoes') {
		return;
	}
	if (!current_user_can('manage_options')) {
		return;
	}

	$url = wp_nonce_url(
		add_query_arg(['idc_smtp_test' => '1'], admin_url('admin.php?page=idc-opcoes')),
		'idc_smtp_test'
	);

	echo '<div class="notice notice-info"><p>';
	echo esc_html__('Após preencher a aba E-mail / SMTP, ', 'instituto-dr-chao');
	echo '<a href="' . esc_url($url) . '">' . esc_html__('envie um e-mail de teste', 'instituto-dr-chao') . '</a>';
	echo esc_html__(' para o endereço de atendimento.', 'instituto-dr-chao');
	echo '</p></div>';
}
add_action('admin_notices', 'idc_smtp_options_help_notice');
