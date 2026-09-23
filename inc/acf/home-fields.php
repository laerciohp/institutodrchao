<?php
/**
 * Campos ACF da Home (todos editáveis no painel).
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Field group Home completa.
 */
function idc_register_acf_home_fields(): void {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key'    => 'group_idc_home_sections',
		'title'  => 'Home — Seções',
		'fields' => [
			// —— Ordem das seções ——
			[
				'key'   => 'field_idc_tab_home_order',
				'label' => 'Ordem das seções',
				'type'  => 'tab',
			],
			[
				'key'          => 'field_idc_home_sections',
				'label'        => 'Seções da Home',
				'name'         => 'idc_home_sections',
				'type'         => 'flexible_content',
				'button_label' => 'Adicionar seção',
				'instructions' => 'Arraste para reordenar. Cada tipo reutiliza o template-part correspondente. Conteúdo de cada seção fica nas abas abaixo / campos existentes.',
				'layouts'      => [
					['key' => 'layout_idc_home_hero', 'name' => 'hero', 'label' => 'Hero', 'display' => 'block', 'sub_fields' => []],
					['key' => 'layout_idc_home_trust', 'name' => 'trust', 'label' => 'Trust bar', 'display' => 'block', 'sub_fields' => []],
					['key' => 'layout_idc_home_pillars', 'name' => 'pillars', 'label' => 'Pilares', 'display' => 'block', 'sub_fields' => []],
					['key' => 'layout_idc_home_why', 'name' => 'why', 'label' => 'Por que o Instituto', 'display' => 'block', 'sub_fields' => []],
					['key' => 'layout_idc_home_testimonials', 'name' => 'testimonials', 'label' => 'Depoimentos', 'display' => 'block', 'sub_fields' => []],
					['key' => 'layout_idc_home_team', 'name' => 'team', 'label' => 'Equipe', 'display' => 'block', 'sub_fields' => []],
					['key' => 'layout_idc_home_blog', 'name' => 'blog', 'label' => 'Blog', 'display' => 'block', 'sub_fields' => []],
					['key' => 'layout_idc_home_cta', 'name' => 'cta', 'label' => 'CTA final', 'display' => 'block', 'sub_fields' => []],
				],
			],

			// —— Trust bar ——
			[
				'key'   => 'field_idc_tab_trust',
				'label' => 'Trust Bar',
				'type'  => 'tab',
			],
			[
				'key'          => 'field_idc_trust_items',
				'label'        => 'Itens da barra',
				'name'         => 'idc_trust_items',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Adicionar item',
				'sub_fields'   => [
					[
						'key'           => 'field_idc_trust_type',
						'label'         => 'Tipo',
						'name'          => 'type',
						'type'          => 'select',
						'choices'       => [
							'number' => 'Número / texto',
							'stars'  => 'Só estrelas',
							'rating' => 'Nota + estrelas (Google)',
						],
						'default_value' => 'number',
					],
					[
						'key'   => 'field_idc_trust_value',
						'label' => 'Valor (ex.: 38+ ou 5.0)',
						'name'  => 'value',
						'type'  => 'text',
					],
					[
						'key'   => 'field_idc_trust_label',
						'label' => 'Rótulo',
						'name'  => 'label',
						'type'  => 'text',
					],
				],
			],

			// —— Pilares ——
			[
				'key'   => 'field_idc_tab_pillars',
				'label' => 'Três pilares',
				'type'  => 'tab',
			],
			[
				'key'           => 'field_idc_pillars_eyebrow',
				'label'         => 'Eyebrow',
				'name'          => 'idc_pillars_eyebrow',
				'type'          => 'text',
				'default_value' => 'NOSSA ABORDAGEM',
			],
			[
				'key'           => 'field_idc_pillars_title_before',
				'label'         => 'Título (antes)',
				'name'          => 'idc_pillars_title_before',
				'type'          => 'text',
				'default_value' => 'Três pilares para',
			],
			[
				'key'           => 'field_idc_pillars_title_accent',
				'label'         => 'Título (destaque)',
				'name'          => 'idc_pillars_title_accent',
				'type'          => 'text',
				'default_value' => 'uma recuperação',
			],
			[
				'key'           => 'field_idc_pillars_title_after',
				'label'         => 'Título (depois)',
				'name'          => 'idc_pillars_title_after',
				'type'          => 'text',
				'default_value' => 'completa',
			],
			[
				'key'           => 'field_idc_pillars_lead',
				'label'         => 'Lead',
				'name'          => 'idc_pillars_lead',
				'type'          => 'textarea',
				'rows'          => 3,
				'default_value' => 'Não olhamos apenas para o sintoma. Atuamos com ortopedia de precisão, reabilitação física e medicina integrativa para restaurar sua qualidade de vida.',
			],
			[
				'key'          => 'field_idc_pillars_cards',
				'label'        => 'Cards dos pilares',
				'name'         => 'idc_pillars_cards',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Adicionar pilar',
				'sub_fields'   => [
					[
						'key'     => 'field_idc_pillar_layout',
						'label'   => 'Layout',
						'name'    => 'layout',
						'type'    => 'select',
						'choices' => [
							'primary' => 'Ortopedia (largo + foto)',
							'narrow'  => 'Card estreito',
							'cta'     => 'CTA “primeiro passo”',
						],
					],
					[
						'key'   => 'field_idc_pillar_title',
						'label' => 'Título',
						'name'  => 'title',
						'type'  => 'text',
					],
					[
						'key'   => 'field_idc_pillar_text',
						'label' => 'Texto',
						'name'  => 'text',
						'type'  => 'textarea',
						'rows'  => 4,
					],
					[
						'key'   => 'field_idc_pillar_link_label',
						'label' => 'Label do link',
						'name'  => 'link_label',
						'type'  => 'text',
					],
					[
						'key'          => 'field_idc_pillar_link_url',
						'label'        => 'URL do link',
						'name'         => 'link_url',
						'type'         => 'text',
						'instructions' => 'Caminho relativo ou URL completa.',
					],
					[
						'key'           => 'field_idc_pillar_tone',
						'label'         => 'Tom de fundo',
						'name'          => 'tone',
						'type'          => 'select',
						'choices'       => [
							'sand'      => 'Areia (ortopedia)',
							'mint'      => 'Menta (fisio)',
							'lavender'  => 'Lavanda (integrativa)',
							'cream'     => 'Cream (CTA)',
						],
						'default_value' => 'sand',
					],
					[
						'key'           => 'field_idc_pillar_icon',
						'label'         => 'Ícone',
						'name'          => 'icon',
						'type'          => 'image',
						'return_format' => 'array',
					],
					[
						'key'           => 'field_idc_pillar_image',
						'label'         => 'Imagem (só layout primary)',
						'name'          => 'image',
						'type'          => 'image',
						'return_format' => 'array',
					],
					[
						'key'           => 'field_idc_pillar_wa',
						'label'         => 'Link via WhatsApp?',
						'name'          => 'use_whatsapp',
						'type'          => 'true_false',
						'ui'            => 1,
						'default_value' => 0,
					],
				],
			],

			// —— Diferenciais ——
			[
				'key'   => 'field_idc_tab_why',
				'label' => 'Diferenciais',
				'type'  => 'tab',
			],
			[
				'key'           => 'field_idc_why_eyebrow',
				'label'         => 'Eyebrow',
				'name'          => 'idc_why_eyebrow',
				'type'          => 'text',
				'default_value' => 'DIFERENCIAIS',
			],
			[
				'key'           => 'field_idc_why_title',
				'label'         => 'Título',
				'name'          => 'idc_why_title',
				'type'          => 'text',
				'default_value' => 'Por que escolher o Instituto Dr. Chao?',
			],
			[
				'key'           => 'field_idc_why_lead',
				'label'         => 'Lead',
				'name'          => 'idc_why_lead',
				'type'          => 'textarea',
				'rows'          => 2,
				'default_value' => 'Unimos a precisão da medicina moderna com o acolhimento humano em um só lugar.',
			],
			[
				'key'           => 'field_idc_why_image',
				'label'         => 'Imagem',
				'name'          => 'idc_why_image',
				'type'          => 'image',
				'return_format' => 'array',
			],
			[
				'key'          => 'field_idc_why_items',
				'label'        => 'Itens',
				'name'         => 'idc_why_items',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Adicionar diferencial',
				'sub_fields'   => [
					[
						'key'           => 'field_idc_why_item_icon',
						'label'         => 'Ícone',
						'name'          => 'icon',
						'type'          => 'image',
						'return_format' => 'array',
					],
					[
						'key'   => 'field_idc_why_item_title',
						'label' => 'Título',
						'name'  => 'title',
						'type'  => 'text',
					],
					[
						'key'   => 'field_idc_why_item_text',
						'label' => 'Texto',
						'name'  => 'text',
						'type'  => 'textarea',
						'rows'  => 2,
					],
				],
			],

			// —— Depoimentos ——
			[
				'key'   => 'field_idc_tab_testimonials',
				'label' => 'Depoimentos',
				'type'  => 'tab',
			],
			[
				'key'           => 'field_idc_testimonials_badge',
				'label'         => 'Badge',
				'name'          => 'idc_testimonials_badge',
				'type'          => 'text',
				'default_value' => '5.0 · 193 avaliações',
			],
			[
				'key'           => 'field_idc_testimonials_title',
				'label'         => 'Título',
				'name'          => 'idc_testimonials_title',
				'type'          => 'text',
				'default_value' => 'O que dizem nossos pacientes',
			],
			[
				'key'          => 'field_idc_testimonials',
				'label'        => 'Depoimentos',
				'name'         => 'idc_testimonials',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Adicionar depoimento',
				'sub_fields'   => [
					[
						'key'           => 'field_idc_testimonial_rating',
						'label'         => 'Estrelas (1–5)',
						'name'          => 'rating',
						'type'          => 'number',
						'min'           => 1,
						'max'           => 5,
						'default_value' => 5,
					],
					[
						'key'   => 'field_idc_testimonial_quote',
						'label' => 'Citação',
						'name'  => 'quote',
						'type'  => 'textarea',
						'rows'  => 4,
					],
					[
						'key'   => 'field_idc_testimonial_name',
						'label' => 'Nome',
						'name'  => 'name',
						'type'  => 'text',
					],
					[
						'key'   => 'field_idc_testimonial_role',
						'label' => 'Categoria',
						'name'  => 'role',
						'type'  => 'text',
					],
					[
						'key'           => 'field_idc_testimonial_photo',
						'label'         => 'Foto',
						'name'          => 'photo',
						'type'          => 'image',
						'return_format' => 'array',
					],
				],
			],

			// —— Corpo clínico (intro; dados vêm do CPT) ——
			[
				'key'   => 'field_idc_tab_team',
				'label' => 'Corpo clínico',
				'type'  => 'tab',
			],
			[
				'key'           => 'field_idc_team_title',
				'label'         => 'Título',
				'name'          => 'idc_team_title',
				'type'          => 'text',
				'default_value' => 'Nosso Corpo Clínico',
			],
			[
				'key'           => 'field_idc_team_lead',
				'label'         => 'Lead',
				'name'          => 'idc_team_lead',
				'type'          => 'text',
				'default_value' => 'No Instituto Dr. Chao, a equipe de todos os setores abraça a missão de oferecer atendimento com cordialidade, acolhimento e empatia. Entendemos que cada pessoa carrega uma história única — e escutá-la com atenção é nossa responsabilidade.',
			],
			[
				'key'           => 'field_idc_team_cta_label',
				'label'         => 'CTA label',
				'name'          => 'idc_team_cta_label',
				'type'          => 'text',
				'default_value' => 'Conheça toda a equipe',
			],
			[
				'key'          => 'field_idc_team_cta_url',
				'label'        => 'CTA URL',
				'name'         => 'idc_team_cta_url',
				'type'         => 'text',
				'instructions' => 'Caminho relativo ou URL completa.',
			],
			[
				'key'           => 'field_idc_team_count',
				'label'         => 'Qtd. na Home',
				'name'          => 'idc_team_count',
				'type'          => 'number',
				'default_value' => 7,
				'min'           => 1,
				'max'           => 12,
			],
			[
				'key'           => 'field_idc_team_archive_eyebrow',
				'label'         => 'Archive — Eyebrow',
				'name'          => 'idc_team_archive_eyebrow',
				'type'          => 'text',
				'default_value' => 'CORPO CLÍNICO',
			],
			[
				'key'           => 'field_idc_team_archive_title',
				'label'         => 'Archive — Título',
				'name'          => 'idc_team_archive_title',
				'type'          => 'text',
				'default_value' => 'Conheça nossa equipe',
			],

			// —— Blog ——
			[
				'key'   => 'field_idc_tab_blog',
				'label' => 'Blog preview',
				'type'  => 'tab',
			],
			[
				'key'           => 'field_idc_blog_title',
				'label'         => 'Título',
				'name'          => 'idc_blog_title',
				'type'          => 'text',
				'default_value' => 'Últimas do Blog',
			],
			[
				'key'           => 'field_idc_blog_lead',
				'label'         => 'Lead',
				'name'          => 'idc_blog_lead',
				'type'          => 'text',
				'default_value' => 'Informação de qualidade para a sua saúde.',
			],
			[
				'key'           => 'field_idc_blog_cta_label',
				'label'         => 'Link “ver todos”',
				'name'          => 'idc_blog_cta_label',
				'type'          => 'text',
				'default_value' => 'Ver todos os artigos',
			],

			// —— CTA final ——
			[
				'key'   => 'field_idc_tab_cta',
				'label' => 'CTA final',
				'type'  => 'tab',
			],
			[
				'key'           => 'field_idc_cta_title_before',
				'label'         => 'Título (antes)',
				'name'          => 'idc_cta_title_before',
				'type'          => 'text',
				'default_value' => 'Recupere o ritmo',
			],
			[
				'key'           => 'field_idc_cta_title_accent',
				'label'         => 'Título (destaque)',
				'name'          => 'idc_cta_title_accent',
				'type'          => 'text',
				'default_value' => 'natural da sua vida.',
			],
			[
				'key'           => 'field_idc_cta_lead',
				'label'         => 'Lead',
				'name'          => 'idc_cta_lead',
				'type'          => 'textarea',
				'rows'          => 3,
				'default_value' => 'Agende sua avaliação e descubra um plano de tratamento criado especificamente para as suas necessidades, em um ambiente que respira tranquilidade.',
			],
			[
				'key'           => 'field_idc_cta_primary_label',
				'label'         => 'CTA primário',
				'name'          => 'idc_cta_primary_label',
				'type'          => 'text',
				'default_value' => 'Agendar Consulta',
			],
			[
				'key'           => 'field_idc_cta_secondary_label',
				'label'         => 'CTA secundário',
				'name'          => 'idc_cta_secondary_label',
				'type'          => 'text',
				'default_value' => 'Dúvidas Frequentes',
			],
			[
				'key'          => 'field_idc_cta_secondary_url',
				'label'        => 'URL CTA secundário',
				'name'         => 'idc_cta_secondary_url',
				'type'         => 'text',
				'instructions' => 'Caminho relativo (/contato/) ou URL completa.',
				'placeholder'  => '/contato/',
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
			[
				[
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				],
			],
		],
	]);
}
add_action('acf/init', 'idc_register_acf_home_fields');
