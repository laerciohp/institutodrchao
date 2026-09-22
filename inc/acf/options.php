<?php
/**
 * ACF Options + field groups (requer plugin ACF).
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Página de opções do tema.
 */
function idc_register_acf_options(): void {
	if (!function_exists('acf_add_options_page')) {
		return;
	}

	acf_add_options_page([
		'page_title' => __('Instituto Dr. Chao', 'instituto-dr-chao'),
		'menu_title' => __('IDC Opções', 'instituto-dr-chao'),
		'menu_slug'  => 'idc-opcoes',
		'capability' => 'edit_theme_options',
		'redirect'   => false,
		'icon_url'   => 'dashicons-admin-generic',
		'position'   => 58,
	]);

	acf_add_options_sub_page([
		'page_title'  => __('Home', 'instituto-dr-chao'),
		'menu_title'  => __('Home', 'instituto-dr-chao'),
		'parent_slug' => 'idc-opcoes',
		'menu_slug'   => 'idc-opcoes-home',
	]);
}
add_action('acf/init', 'idc_register_acf_options');

/**
 * Field groups locais (sem JSON export — registráveis via PHP).
 */
function idc_register_acf_field_groups(): void {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key'    => 'group_idc_opcoes_gerais',
		'title'  => 'Dados da clínica',
		'fields' => [
			[
				'key'   => 'field_idc_telefone',
				'label' => 'Telefone',
				'name'  => 'idc_telefone',
				'type'  => 'text',
				'default_value' => '(11) 2218-8080',
			],
			[
				'key'   => 'field_idc_whatsapp',
				'label' => 'WhatsApp (com DDI)',
				'name'  => 'idc_whatsapp',
				'type'  => 'text',
				'instructions' => 'Ex.: 551122188080',
				'default_value' => '551122188080',
			],
			[
				'key'   => 'field_idc_whatsapp_mensagem',
				'label' => 'Mensagem padrão WhatsApp',
				'name'  => 'idc_whatsapp_mensagem',
				'type'  => 'textarea',
				'rows'  => 3,
			],
			[
				'key'   => 'field_idc_email',
				'label' => 'E-mail atendimento',
				'name'  => 'idc_email',
				'type'  => 'email',
				'default_value' => 'atendimento@institutodrchao.com.br',
			],
			[
				'key'   => 'field_idc_email_rh',
				'label' => 'E-mail RH / Carreiras',
				'name'  => 'idc_email_rh',
				'type'  => 'email',
				'default_value' => 'rh@institutodrchao.com.br',
			],
			[
				'key'   => 'field_idc_horario',
				'label' => 'Horário de atendimento',
				'name'  => 'idc_horario',
				'type'  => 'text',
				'default_value' => 'Seg. à Sex. das 08h às 18h',
			],
			[
				'key'   => 'field_idc_endereco',
				'label' => 'Endereço',
				'name'  => 'idc_endereco',
				'type'  => 'textarea',
				'rows'  => 3,
				'default_value' => "Rua Maria Cândida, 1.788\nVila Guilherme — São Paulo, SP\nCEP 02071-003",
			],
			[
				'key'   => 'field_idc_map_embed',
				'label' => 'URL embed do mapa (Google Maps)',
				'name'  => 'idc_map_embed',
				'type'  => 'url',
				'instructions' => 'URL com output=embed ou similar.',
			],
			[
				'key'   => 'field_idc_instagram',
				'label' => 'Instagram URL',
				'name'  => 'idc_instagram',
				'type'  => 'url',
			],
			[
				'key'   => 'field_idc_facebook',
				'label' => 'Facebook / Share URL',
				'name'  => 'idc_facebook',
				'type'  => 'url',
			],
			[
				'key'   => 'field_idc_scripts_head',
				'label' => 'Scripts no &lt;head&gt;',
				'name'  => 'idc_scripts_head',
				'type'  => 'textarea',
				'rows'  => 4,
			],
			[
				'key'   => 'field_idc_scripts_body',
				'label' => 'Scripts antes de &lt;/body&gt;',
				'name'  => 'idc_scripts_body',
				'type'  => 'textarea',
				'rows'  => 4,
			],
		],
		'location' => [
			[
				[
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'idc-opcoes',
				],
			],
		],
	]);

	acf_add_local_field_group([
		'key'    => 'group_idc_home_hero',
		'title'  => 'Home — Hero',
		'fields' => [
			[
				'key'   => 'field_idc_hero_eyebrow',
				'label' => 'Eyebrow',
				'name'  => 'idc_hero_eyebrow',
				'type'  => 'text',
				'default_value' => 'INSTITUTO DR. CHAO',
			],
			[
				'key'   => 'field_idc_hero_since',
				'label' => 'Desde',
				'name'  => 'idc_hero_since',
				'type'  => 'text',
				'default_value' => 'DESDE 1987',
			],
			[
				'key'   => 'field_idc_hero_title_before',
				'label' => 'Título (antes do destaque)',
				'name'  => 'idc_hero_title_before',
				'type'  => 'text',
				'default_value' => 'Tratamos a',
			],
			[
				'key'   => 'field_idc_hero_title_accent',
				'label' => 'Título (destaque serif)',
				'name'  => 'idc_hero_title_accent',
				'type'  => 'text',
				'default_value' => 'origem da dor.',
			],
			[
				'key'   => 'field_idc_hero_title_after',
				'label' => 'Título (depois do destaque)',
				'name'  => 'idc_hero_title_after',
				'type'  => 'text',
				'default_value' => 'Você sente a mudança.',
			],
			[
				'key'   => 'field_idc_hero_lead',
				'label' => 'Lead',
				'name'  => 'idc_hero_lead',
				'type'  => 'textarea',
				'rows'  => 4,
				'default_value' => "Existimos para que ninguém seja definido pela sua dor.\nCombinamos vanguarda médica e terapias integrativas em um ambiente pensado para a sua verdadeira recuperação e bem-estar contínuo.",
			],
			[
				'key'   => 'field_idc_hero_cta_primary',
				'label' => 'CTA primário',
				'name'  => 'idc_hero_cta_primary',
				'type'  => 'text',
				'default_value' => 'Agendar Consulta',
			],
			[
				'key'   => 'field_idc_hero_cta_secondary',
				'label' => 'CTA secundário',
				'name'  => 'idc_hero_cta_secondary',
				'type'  => 'text',
				'default_value' => 'Conheça os tratamentos',
			],
			[
				'key'           => 'field_idc_hero_cta_secondary_url',
				'label'         => 'URL CTA secundário',
				'name'          => 'idc_hero_cta_secondary_url',
				'type'          => 'url',
				'default_value' => '/especialidades/',
			],
			[
				'key'   => 'field_idc_hero_image',
				'label' => 'Imagem do hero',
				'name'  => 'idc_hero_image',
				'type'  => 'image',
				'return_format' => 'array',
				'preview_size'  => 'large',
			],
		],
		'location' => [
			[
				[
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'idc-opcoes-home',
				],
			],
		],
	]);

	acf_add_local_field_group([
		'key'    => 'group_idc_profissional',
		'title'  => 'Dados do profissional',
		'fields' => [
			[
				'key'   => 'field_idc_crm',
				'label' => 'CRM / TEOT',
				'name'  => 'idc_crm',
				'type'  => 'text',
			],
			[
				'key'   => 'field_idc_especialidade_txt',
				'label' => 'Especialidade (linha)',
				'name'  => 'idc_especialidade_txt',
				'type'  => 'text',
			],
		],
		'location' => [
			[
				[
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'idc_profissional',
				],
			],
		],
	]);
}
add_action('acf/init', 'idc_register_acf_field_groups');
