<?php
/**
 * Cria páginas institucionais, menus, conteúdo ACF e corpo clínico na ativação do tema.
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
		'especialidades'         => ['title' => 'Especialidades', 'template' => 'page-especialidades.php'],
		'ortopedia-regenerativa' => ['title' => 'Ortopedia Regenerativa', 'template' => 'page-ortopedia.php'],
		'fisioterapia'           => ['title' => 'Fisioterapia', 'template' => 'page-fisioterapia.php'],
		'medicina-integrativa'   => ['title' => 'Medicina Integrativa', 'template' => 'page-medicina-integrativa.php'],
		'o-instituto'            => ['title' => 'O Instituto', 'template' => 'page-o-instituto.php'],
		'instalacoes'            => ['title' => 'Instalações', 'template' => 'page-instalacoes.php'],
		'contato'                => ['title' => 'Contato', 'template' => 'page-contato.php'],
		'carreiras'              => ['title' => 'Trabalhe Conosco', 'template' => 'page-carreiras.php'],
		'privacidade'            => ['title' => 'Privacidade', 'template' => 'page-privacidade.php'],
		'blog'                   => ['title' => 'Blog', 'template' => ''],
	];
}

/**
 * Garante páginas + leitura + menus + conteúdo.
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
	idc_setup_ensure_footer_menus($page_ids);
	idc_setup_seed_options();
	idc_setup_seed_home_options();
	idc_setup_seed_page_fields($page_ids);
	idc_setup_seed_privacy_content($page_ids);
	idc_setup_seed_profissionais();
	idc_setup_seed_blog_posts();
	idc_setup_seed_tratamentos();
	flush_rewrite_rules(false);
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
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Páginas, conteúdo, menus, equipe real e posts do blog atualizados.', 'instituto-dr-chao') . '</p></div>';
	}

	$screen = function_exists('get_current_screen') ? get_current_screen() : null;
	if (!$screen || !in_array($screen->id, ['themes', 'toplevel_page_idc-opcoes', 'dashboard'], true)) {
		return;
	}

	$url = wp_nonce_url(admin_url('themes.php?idc_setup_pages=1'), 'idc_setup_pages');
	echo '<div class="notice notice-info"><p>';
	echo esc_html__('Instituto Dr. Chao: ', 'instituto-dr-chao');
	echo '<a href="' . esc_url($url) . '">' . esc_html__('criar/atualizar páginas, conteúdo, menus, equipe real e blog', 'instituto-dr-chao') . '</a>';
	echo '</p></div>';
}
add_action('admin_notices', 'idc_setup_admin_notice');

/**
 * Menu principal (+ filhos de Especialidades).
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
		$items = wp_get_nav_menu_items($menu_id);
	}

	idc_setup_ensure_especialidades_children($menu_id, $page_ids, is_array($items) ? $items : []);

	$locations            = get_theme_mod('nav_menu_locations', []);
	$locations['primary'] = $menu_id;
	set_theme_mod('nav_menu_locations', $locations);
}

/**
 * Garante filhos sob Especialidades no menu principal.
 *
 * @param array<string,int> $page_ids
 * @param array<int,WP_Post> $items
 */
function idc_setup_ensure_especialidades_children(int $menu_id, array $page_ids, array $items): void {
	$parent_id = 0;
	foreach ($items as $item) {
		$title = mb_strtolower(trim((string) $item->title));
		$obj   = (int) ($item->object_id ?? 0);
		if (
			$title === 'especialidades'
			|| (!empty($page_ids['especialidades']) && $obj === (int) $page_ids['especialidades'])
		) {
			$parent_id = (int) $item->ID;
			break;
		}
	}

	if ($parent_id <= 0) {
		return;
	}

	$children_map = [
		'Ortopedia'             => $page_ids['ortopedia-regenerativa'] ?? 0,
		'Fisioterapia'          => $page_ids['fisioterapia'] ?? 0,
		'Medicina Integrativa'  => $page_ids['medicina-integrativa'] ?? 0,
	];

	$existing_child_ids = [];
	foreach ($items as $item) {
		if ((int) $item->menu_item_parent === $parent_id) {
			$existing_child_ids[] = (int) $item->object_id;
		}
	}

	$position = count($items) + 1;
	foreach ($children_map as $title => $page_id) {
		if ($page_id <= 0 || in_array($page_id, $existing_child_ids, true)) {
			continue;
		}
		wp_update_nav_menu_item($menu_id, 0, [
			'menu-item-title'     => $title,
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $page_id,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-parent-id' => $parent_id,
			'menu-item-position'  => $position++,
		]);
	}
}

/**
 * Menus do rodapé (3 localizações).
 *
 * @param array<string,int> $page_ids
 */
function idc_setup_ensure_footer_menus(array $page_ids): void {
	$blog_url = !empty($page_ids['blog'])
		? (string) get_permalink($page_ids['blog'])
		: home_url('/blog/');

	$defs = [
		'footer_tratamentos' => [
			'name'  => 'Rodapé Tratamentos IDC',
			'items' => [
				['title' => 'Ortopedia', 'url' => home_url('/ortopedia-regenerativa/'), 'page' => $page_ids['ortopedia-regenerativa'] ?? 0],
				['title' => 'Fisioterapia', 'url' => home_url('/fisioterapia/'), 'page' => $page_ids['fisioterapia'] ?? 0],
				['title' => 'Medicina Integrativa', 'url' => home_url('/medicina-integrativa/'), 'page' => $page_ids['medicina-integrativa'] ?? 0],
			],
		],
		'footer_institucional' => [
			'name'  => 'Rodapé Institucional IDC',
			'items' => [
				['title' => 'O Instituto', 'url' => home_url('/o-instituto/'), 'page' => $page_ids['o-instituto'] ?? 0],
				['title' => 'Corpo Clínico', 'url' => home_url('/corpo-clinico/'), 'page' => 0],
				['title' => 'Blog', 'url' => $blog_url, 'page' => $page_ids['blog'] ?? 0],
			],
		],
		'footer_contato' => [
			'name'  => 'Rodapé Contato IDC',
			'items' => [
				['title' => 'Fale Conosco', 'url' => home_url('/contato/'), 'page' => $page_ids['contato'] ?? 0],
				['title' => 'Trabalhe Conosco', 'url' => home_url('/carreiras/'), 'page' => $page_ids['carreiras'] ?? 0],
				['title' => 'Privacidade', 'url' => home_url('/privacidade/'), 'page' => $page_ids['privacidade'] ?? 0],
			],
		],
	];

	$locations = get_theme_mod('nav_menu_locations', []);
	if (!is_array($locations)) {
		$locations = [];
	}

	foreach ($defs as $location => $def) {
		$menu = wp_get_nav_menu_object($def['name']);
		if (!$menu) {
			$menu_id = wp_create_nav_menu($def['name']);
		} else {
			$menu_id = (int) $menu->term_id;
		}

		if (is_wp_error($menu_id) || !$menu_id) {
			continue;
		}

		$existing = wp_get_nav_menu_items($menu_id);
		if (empty($existing)) {
			$i = 1;
			foreach ($def['items'] as $row) {
				$args = [
					'menu-item-title'  => $row['title'],
					'menu-item-status' => 'publish',
					'menu-item-position' => $i++,
				];
				if (!empty($row['page'])) {
					$args['menu-item-object']    = 'page';
					$args['menu-item-object-id'] = (int) $row['page'];
					$args['menu-item-type']      = 'post_type';
				} else {
					$args['menu-item-type'] = 'custom';
					$args['menu-item-url']  = $row['url'];
				}
				wp_update_nav_menu_item($menu_id, 0, $args);
			}
		}

		$locations[$location] = $menu_id;
	}

	set_theme_mod('nav_menu_locations', $locations);

	idc_setup_remove_footer_instalacoes_link();
	idc_setup_ensure_footer_corpo_clinico_link();
}

/**
 * Remove link Instalações do rodapé (Figma: O Instituto, Corpo Clínico, Blog).
 */
function idc_setup_remove_footer_instalacoes_link(): void {
	$locations = get_theme_mod('nav_menu_locations', []);
	if (!is_array($locations) || empty($locations['footer_institucional'])) {
		return;
	}

	$menu_id = (int) $locations['footer_institucional'];
	$items   = wp_get_nav_menu_items($menu_id);
	if (!is_array($items)) {
		return;
	}

	foreach ($items as $item) {
		$title = mb_strtolower(trim((string) $item->title));
		$url   = (string) ($item->url ?? '');
		if (
			$title === 'instalações'
			|| $title === 'instalacoes'
			|| str_contains($url, '/instalacoes')
		) {
			wp_delete_post((int) $item->ID, true);
		}
	}
}

/**
 * @deprecated Compat — redireciona para remoção alinhada ao Figma.
 *
 * @param array<string,int> $page_ids
 */
function idc_setup_ensure_footer_instalacoes_link(array $page_ids): void {
	unset($page_ids);
	idc_setup_remove_footer_instalacoes_link();
}

/**
 * Garante / atualiza link Corpo Clínico no menu institucional do rodapé.
 */
function idc_setup_ensure_footer_corpo_clinico_link(): void {
	$target = home_url('/corpo-clinico/');

	$locations = get_theme_mod('nav_menu_locations', []);
	if (!is_array($locations) || empty($locations['footer_institucional'])) {
		return;
	}

	$menu_id = (int) $locations['footer_institucional'];
	$items   = wp_get_nav_menu_items($menu_id);
	if (!is_array($items)) {
		$items = [];
	}

	foreach ($items as $item) {
		$title = mb_strtolower(trim((string) $item->title));
		$url   = (string) ($item->url ?? '');
		if (
			$title === 'corpo clínico'
			|| $title === 'corpo clinico'
			|| str_contains($url, '#corpo-clinico')
			|| str_contains($url, '/corpo-clinico')
		) {
			if (!str_contains($url, '/corpo-clinico')) {
				wp_update_nav_menu_item($menu_id, (int) $item->ID, [
					'menu-item-title'  => 'Corpo Clínico',
					'menu-item-type'   => 'custom',
					'menu-item-url'    => $target,
					'menu-item-status' => 'publish',
				]);
			}
			return;
		}
	}

	wp_update_nav_menu_item($menu_id, 0, [
		'menu-item-title'    => 'Corpo Clínico',
		'menu-item-type'     => 'custom',
		'menu-item-url'      => $target,
		'menu-item-status'   => 'publish',
		'menu-item-position' => count($items) + 1,
	]);
}

/**
 * Seed das opções ACF com dados reais da clínica (só se vazios).
 */
function idc_setup_seed_options(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$defaults = [
		'idc_telefone'                 => '(11) 2218-8080',
		'idc_whatsapp'                 => '5511943356377',
		'idc_whatsapp_mensagem'        => 'Olá, gostaria de realizar um agendamento!',
		'idc_endereco'                 => "Rua Maria Cândida, 1.788\nVila Guilherme — São Paulo, SP\nCEP 02071-003",
		'idc_email'                    => 'atendimento@institutodrchao.com.br',
		'idc_email_rh'                 => 'rh@institutodrchao.com.br',
		'idc_horario'                  => 'Seg. à Sex. das 08h às 18h',
		'idc_map_embed'                => 'https://www.google.com/maps?q=Rua+Maria+C%C3%A2ndida,+1788,+Vila+Guilherme,+S%C3%A3o+Paulo&output=embed',
		'idc_instagram'                => 'https://www.instagram.com/institutodrchao/',
		'idc_header_cta_label'         => 'Agendar Consulta',
		'idc_footer_tagline'           => 'Precisão que acolhe. Excelência em ortopedia e reabilitação integrada desde 1987.',
		'idc_footer_copy'              => 'Instituto Dr. Chao. Todos os direitos reservados.',
		'idc_footer_col_tratamentos'   => 'Tratamentos',
		'idc_footer_col_institucional' => 'Institucional',
		'idc_footer_col_contato'       => 'Contato & Jurídico',
	];

	foreach ($defaults as $key => $value) {
		if (in_array($key, ['idc_whatsapp', 'idc_whatsapp_mensagem'], true)) {
			idc_setup_update_field_force($key, $value, 'option');
		} else {
			idc_setup_update_field_if_empty($key, $value, 'option');
		}
	}
}

/**
 * Seed das seções da Home (só se vazios).
 */
function idc_setup_seed_home_options(): void {
	if (!function_exists('update_field')) {
		return;
	}

	$scalars = [
		'idc_hero_eyebrow'           => 'INSTITUTO DR. CHAO',
		'idc_hero_since'             => 'DESDE 1987',
		'idc_hero_title_before'      => 'Tratamos a',
		'idc_hero_title_accent'      => 'origem da dor.',
		'idc_hero_title_after'       => 'Você sente a mudança.',
		'idc_hero_lead'              => "Existimos para que ninguém seja definido pela sua dor.\nCombinamos vanguarda médica e terapias integrativas em um ambiente pensado para a sua verdadeira recuperação e bem-estar contínuo.",
		'idc_hero_cta_primary'       => 'Agendar Consulta',
		'idc_hero_cta_secondary'     => 'Conheça os tratamentos',
		'idc_hero_cta_secondary_url' => home_url('/especialidades/'),
		'idc_pillars_eyebrow'        => 'NOSSA ABORDAGEM',
		'idc_pillars_title_before'   => 'Três pilares para',
		'idc_pillars_title_accent'   => 'uma recuperação',
		'idc_pillars_title_after'    => 'completa',
		'idc_pillars_lead'           => 'Não olhamos apenas para o sintoma. Atuamos com ortopedia de precisão, reabilitação física e medicina integrativa para restaurar sua qualidade de vida.',
		'idc_why_eyebrow'            => 'DIFERENCIAIS',
		'idc_why_title'              => 'Por que escolher o Instituto Dr. Chao?',
		'idc_why_lead'               => 'Unimos a precisão da medicina moderna com o acolhimento humano em um só lugar.',
		'idc_testimonials_badge'     => '5.0 · 193 avaliações',
		'idc_testimonials_title'     => 'O que dizem nossos pacientes',
		'idc_team_title'             => 'Nosso Corpo Clínico',
		'idc_team_lead'              => 'No Instituto Dr. Chao, a equipe de todos os setores abraça a missão de oferecer atendimento com cordialidade, acolhimento e empatia. Entendemos que cada pessoa carrega uma história única — e escutá-la com atenção é nossa responsabilidade.',
		'idc_team_cta_label'         => 'Conheça toda a equipe',
		'idc_team_cta_url'           => home_url('/corpo-clinico/'),
		'idc_team_count'             => 7,
		'idc_blog_title'             => 'Últimas do Blog',
		'idc_blog_lead'              => 'Informação de qualidade para a sua saúde.',
		'idc_blog_cta_label'         => 'Ver todos os artigos',
		'idc_cta_title_before'       => 'Recupere o ritmo',
		'idc_cta_title_accent'       => 'natural da sua vida.',
		'idc_cta_lead'               => 'Agende sua avaliação e descubra um plano de tratamento criado especificamente para as suas necessidades, em um ambiente que respira tranquilidade.',
		'idc_cta_primary_label'      => 'Agendar Consulta',
		'idc_cta_secondary_label'    => 'Dúvidas Frequentes',
		'idc_cta_secondary_url'      => home_url('/contato/'),
	];

	foreach ($scalars as $key => $value) {
		if (in_array($key, ['idc_testimonials_badge', 'idc_team_count', 'idc_team_cta_url', 'idc_team_lead'], true)) {
			idc_setup_update_field_force($key, $value, 'option');
		} else {
			idc_setup_update_field_if_empty($key, $value, 'option');
		}
	}

	$repeaters = [
		'idc_trust_items'    => idc_default_trust_items(),
		'idc_pillars_cards'  => idc_default_pillars_cards(),
		'idc_why_items'      => idc_default_why_items(),
		'idc_testimonials'   => idc_default_testimonials(),
	];

	foreach ($repeaters as $key => $value) {
		if (in_array($key, ['idc_testimonials', 'idc_trust_items'], true)) {
			idc_setup_update_field_force($key, $value, 'option');
		} else {
			idc_setup_update_field_if_empty($key, $value, 'option');
		}
	}
}

/**
 * Seed de campos ACF por página (só se vazios).
 *
 * @param array<string,int> $page_ids
 */
function idc_setup_seed_page_fields(array $page_ids): void {
	if (!function_exists('update_field')) {
		return;
	}

	foreach ($page_ids as $slug => $page_id) {
		if ($page_id <= 0) {
			continue;
		}

		$hero = idc_default_page_hero($slug);
		if (is_array($hero)) {
			foreach ($hero as $key => $value) {
				idc_setup_update_field_if_empty($key, $value, $page_id);
			}
		}

		$strip = idc_default_strip_cta();
		if (in_array($slug, ['especialidades', 'o-instituto', 'instalacoes', 'ortopedia-regenerativa', 'fisioterapia', 'medicina-integrativa'], true)) {
			foreach ($strip as $key => $value) {
				idc_setup_update_field_if_empty($key, $value, $page_id);
			}
		}
	}

	// Hub cards.
	if (!empty($page_ids['especialidades'])) {
		idc_setup_update_field_if_empty('idc_hub_cards', idc_default_hub_cards(), $page_ids['especialidades']);
	}

	// O Instituto — essência + cards.
	if (!empty($page_ids['o-instituto'])) {
		$inst = $page_ids['o-instituto'];
		idc_setup_update_field_if_empty('idc_instituto_essencia_eyebrow', 'NOSSA ESSÊNCIA', $inst);
		idc_setup_update_field_if_empty('idc_instituto_essencia_title', 'O que nos move', $inst);
		idc_setup_update_field_if_empty(
			'idc_instituto_essencia_lead',
			'Unimos precisão médica e acolhimento humano para que cada paciente recupere o ritmo natural da sua vida.',
			$inst
		);
		idc_setup_update_field_if_empty('idc_instituto_cards', idc_default_instituto_cards(), $inst);
	}

	// Instalações — galeria padrão (fotos locais do tema).
	if (!empty($page_ids['instalacoes'])) {
		idc_setup_update_field_if_empty(
			'idc_instalacoes_gallery',
			idc_default_instalacoes_gallery(),
			$page_ids['instalacoes']
		);
	}

	// Ortopedia — tratamentos + strip secundário.
	if (!empty($page_ids['ortopedia-regenerativa'])) {
		$oid = $page_ids['ortopedia-regenerativa'];
		idc_setup_update_field_if_empty('idc_orto_treatments_title', 'Tratamentos regenerativos', $oid);
		idc_setup_update_field_if_empty('idc_orto_treatments', idc_default_ortopedia_treatments(), $oid);
		idc_setup_update_field_if_empty('idc_strip_title', 'Pronto para iniciar seu cuidado?', $oid);
		idc_setup_update_field_if_empty('idc_strip_label', 'Fale via WhatsApp', $oid);
		idc_setup_update_field_if_empty('idc_strip_secondary_label', 'Ver Viscossuplementação', $oid);
		idc_setup_update_field_if_empty('idc_strip_secondary_url', '/tratamentos/viscosuplementacao/', $oid);
		idc_setup_update_field_if_empty('idc_page_hero_cta_label', 'Agendar Consulta', $oid);
	}

	// Fisioterapia — fases + strip.
	if (!empty($page_ids['fisioterapia'])) {
		$fid = $page_ids['fisioterapia'];
		idc_setup_update_field_if_empty('idc_fisio_phases_title', 'As 4 fases da recuperação', $fid);
		idc_setup_update_field_if_empty(
			'idc_fisio_phases',
			idc_default_fisioterapia_phases_for_acf(),
			$fid
		);
		idc_setup_update_field_if_empty('idc_strip_title', 'Pronto para iniciar sua reabilitação?', $fid);
		idc_setup_update_field_if_empty('idc_strip_label', 'Fale via WhatsApp', $fid);
	}

	// Medicina Integrativa — grade + strip.
	if (!empty($page_ids['medicina-integrativa'])) {
		$iid = $page_ids['medicina-integrativa'];
		idc_setup_update_field_if_empty('idc_integrativa_grid_title', 'Tratamentos Integrativos', $iid);
		idc_setup_update_field_if_empty(
			'idc_integrativa_grid',
			idc_default_integrativa_grid_for_acf(),
			$iid
		);
		idc_setup_update_field_if_empty('idc_strip_title', 'Pronto para buscar um equilíbrio sistêmico para sua saúde?', $iid);
		idc_setup_update_field_if_empty('idc_strip_label', 'WhatsApp', $iid);
		idc_setup_update_field_if_empty('idc_strip_secondary_label', 'Voltar para Ortopedia Regenerativa', $iid);
		idc_setup_update_field_if_empty('idc_strip_secondary_url', '/ortopedia-regenerativa/', $iid);
	}

	// Instalações — títulos da seção.
	if (!empty($page_ids['instalacoes'])) {
		$sid = $page_ids['instalacoes'];
		idc_setup_update_field_if_empty('idc_instalacoes_section_title', 'Conheça nossos espaços', $sid);
		idc_setup_update_field_if_empty(
			'idc_instalacoes_section_lead',
			'Da recepção às salas de fisioterapia e cuidados integrativos.',
			$sid
		);
	}

	// Contato.
	if (!empty($page_ids['contato'])) {
		$cid = $page_ids['contato'];
		idc_setup_update_field_if_empty('idc_contato_form_title', 'Envie uma mensagem', $cid);
		idc_setup_update_field_if_empty('idc_contato_form_lead', 'Preencha o formulário e retornaremos o mais breve possível.', $cid);
		idc_setup_update_field_if_empty('idc_contato_whatsapp_label', 'Conversar no WhatsApp', $cid);
		idc_setup_update_field_if_empty('idc_contato_aside_title', 'Fale diretamente', $cid);
		idc_setup_update_field_if_empty('idc_contato_assuntos', idc_default_contato_assuntos(), $cid);
	}

	// Carreiras.
	if (!empty($page_ids['carreiras'])) {
		$rid = $page_ids['carreiras'];
		idc_setup_update_field_if_empty('idc_carreiras_form_title', 'Envie uma mensagem', $rid);
		idc_setup_update_field_if_empty('idc_carreiras_form_lead', '', $rid);
		idc_setup_update_field_if_empty('idc_carreiras_areas', idc_default_carreiras_areas(), $rid);
	}

	// Blog (posts page) — strip CTA.
	$blog_id = (int) get_option('page_for_posts');
	if ($blog_id > 0) {
		idc_setup_update_field_if_empty('idc_strip_title', 'Pronto para cuidar da sua saúde?', $blog_id);
		idc_setup_update_field_if_empty('idc_strip_label', 'Fale via WhatsApp', $blog_id);
	}
}

/**
 * Seed do post_content da página de privacidade se vazio.
 *
 * @param array<string,int> $page_ids
 */
function idc_setup_seed_privacy_content(array $page_ids): void {
	if (empty($page_ids['privacidade'])) {
		return;
	}

	$post = get_post($page_ids['privacidade']);
	if (!$post instanceof WP_Post) {
		return;
	}

	if (trim((string) $post->post_content) !== '') {
		return;
	}

	wp_update_post([
		'ID'           => (int) $post->ID,
		'post_content' => idc_default_privacy_content(),
	]);
}

/**
 * Remove placeholders do CPT e insere os 7 profissionais reais.
 */
function idc_setup_seed_profissionais(): void {
	idc_setup_delete_placeholder_profissionais();

	foreach (idc_default_profissionais_seed() as $pro) {
		$existing = get_page_by_path($pro['slug'], OBJECT, 'idc_profissional');
		if ($existing instanceof WP_Post) {
			$id = (int) $existing->ID;
			wp_update_post([
				'ID'           => $id,
				'post_title'   => $pro['title'],
				'post_content' => $pro['content'],
				'post_status'  => 'publish',
				'menu_order'   => (int) $pro['menu_order'],
			]);
		} else {
			$id = wp_insert_post([
				'post_title'   => $pro['title'],
				'post_name'    => $pro['slug'],
				'post_content' => $pro['content'],
				'post_status'  => 'publish',
				'post_type'    => 'idc_profissional',
				'menu_order'   => (int) $pro['menu_order'],
			], true);

			if (is_wp_error($id) || !$id) {
				continue;
			}
			$id = (int) $id;
		}

		if (function_exists('update_field')) {
			update_field('idc_crm', $pro['crm'], $id);
			update_field('idc_especialidade_txt', $pro['especialidade'], $id);
		} else {
			update_post_meta($id, 'idc_crm', $pro['crm']);
			update_post_meta($id, 'idc_especialidade_txt', $pro['especialidade']);
		}

		if (!has_post_thumbnail($id) && !empty($pro['image'])) {
			idc_setup_sideload_theme_image($pro['image'], $id);
		}
	}
}

/**
 * Apaga profissionais placeholder (CRM 00000 ou títulos fictícios).
 */
function idc_setup_delete_placeholder_profissionais(): void {
	$placeholder_titles = [
		'dr. chao',
		'dra. ana oliveira',
		'dr. ricardo mendes',
	];

	$posts = get_posts([
		'post_type'      => 'idc_profissional',
		'posts_per_page' => -1,
		'post_status'    => 'any',
	]);

	foreach ($posts as $post) {
		if (!$post instanceof WP_Post) {
			continue;
		}

		$crm   = function_exists('get_field')
			? (string) get_field('idc_crm', $post->ID)
			: (string) get_post_meta($post->ID, 'idc_crm', true);
		$title = mb_strtolower(trim((string) $post->post_title));

		$is_placeholder = str_contains($crm, '00000')
			|| in_array($title, $placeholder_titles, true);

		if ($is_placeholder) {
			wp_delete_post((int) $post->ID, true);
		}
	}
}

/**
 * Importa posts do blog (produção) + remove “Olá, mundo!” + categorias Figma.
 */
function idc_setup_seed_blog_posts(): void {
	if (!function_exists('idc_default_blog_posts_seed')) {
		return;
	}

	// Remove post padrão do WordPress.
	$hello = get_page_by_path('hello-world', OBJECT, 'post');
	if ($hello instanceof WP_Post) {
		wp_delete_post((int) $hello->ID, true);
	}
	$ola = get_posts([
		'post_type'      => 'post',
		'name'           => 'ola-mundo',
		'posts_per_page' => 1,
		'post_status'    => 'any',
	]);
	if (!empty($ola[0]) && $ola[0] instanceof WP_Post) {
		$title = mb_strtolower((string) $ola[0]->post_title);
		if (str_contains($title, 'olá') || str_contains($title, 'ola') || str_contains($title, 'hello')) {
			wp_delete_post((int) $ola[0]->ID, true);
		}
	}
	$by_title = get_posts([
		'post_type'      => 'post',
		'posts_per_page' => 5,
		'post_status'    => 'any',
		's'              => 'Olá, mundo',
	]);
	foreach ($by_title as $p) {
		if ($p instanceof WP_Post && mb_strtolower(trim($p->post_title)) === 'olá, mundo!') {
			wp_delete_post((int) $p->ID, true);
		}
	}

	idc_setup_ensure_blog_categories();
	idc_setup_retire_odonto_posts();

	$cat_map = idc_default_blog_post_category_map();

	foreach (idc_default_blog_posts_seed() as $row) {
		$existing = get_page_by_path($row['slug'], OBJECT, 'post');
		if ($existing instanceof WP_Post) {
			$id = (int) $existing->ID;
		} else {
			$id = wp_insert_post([
				'post_title'    => $row['title'],
				'post_name'     => $row['slug'],
				'post_content'  => $row['content'],
				'post_excerpt'  => $row['excerpt'],
				'post_status'   => 'publish',
				'post_type'     => 'post',
				'post_date'     => $row['date'],
				'post_date_gmt' => get_gmt_from_date($row['date']),
			], true);

			if (is_wp_error($id) || !$id) {
				continue;
			}

			if (!empty($row['image'])) {
				idc_setup_sideload_theme_image($row['image'], (int) $id);
			}
		}

		$slugs = $cat_map[$row['slug']] ?? [];
		if ($slugs !== []) {
			wp_set_object_terms((int) $id, $slugs, 'category', false);
		}
	}
}

/**
 * Garante as 3 categorias dos pilares Figma.
 *
 * @return array<string,int> slug => term_id
 */
function idc_setup_ensure_blog_categories(): array {
	$ids = [];
	foreach (idc_default_blog_categories() as $cat) {
		$existing = get_category_by_slug($cat['slug']);
		if ($existing instanceof WP_Term) {
			$ids[$cat['slug']] = (int) $existing->term_id;
			continue;
		}
		$result = wp_insert_term($cat['name'], 'category', [
			'slug'        => $cat['slug'],
			'description' => $cat['description'],
		]);
		if (is_wp_error($result)) {
			continue;
		}
		$ids[$cat['slug']] = (int) $result['term_id'];
	}
	return $ids;
}

/**
 * Arquiva (draft) posts odontológicos fora do escopo Figma.
 */
function idc_setup_retire_odonto_posts(): void {
	foreach (idc_odonto_post_slugs() as $slug) {
		$post = get_page_by_path($slug, OBJECT, 'post');
		if (!$post instanceof WP_Post) {
			continue;
		}
		if ($post->post_status === 'draft') {
			continue;
		}
		wp_update_post([
			'ID'          => (int) $post->ID,
			'post_status' => 'draft',
		]);
	}
}

/**
 * Seed CPT tratamentos (Viscos + Ozônio).
 */
function idc_setup_seed_tratamentos(): void {
	if (!function_exists('idc_default_tratamentos_seed') || !post_type_exists('idc_tratamento')) {
		return;
	}

	foreach (idc_default_tratamentos_seed() as $row) {
		$existing = get_page_by_path($row['slug'], OBJECT, 'idc_tratamento');
		if ($existing instanceof WP_Post) {
			$id = (int) $existing->ID;
		} else {
			$id = wp_insert_post([
				'post_title'   => $row['title'],
				'post_name'    => $row['slug'],
				'post_content' => $row['content'],
				'post_excerpt' => $row['excerpt'],
				'post_status'  => 'publish',
				'post_type'    => 'idc_tratamento',
			], true);

			if (is_wp_error($id) || !$id) {
				continue;
			}

			if (!empty($row['image'])) {
				idc_setup_sideload_theme_image($row['image'], (int) $id);
			}
		}

		if (function_exists('update_field')) {
			idc_setup_update_field_if_empty('idc_tx_eyebrow', $row['eyebrow'], $id);
			idc_setup_update_field_if_empty('idc_tx_lead', $row['lead'], $id);
			idc_setup_update_field_if_empty('idc_tx_faqs', $row['faqs'], $id);
			idc_setup_update_field_if_empty('idc_tx_cta_label', 'Agendar Consulta', $id);
			idc_setup_update_field_if_empty('idc_tx_related_label', 'Ver Ortopedia Regenerativa', $id);
			idc_setup_update_field_if_empty('idc_tx_related_url', '/ortopedia-regenerativa/', $id);
		}

		// Termo de especialidade.
		$term = term_exists('Ortopedia Regenerativa', 'idc_especialidade');
		if (!$term) {
			$term = wp_insert_term('Ortopedia Regenerativa', 'idc_especialidade', [
				'slug' => 'ortopedia-regenerativa',
			]);
		}
		if (!is_wp_error($term)) {
			$term_id = is_array($term) ? (int) $term['term_id'] : (int) $term;
			wp_set_object_terms((int) $id, [$term_id], 'idc_especialidade', false);
		}
	}
}

/**
 * Copia arquivo do tema para a biblioteca de mídia e define como destaque.
 *
 * @return int Attachment ID ou 0.
 */
function idc_setup_sideload_theme_image(string $relative_theme_path, int $post_id): int {
	$relative_theme_path = ltrim(str_replace('\\', '/', $relative_theme_path), '/');
	$source              = IDC_THEME_DIR . '/' . $relative_theme_path;

	if (!is_readable($source)) {
		return 0;
	}

	if (!function_exists('media_handle_sideload')) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}

	$filename = basename($source);
	$bits     = wp_upload_bits($filename, null, (string) file_get_contents($source));

	if (!empty($bits['error']) || empty($bits['file'])) {
		return 0;
	}

	$filetype = wp_check_filetype($filename, null);
	$attach   = [
		'post_mime_type' => $filetype['type'] ?: 'image/jpeg',
		'post_title'     => preg_replace('/\.[^.]+$/', '', $filename),
		'post_content'   => '',
		'post_status'    => 'inherit',
		'post_parent'    => $post_id,
	];

	$attach_id = wp_insert_attachment($attach, $bits['file'], $post_id);
	if (is_wp_error($attach_id) || !$attach_id) {
		return 0;
	}

	$attach_id = (int) $attach_id;
	$meta      = wp_generate_attachment_metadata($attach_id, $bits['file']);
	if (is_array($meta)) {
		wp_update_attachment_metadata($attach_id, $meta);
	}

	set_post_thumbnail($post_id, $attach_id);

	return $attach_id;
}

/**
 * Atualiza campo ACF apenas se estiver vazio.
 *
 * @param mixed $value
 * @param int|string $post_id
 */
function idc_setup_update_field_if_empty(string $key, $value, $post_id): void {
	if (!function_exists('update_field') || !function_exists('get_field')) {
		return;
	}

	$current = get_field($key, $post_id);
	if ($current === null || $current === false || $current === '' || $current === []) {
		update_field($key, $value, $post_id);
	}
}

/**
 * Força atualização de campo ACF (substitui placeholders).
 *
 * @param mixed $value
 * @param int|string $post_id
 */
function idc_setup_update_field_force(string $key, $value, $post_id): void {
	if (!function_exists('update_field')) {
		return;
	}

	update_field($key, $value, $post_id);
}
