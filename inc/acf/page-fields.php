<?php
/**
 * Campos ACF das páginas institucionais (por page template).
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Campos comuns de hero de página.
 * Nome dos campos permanece idc_page_*; keys usam $key_suffix para unicidade.
 *
 * @return list<array<string,mixed>>
 */
function idc_acf_page_hero_fields(string $key_suffix, bool $with_media = true): array {
	$fields = [
		[
			'key'   => "field_idc_page_eyebrow_{$key_suffix}",
			'label' => 'Eyebrow',
			'name'  => 'idc_page_eyebrow',
			'type'  => 'text',
		],
		[
			'key'   => "field_idc_page_title_before_{$key_suffix}",
			'label' => 'Título (antes)',
			'name'  => 'idc_page_title_before',
			'type'  => 'text',
		],
		[
			'key'   => "field_idc_page_title_accent_{$key_suffix}",
			'label' => 'Título (destaque serif)',
			'name'  => 'idc_page_title_accent',
			'type'  => 'text',
		],
		[
			'key'   => "field_idc_page_title_after_{$key_suffix}",
			'label' => 'Título (depois)',
			'name'  => 'idc_page_title_after',
			'type'  => 'text',
		],
		[
			'key'   => "field_idc_page_lead_{$key_suffix}",
			'label' => 'Lead',
			'name'  => 'idc_page_lead',
			'type'  => 'textarea',
			'rows'  => 3,
		],
	];

	if ($with_media) {
		$fields[] = [
			'key'           => "field_idc_page_hero_image_{$key_suffix}",
			'label'         => 'Imagem do hero',
			'name'          => 'idc_page_hero_image',
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
			'instructions'  => 'Se vazio, o tema usa a imagem padrão do layout.',
		];
		$fields[] = [
			'key'   => "field_idc_page_hero_cta_{$key_suffix}",
			'label' => 'Label CTA do hero (WhatsApp)',
			'name'  => 'idc_page_hero_cta_label',
			'type'  => 'text',
			'instructions' => 'Opcional — exibido no hero split.',
		];
	}

	return $fields;
}

/**
 * Campos de strip CTA (opcionais por página).
 * Nomes permanecem idc_strip_*; keys usam $suffix para unicidade.
 *
 * @return list<array<string,mixed>>
 */
function idc_acf_strip_cta_fields(string $suffix = ''): array {
	$s = $suffix !== '' ? "_{$suffix}" : '';

	return [
		[
			'key'   => "field_idc_strip_tab{$s}",
			'label' => 'Faixa CTA',
			'type'  => 'tab',
		],
		[
			'key'   => "field_idc_strip_title{$s}",
			'label' => 'Título da faixa',
			'name'  => 'idc_strip_title',
			'type'  => 'text',
		],
		[
			'key'   => "field_idc_strip_lead{$s}",
			'label' => 'Texto da faixa',
			'name'  => 'idc_strip_lead',
			'type'  => 'textarea',
			'rows'  => 2,
		],
		[
			'key'   => "field_idc_strip_label{$s}",
			'label' => 'Label do botão WhatsApp',
			'name'  => 'idc_strip_label',
			'type'  => 'text',
		],
		[
			'key'   => "field_idc_strip_secondary_label{$s}",
			'label' => 'Link secundário (label)',
			'name'  => 'idc_strip_secondary_label',
			'type'  => 'text',
		],
		[
			'key'   => "field_idc_strip_secondary_url{$s}",
			'label' => 'Link secundário (URL)',
			'name'  => 'idc_strip_secondary_url',
			'type'  => 'text',
			'instructions' => 'URL absoluta, relativa ou âncora (#id).',
		],
	];
}

/**
 * FAQ repeater.
 * Nomes permanecem idc_faq_*; keys usam $suffix para unicidade.
 *
 * @return list<array<string,mixed>>
 */
function idc_acf_faq_fields(string $suffix = ''): array {
	$s = $suffix !== '' ? "_{$suffix}" : '';

	return [
		[
			'key'   => "field_idc_faq_tab{$s}",
			'label' => 'FAQ',
			'type'  => 'tab',
		],
		[
			'key'           => "field_idc_faq_title{$s}",
			'label'         => 'Título da seção',
			'name'          => 'idc_faq_title',
			'type'          => 'text',
			'default_value' => 'Dúvidas frequentes',
		],
		[
			'key'          => "field_idc_faq_items{$s}",
			'label'        => 'Perguntas',
			'name'         => 'idc_faq_items',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Adicionar pergunta',
			'sub_fields'   => [
				[
					'key'   => "field_idc_faq_question{$s}",
					'label' => 'Pergunta',
					'name'  => 'question',
					'type'  => 'text',
				],
				[
					'key'   => "field_idc_faq_answer{$s}",
					'label' => 'Resposta',
					'name'  => 'answer',
					'type'  => 'textarea',
					'rows'  => 3,
				],
			],
		],
	];
}

/**
 * Registra field groups por template de página.
 */
function idc_register_acf_page_fields(): void {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	// —— Hub Especialidades ——
	acf_add_local_field_group([
		'key'    => 'group_idc_page_especialidades',
		'title'  => 'Página — Especialidades Hub',
		'fields' => array_merge(
			[
				['key' => 'field_idc_hub_tab_hero', 'label' => 'Hero', 'type' => 'tab'],
			],
			idc_acf_page_hero_fields('especialidades'),
			[
				['key' => 'field_idc_hub_tab_cards', 'label' => 'Cards', 'type' => 'tab'],
				[
					'key'          => 'field_idc_hub_cards',
					'label'        => 'Cards do hub',
					'name'         => 'idc_hub_cards',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Adicionar card',
					'sub_fields'   => [
						['key' => 'field_idc_hub_card_title', 'label' => 'Título', 'name' => 'title', 'type' => 'text'],
						['key' => 'field_idc_hub_card_text', 'label' => 'Texto', 'name' => 'text', 'type' => 'textarea', 'rows' => 3],
						['key' => 'field_idc_hub_card_link_label', 'label' => 'Label do link', 'name' => 'link_label', 'type' => 'text'],
						['key' => 'field_idc_hub_card_link_url', 'label' => 'URL', 'name' => 'link_url', 'type' => 'url'],
						[
							'key'     => 'field_idc_hub_card_tone',
							'label'   => 'Tom',
							'name'    => 'tone',
							'type'    => 'select',
							'choices' => ['sand' => 'Areia', 'peach' => 'Pêssego', 'cream' => 'Cream'],
						],
						['key' => 'field_idc_hub_card_image', 'label' => 'Imagem', 'name' => 'image', 'type' => 'image', 'return_format' => 'array'],
					],
				],
			],
			idc_acf_strip_cta_fields('especialidades')
		),
		'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-especialidades.php']]],
	]);

	// —— O Instituto ——
	acf_add_local_field_group([
		'key'    => 'group_idc_page_instituto',
		'title'  => 'Página — O Instituto',
		'fields' => array_merge(
			[
				['key' => 'field_idc_inst_tab_hero', 'label' => 'Hero', 'type' => 'tab'],
			],
			idc_acf_page_hero_fields('instituto'),
			[
				['key' => 'field_idc_inst_tab_essencia', 'label' => 'Essência', 'type' => 'tab'],
				['key' => 'field_idc_instituto_essencia_eyebrow', 'label' => 'Eyebrow essência', 'name' => 'idc_instituto_essencia_eyebrow', 'type' => 'text'],
				['key' => 'field_idc_instituto_essencia_title', 'label' => 'Título essência', 'name' => 'idc_instituto_essencia_title', 'type' => 'text'],
				['key' => 'field_idc_instituto_essencia_lead', 'label' => 'Lead essência', 'name' => 'idc_instituto_essencia_lead', 'type' => 'textarea', 'rows' => 2],
				[
					'key'          => 'field_idc_instituto_cards',
					'label'        => 'Diferenciais (2×2)',
					'name'         => 'idc_instituto_cards',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Adicionar card',
					'sub_fields'   => [
						['key' => 'field_idc_inst_card_icon', 'label' => 'Ícone', 'name' => 'icon', 'type' => 'image', 'return_format' => 'array'],
						['key' => 'field_idc_inst_card_title', 'label' => 'Título', 'name' => 'title', 'type' => 'text'],
						['key' => 'field_idc_inst_card_text', 'label' => 'Texto', 'name' => 'text', 'type' => 'textarea', 'rows' => 2],
					],
				],
				['key' => 'field_idc_instituto_image', 'label' => 'Imagem interior', 'name' => 'idc_instituto_image', 'type' => 'image', 'return_format' => 'array'],
			],
			idc_acf_strip_cta_fields('instituto')
		),
		'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-o-instituto.php']]],
	]);

	// —— Instalações ——
	acf_add_local_field_group([
		'key'    => 'group_idc_page_instalacoes',
		'title'  => 'Página — Instalações',
		'fields' => array_merge(
			[
				['key' => 'field_idc_instal_tab_hero', 'label' => 'Hero', 'type' => 'tab'],
			],
			idc_acf_page_hero_fields('instalacoes'),
			[
				['key' => 'field_idc_instal_tab_gallery', 'label' => 'Galeria', 'type' => 'tab'],
				[
					'key'           => 'field_idc_instalacoes_section_title',
					'label'         => 'Título da seção',
					'name'          => 'idc_instalacoes_section_title',
					'type'          => 'text',
					'default_value' => 'Conheça nossos espaços',
				],
				[
					'key'           => 'field_idc_instalacoes_section_lead',
					'label'         => 'Lead da seção',
					'name'          => 'idc_instalacoes_section_lead',
					'type'          => 'textarea',
					'rows'          => 2,
					'default_value' => 'Da recepção às salas de fisioterapia e cuidados integrativos.',
				],
				[
					'key'          => 'field_idc_instalacoes_gallery',
					'label'        => 'Galeria de imagens',
					'name'         => 'idc_instalacoes_gallery',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Adicionar imagem',
					'instructions' => 'Se vazio, o tema usa as fotos padrão em assets/images/instalacoes/.',
					'sub_fields'   => [
						[
							'key'           => 'field_idc_instal_gallery_image',
							'label'         => 'Imagem',
							'name'          => 'image',
							'type'          => 'image',
							'return_format' => 'array',
							'preview_size'  => 'medium',
						],
						[
							'key'   => 'field_idc_instal_gallery_caption',
							'label' => 'Legenda',
							'name'  => 'caption',
							'type'  => 'text',
						],
					],
				],
			],
			idc_acf_strip_cta_fields('instalacoes')
		),
		'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-instalacoes.php']]],
	]);

	// —— Ortopedia Regenerativa ——
	acf_add_local_field_group([
		'key'    => 'group_idc_page_ortopedia',
		'title'  => 'Página — Ortopedia Regenerativa',
		'fields' => array_merge(
			[
				['key' => 'field_idc_spec_tab_hero_ortopedia', 'label' => 'Hero', 'type' => 'tab'],
			],
			idc_acf_page_hero_fields('ortopedia'),
			[
				['key' => 'field_idc_orto_tab_treatments', 'label' => 'Tratamentos', 'type' => 'tab'],
				[
					'key'           => 'field_idc_orto_treatments_title',
					'label'         => 'Título da seção',
					'name'          => 'idc_orto_treatments_title',
					'type'          => 'text',
					'default_value' => 'Tratamentos regenerativos',
				],
				[
					'key'          => 'field_idc_orto_treatments',
					'label'        => 'Cards de tratamento (com FAQ)',
					'name'         => 'idc_orto_treatments',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Adicionar tratamento',
					'sub_fields'   => [
						['key' => 'field_idc_orto_tx_title', 'label' => 'Título', 'name' => 'title', 'type' => 'text'],
						['key' => 'field_idc_orto_tx_intro', 'label' => 'Introdução', 'name' => 'intro', 'type' => 'textarea', 'rows' => 3],
						[
							'key'          => 'field_idc_orto_tx_faqs',
							'label'        => 'Perguntas frequentes',
							'name'         => 'faqs',
							'type'         => 'repeater',
							'layout'       => 'table',
							'button_label' => 'Adicionar pergunta',
							'sub_fields'   => [
								['key' => 'field_idc_orto_tx_faq_q', 'label' => 'Pergunta', 'name' => 'question', 'type' => 'text'],
								['key' => 'field_idc_orto_tx_faq_a', 'label' => 'Resposta', 'name' => 'answer', 'type' => 'textarea', 'rows' => 2],
							],
						],
					],
				],
			],
			idc_acf_strip_cta_fields('ortopedia')
		),
		'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-ortopedia.php']]],
	]);

	// —— Fisioterapia ——
	acf_add_local_field_group([
		'key'    => 'group_idc_page_fisioterapia',
		'title'  => 'Página — Fisioterapia',
		'fields' => array_merge(
			[
				['key' => 'field_idc_spec_tab_hero_fisioterapia', 'label' => 'Hero', 'type' => 'tab'],
			],
			idc_acf_page_hero_fields('fisioterapia'),
			[
				['key' => 'field_idc_fisio_tab_phases', 'label' => 'Fases', 'type' => 'tab'],
				[
					'key'           => 'field_idc_fisio_phases_title',
					'label'         => 'Título da seção',
					'name'          => 'idc_fisio_phases_title',
					'type'          => 'text',
					'default_value' => 'As 4 fases da recuperação',
				],
				[
					'key'          => 'field_idc_fisio_phases',
					'label'        => 'Fases da recuperação',
					'name'         => 'idc_fisio_phases',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Adicionar fase',
					'sub_fields'   => [
						['key' => 'field_idc_fisio_phase_number', 'label' => 'Número', 'name' => 'number', 'type' => 'text', 'instructions' => 'Ex.: 1'],
						['key' => 'field_idc_fisio_phase_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text', 'instructions' => 'Ex.: FASE 1'],
						['key' => 'field_idc_fisio_phase_title', 'label' => 'Título', 'name' => 'title', 'type' => 'text'],
						['key' => 'field_idc_fisio_phase_text', 'label' => 'Texto', 'name' => 'text', 'type' => 'textarea', 'rows' => 3],
						[
							'key'           => 'field_idc_fisio_phase_icon',
							'label'         => 'Ícone',
							'name'          => 'icon',
							'type'          => 'image',
							'return_format' => 'array',
							'preview_size'  => 'thumbnail',
						],
					],
				],
			],
			idc_acf_strip_cta_fields('fisioterapia')
		),
		'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-fisioterapia.php']]],
	]);

	// —— Medicina Integrativa ——
	acf_add_local_field_group([
		'key'    => 'group_idc_page_integrativa',
		'title'  => 'Página — Medicina Integrativa',
		'fields' => array_merge(
			[
				['key' => 'field_idc_spec_tab_hero_integrativa', 'label' => 'Hero', 'type' => 'tab'],
			],
			idc_acf_page_hero_fields('integrativa'),
			[
				['key' => 'field_idc_integ_tab_grid', 'label' => 'Tratamentos', 'type' => 'tab'],
				[
					'key'           => 'field_idc_integrativa_grid_title',
					'label'         => 'Título da seção',
					'name'          => 'idc_integrativa_grid_title',
					'type'          => 'text',
					'default_value' => 'Tratamentos Integrativos',
				],
				[
					'key'          => 'field_idc_integrativa_grid',
					'label'        => 'Grade de tratamentos',
					'name'         => 'idc_integrativa_grid',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Adicionar tratamento',
					'sub_fields'   => [
						['key' => 'field_idc_integ_item_title', 'label' => 'Título', 'name' => 'title', 'type' => 'text'],
						['key' => 'field_idc_integ_item_text', 'label' => 'Texto', 'name' => 'text', 'type' => 'textarea', 'rows' => 2],
						[
							'key'           => 'field_idc_integ_item_icon',
							'label'         => 'Ícone',
							'name'          => 'icon',
							'type'          => 'image',
							'return_format' => 'array',
							'preview_size'  => 'thumbnail',
						],
					],
				],
			],
			idc_acf_strip_cta_fields('integrativa')
		),
		'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-medicina-integrativa.php']]],
	]);

	// —— Contato ——
	acf_add_local_field_group([
		'key'    => 'group_idc_page_contato',
		'title'  => 'Página — Contato',
		'fields' => array_merge(
			[
				['key' => 'field_idc_cont_tab_hero', 'label' => 'Hero', 'type' => 'tab'],
			],
			idc_acf_page_hero_fields('contato'),
			[
				['key' => 'field_idc_cont_tab_info', 'label' => 'Informações', 'type' => 'tab'],
				['key' => 'field_idc_contato_telefone', 'label' => 'Telefone (override)', 'name' => 'idc_contato_telefone', 'type' => 'text', 'instructions' => 'Deixe vazio para usar IDC Opções.'],
				['key' => 'field_idc_contato_whatsapp_label', 'label' => 'Label botão WhatsApp', 'name' => 'idc_contato_whatsapp_label', 'type' => 'text', 'default_value' => 'Conversar no WhatsApp'],
				['key' => 'field_idc_contato_endereco', 'label' => 'Endereço (override)', 'name' => 'idc_contato_endereco', 'type' => 'textarea', 'rows' => 3],
				['key' => 'field_idc_contato_aside_title', 'label' => 'Título do aside', 'name' => 'idc_contato_aside_title', 'type' => 'text', 'default_value' => 'Fale diretamente'],
				['key' => 'field_idc_contato_form_title', 'label' => 'Título do formulário', 'name' => 'idc_contato_form_title', 'type' => 'text'],
				['key' => 'field_idc_contato_form_lead', 'label' => 'Lead do formulário', 'name' => 'idc_contato_form_lead', 'type' => 'textarea', 'rows' => 2],
				[
					'key'          => 'field_idc_contato_assuntos',
					'label'        => 'Assuntos do formulário',
					'name'         => 'idc_contato_assuntos',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => 'Adicionar assunto',
					'sub_fields'   => [
						['key' => 'field_idc_contato_assunto_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'],
						['key' => 'field_idc_contato_assunto_value', 'label' => 'Value', 'name' => 'value', 'type' => 'text'],
					],
				],
				['key' => 'field_idc_cont_tab_labels', 'label' => 'Labels do formulário', 'type' => 'tab'],
				['key' => 'field_idc_contato_label_nome', 'label' => 'Label — Nome', 'name' => 'idc_contato_label_nome', 'type' => 'text', 'default_value' => 'Nome completo'],
				['key' => 'field_idc_contato_label_email', 'label' => 'Label — E-mail', 'name' => 'idc_contato_label_email', 'type' => 'text', 'default_value' => 'E-mail'],
				['key' => 'field_idc_contato_label_telefone', 'label' => 'Label — Telefone', 'name' => 'idc_contato_label_telefone', 'type' => 'text', 'default_value' => 'Telefone'],
				['key' => 'field_idc_contato_label_assunto', 'label' => 'Label — Assunto', 'name' => 'idc_contato_label_assunto', 'type' => 'text', 'default_value' => 'Assunto de interesse'],
				['key' => 'field_idc_contato_label_mensagem', 'label' => 'Label — Mensagem', 'name' => 'idc_contato_label_mensagem', 'type' => 'text', 'default_value' => 'Mensagem'],
				['key' => 'field_idc_contato_label_submit', 'label' => 'Botão enviar', 'name' => 'idc_contato_label_submit', 'type' => 'text', 'default_value' => 'Enviar mensagem'],
				['key' => 'field_idc_contato_label_endereco', 'label' => 'Aside — Endereço', 'name' => 'idc_contato_label_endereco', 'type' => 'text', 'default_value' => 'Endereço'],
				['key' => 'field_idc_contato_label_horario', 'label' => 'Aside — Horário', 'name' => 'idc_contato_label_horario', 'type' => 'text', 'default_value' => 'Horário de atendimento'],
				['key' => 'field_idc_contato_label_maps', 'label' => 'Aside — Maps', 'name' => 'idc_contato_label_maps', 'type' => 'text', 'default_value' => 'Ver no Google Maps'],
			]
		),
		'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-contato.php']]],
	]);

	// —— Carreiras ——
	acf_add_local_field_group([
		'key'    => 'group_idc_page_carreiras',
		'title'  => 'Página — Carreiras',
		'fields' => array_merge(
			[
				['key' => 'field_idc_carr_tab_hero', 'label' => 'Hero', 'type' => 'tab'],
			],
			idc_acf_page_hero_fields('carreiras'),
			[
				['key' => 'field_idc_carr_tab_form', 'label' => 'Formulário', 'type' => 'tab'],
				['key' => 'field_idc_carreiras_form_title', 'label' => 'Título do formulário', 'name' => 'idc_carreiras_form_title', 'type' => 'text'],
				['key' => 'field_idc_carreiras_form_lead', 'label' => 'Lead do formulário', 'name' => 'idc_carreiras_form_lead', 'type' => 'textarea', 'rows' => 2],
				['key' => 'field_idc_carreiras_email', 'label' => 'E-mail RH (mailto)', 'name' => 'idc_carreiras_email', 'type' => 'email', 'default_value' => 'rh@institutodrchao.com.br'],
				[
					'key'          => 'field_idc_carreiras_areas',
					'label'        => 'Áreas / vagas',
					'name'         => 'idc_carreiras_areas',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => 'Adicionar área',
					'sub_fields'   => [
						['key' => 'field_idc_carreiras_area_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'],
					],
				],
				['key' => 'field_idc_carr_tab_labels', 'label' => 'Labels do formulário', 'type' => 'tab'],
				['key' => 'field_idc_carreiras_label_nome', 'label' => 'Label — Nome', 'name' => 'idc_carreiras_label_nome', 'type' => 'text', 'default_value' => 'Nome completo'],
				['key' => 'field_idc_carreiras_label_email', 'label' => 'Label — E-mail', 'name' => 'idc_carreiras_label_email', 'type' => 'text', 'default_value' => 'E-mail'],
				['key' => 'field_idc_carreiras_label_telefone', 'label' => 'Label — Telefone', 'name' => 'idc_carreiras_label_telefone', 'type' => 'text', 'default_value' => 'Telefone / WhatsApp'],
				['key' => 'field_idc_carreiras_label_area', 'label' => 'Label — Área', 'name' => 'idc_carreiras_label_area', 'type' => 'text', 'default_value' => 'Área de atuação ou vaga desejada'],
				['key' => 'field_idc_carreiras_label_cv', 'label' => 'Label — Currículo', 'name' => 'idc_carreiras_label_cv', 'type' => 'text', 'default_value' => 'Anexe seu currículo (PDF ou DOC, máx 5MB)'],
				['key' => 'field_idc_carreiras_label_mensagem', 'label' => 'Label — Mensagem', 'name' => 'idc_carreiras_label_mensagem', 'type' => 'text', 'default_value' => 'Mensagem'],
				['key' => 'field_idc_carreiras_label_submit', 'label' => 'Botão enviar', 'name' => 'idc_carreiras_label_submit', 'type' => 'text', 'default_value' => 'Enviar candidatura'],
			]
		),
		'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-carreiras.php']]],
	]);

	// —— Privacidade ——
	acf_add_local_field_group([
		'key'    => 'group_idc_page_privacidade',
		'title'  => 'Página — Privacidade',
		'fields' => array_merge(
			[
				['key' => 'field_idc_priv_tab_hero', 'label' => 'Hero', 'type' => 'tab'],
			],
			idc_acf_page_hero_fields('privacidade')
		),
		'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-privacidade.php']]],
	]);

	// —— Blog (página de posts) ——
	acf_add_local_field_group([
		'key'    => 'group_idc_page_blog',
		'title'  => 'Página — Blog (arquivo)',
		'fields' => array_merge(
			[
				['key' => 'field_idc_blog_tab_hero', 'label' => 'Hero', 'type' => 'tab'],
			],
			idc_acf_page_hero_fields('blog', false),
			idc_acf_strip_cta_fields('blog')
		),
		'location' => [[['param' => 'page_type', 'operator' => '==', 'value' => 'posts_page']]],
	]);

	// —— Especialidade (template flexível) ——
	acf_add_local_field_group([
		'key'    => 'group_idc_page_especialidade',
		'title'  => 'Página — Especialidade',
		'fields' => array_merge(
			[
				['key' => 'field_idc_espec_tab_hero', 'label' => 'Hero', 'type' => 'tab'],
			],
			idc_acf_page_hero_fields('especialidade'),
			[
				['key' => 'field_idc_espec_tab_blocks', 'label' => 'Blocos', 'type' => 'tab'],
				[
					'key'          => 'field_idc_specialty_blocks',
					'label'        => 'Blocos da especialidade',
					'name'         => 'idc_specialty_blocks',
					'type'         => 'flexible_content',
					'button_label' => 'Adicionar bloco',
					'layouts'      => [
						[
							'key'        => 'layout_idc_spec_rich',
							'name'       => 'rich_text',
							'label'      => 'Texto',
							'display'    => 'block',
							'sub_fields' => [
								['key' => 'field_idc_spec_rich_body', 'label' => 'Conteúdo', 'name' => 'body', 'type' => 'wysiwyg', 'tabs' => 'visual', 'media_upload' => 0],
							],
						],
						[
							'key'        => 'layout_idc_spec_tx_faq',
							'name'       => 'treatment_faq',
							'label'      => 'Cards / FAQ tratamentos',
							'display'    => 'block',
							'sub_fields' => [
								['key' => 'field_idc_spec_tx_title', 'label' => 'Título da seção', 'name' => 'section_title', 'type' => 'text'],
								[
									'key'          => 'field_idc_spec_tx_cards',
									'label'        => 'Cards',
									'name'         => 'cards',
									'type'         => 'repeater',
									'layout'       => 'block',
									'button_label' => 'Adicionar card',
									'sub_fields'   => [
										['key' => 'field_idc_spec_tx_card_title', 'label' => 'Título', 'name' => 'title', 'type' => 'text'],
										['key' => 'field_idc_spec_tx_card_intro', 'label' => 'Intro', 'name' => 'intro', 'type' => 'textarea', 'rows' => 2],
										['key' => 'field_idc_spec_tx_card_icon', 'label' => 'Ícone', 'name' => 'icon', 'type' => 'image', 'return_format' => 'array'],
										[
											'key'          => 'field_idc_spec_tx_card_faqs',
											'label'        => 'FAQs',
											'name'         => 'faqs',
											'type'         => 'repeater',
											'layout'       => 'table',
											'button_label' => 'Pergunta',
											'sub_fields'   => [
												['key' => 'field_idc_spec_tx_faq_q', 'label' => 'Pergunta', 'name' => 'question', 'type' => 'text'],
												['key' => 'field_idc_spec_tx_faq_a', 'label' => 'Resposta', 'name' => 'answer', 'type' => 'textarea', 'rows' => 2],
											],
										],
									],
								],
							],
						],
						[
							'key'        => 'layout_idc_spec_phases',
							'name'       => 'phases',
							'label'      => 'Fases / cards',
							'display'    => 'block',
							'sub_fields' => [
								['key' => 'field_idc_spec_phases_title', 'label' => 'Título da seção', 'name' => 'section_title', 'type' => 'text'],
								[
									'key'          => 'field_idc_spec_phases_items',
									'label'        => 'Fases',
									'name'         => 'phases',
									'type'         => 'repeater',
									'layout'       => 'block',
									'button_label' => 'Adicionar fase',
									'sub_fields'   => [
										['key' => 'field_idc_spec_phase_n', 'label' => 'Número', 'name' => 'number', 'type' => 'text'],
										['key' => 'field_idc_spec_phase_l', 'label' => 'Label', 'name' => 'label', 'type' => 'text'],
										['key' => 'field_idc_spec_phase_t', 'label' => 'Título', 'name' => 'title', 'type' => 'text'],
										['key' => 'field_idc_spec_phase_x', 'label' => 'Texto', 'name' => 'text', 'type' => 'textarea', 'rows' => 3],
										['key' => 'field_idc_spec_phase_i', 'label' => 'Ícone', 'name' => 'icon', 'type' => 'image', 'return_format' => 'array'],
									],
								],
							],
						],
						[
							'key'        => 'layout_idc_spec_bento',
							'name'       => 'bento',
							'label'      => 'Bento / grade',
							'display'    => 'block',
							'sub_fields' => [
								['key' => 'field_idc_spec_bento_title', 'label' => 'Título da seção', 'name' => 'section_title', 'type' => 'text'],
								[
									'key'          => 'field_idc_spec_bento_items',
									'label'        => 'Itens',
									'name'         => 'items',
									'type'         => 'repeater',
									'layout'       => 'block',
									'button_label' => 'Adicionar item',
									'sub_fields'   => [
										['key' => 'field_idc_spec_bento_t', 'label' => 'Título', 'name' => 'title', 'type' => 'text'],
										['key' => 'field_idc_spec_bento_x', 'label' => 'Texto', 'name' => 'text', 'type' => 'textarea', 'rows' => 2],
										['key' => 'field_idc_spec_bento_i', 'label' => 'Ícone', 'name' => 'icon', 'type' => 'image', 'return_format' => 'array'],
									],
								],
							],
						],
						[
							'key'        => 'layout_idc_spec_faq',
							'name'       => 'faq',
							'label'      => 'FAQ accordion',
							'display'    => 'block',
							'sub_fields' => [
								['key' => 'field_idc_spec_faq_title', 'label' => 'Título', 'name' => 'section_title', 'type' => 'text'],
								[
									'key'          => 'field_idc_spec_faq_items',
									'label'        => 'Perguntas',
									'name'         => 'items',
									'type'         => 'repeater',
									'layout'       => 'table',
									'button_label' => 'Adicionar',
									'sub_fields'   => [
										['key' => 'field_idc_spec_faq_q', 'label' => 'Pergunta', 'name' => 'question', 'type' => 'text'],
										['key' => 'field_idc_spec_faq_a', 'label' => 'Resposta', 'name' => 'answer', 'type' => 'textarea', 'rows' => 2],
									],
								],
							],
						],
						[
							'key'        => 'layout_idc_spec_strip',
							'name'       => 'strip_cta',
							'label'      => 'Strip CTA',
							'display'    => 'block',
							'sub_fields' => [
								['key' => 'field_idc_spec_strip_title', 'label' => 'Título', 'name' => 'title', 'type' => 'text'],
								['key' => 'field_idc_spec_strip_lead', 'label' => 'Lead', 'name' => 'lead', 'type' => 'textarea', 'rows' => 2],
								['key' => 'field_idc_spec_strip_label', 'label' => 'Label botão', 'name' => 'label', 'type' => 'text'],
								['key' => 'field_idc_spec_strip_sec_l', 'label' => 'Label secundário', 'name' => 'secondary_label', 'type' => 'text'],
								['key' => 'field_idc_spec_strip_sec_u', 'label' => 'URL secundária', 'name' => 'secondary_url', 'type' => 'url'],
							],
						],
					],
				],
			],
			idc_acf_strip_cta_fields('especialidade')
		),
		'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-especialidade.php']]],
	]);
}
add_action('acf/init', 'idc_register_acf_page_fields');

/**
 * Ao salvar página com template Especialidade, sugere card no hub se ainda não existir.
 */
function idc_hub_sync_on_specialty_save(int $post_id): void {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	if (wp_is_post_revision($post_id) || get_post_type($post_id) !== 'page') {
		return;
	}
	if (get_page_template_slug($post_id) !== 'page-especialidade.php') {
		return;
	}
	if (!current_user_can('edit_page', $post_id) || !function_exists('get_field') || !function_exists('update_field')) {
		return;
	}

	$hub = get_page_by_path('especialidades');
	if (!$hub instanceof WP_Post) {
		return;
	}

	$page  = get_post($post_id);
	$title = $page instanceof WP_Post ? $page->post_title : '';
	$url   = get_permalink($post_id);
	if ($title === '' || !$url) {
		return;
	}

	$cards = get_field('idc_hub_cards', (int) $hub->ID);
	if (!is_array($cards)) {
		$cards = [];
	}

	foreach ($cards as $card) {
		if (!is_array($card)) {
			continue;
		}
		$existing = (string) ($card['link_url'] ?? '');
		if ($existing !== '' && untrailingslashit($existing) === untrailingslashit($url)) {
			return;
		}
	}

	$tones   = ['sand', 'peach', 'cream'];
	$cards[] = [
		'title'      => $title,
		'text'       => (string) get_field('idc_page_lead', $post_id) ?: '',
		'link_label' => __('Saiba mais', 'instituto-dr-chao'),
		'link_url'   => $url,
		'tone'       => $tones[count($cards) % 3],
		'image'      => null,
	];
	update_field('idc_hub_cards', $cards, (int) $hub->ID);
}
add_action('save_post_page', 'idc_hub_sync_on_specialty_save', 20);
