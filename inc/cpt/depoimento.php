<?php
/**
 * CPT Depoimento (Home — carrossel).
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Registra o CPT.
 */
if (!function_exists('idc_register_cpt_depoimento')) {
	function idc_register_cpt_depoimento(): void {
		register_post_type('idc_depoimento', [
			'labels' => [
				'name'               => __('Depoimentos', 'instituto-dr-chao'),
				'singular_name'      => __('Depoimento', 'instituto-dr-chao'),
				'add_new'            => __('Adicionar novo', 'instituto-dr-chao'),
				'add_new_item'       => __('Adicionar depoimento', 'instituto-dr-chao'),
				'edit_item'          => __('Editar depoimento', 'instituto-dr-chao'),
				'new_item'           => __('Novo depoimento', 'instituto-dr-chao'),
				'view_item'          => __('Ver depoimento', 'instituto-dr-chao'),
				'search_items'       => __('Buscar depoimentos', 'instituto-dr-chao'),
				'not_found'          => __('Nenhum depoimento encontrado', 'instituto-dr-chao'),
				'not_found_in_trash' => __('Nenhum depoimento na lixeira', 'instituto-dr-chao'),
				'menu_name'          => __('Depoimentos', 'instituto-dr-chao'),
				'all_items'          => __('Todos os depoimentos', 'instituto-dr-chao'),
			],
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_icon'           => 'dashicons-format-quote',
			'menu_position'       => 26,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'rewrite'             => false,
			'supports'            => ['title', 'thumbnail', 'page-attributes'],
		]);
	}
	add_action('init', 'idc_register_cpt_depoimento');
}

/**
 * Campos ACF do depoimento.
 */
if (!function_exists('idc_register_acf_depoimento_fields')) {
	function idc_register_acf_depoimento_fields(): void {
		if (!function_exists('acf_add_local_field_group')) {
			return;
		}

		acf_add_local_field_group([
			'key'    => 'group_idc_depoimento',
			'title'  => __('Dados do depoimento', 'instituto-dr-chao'),
			'fields' => [
				[
					'key'           => 'field_idc_dep_rating',
					'label'         => __('Estrelas (1–5)', 'instituto-dr-chao'),
					'name'          => 'idc_dep_rating',
					'type'          => 'number',
					'min'           => 1,
					'max'           => 5,
					'default_value' => 5,
					'required'      => 1,
				],
				[
					'key'          => 'field_idc_dep_quote',
					'label'        => __('Citação', 'instituto-dr-chao'),
					'name'         => 'idc_dep_quote',
					'type'         => 'textarea',
					'rows'         => 5,
					'required'     => 1,
					'instructions' => __('Texto do depoimento (sem aspas — o tema adiciona).', 'instituto-dr-chao'),
				],
				[
					'key'           => 'field_idc_dep_role',
					'label'         => __('Categoria / papel', 'instituto-dr-chao'),
					'name'          => 'idc_dep_role',
					'type'          => 'text',
					'placeholder'   => 'Paciente Ortopedia',
					'default_value' => '',
				],
			],
			'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'idc_depoimento']]],
		]);
	}
	add_action('acf/init', 'idc_register_acf_depoimento_fields');
}

/**
 * Lê meta ACF ou post_meta cru.
 */
if (!function_exists('idc_dep_meta')) {
	/**
	 * @return mixed
	 */
	function idc_dep_meta(string $key, int $post_id) {
		if (function_exists('get_field')) {
			$value = get_field($key, $post_id);
			if ($value !== null && $value !== false && $value !== '') {
				return $value;
			}
		}
		$raw = get_post_meta($post_id, $key, true);
		return $raw !== '' && $raw !== false ? $raw : '';
	}
}

/**
 * Colunas na listagem do admin.
 *
 * @param array<string,string> $columns
 * @return array<string,string>
 */
if (!function_exists('idc_depoimento_admin_columns')) {
	function idc_depoimento_admin_columns(array $columns): array {
		$new = [];
		foreach ($columns as $key => $label) {
			$new[$key] = $label;
			if ($key === 'title') {
				$new['idc_dep_rating'] = __('Estrelas', 'instituto-dr-chao');
				$new['idc_dep_role']   = __('Categoria', 'instituto-dr-chao');
				$new['idc_dep_photo']  = __('Foto', 'instituto-dr-chao');
			}
		}
		return $new;
	}
	add_filter('manage_idc_depoimento_posts_columns', 'idc_depoimento_admin_columns');
}

/**
 * Conteúdo das colunas.
 *
 * @param string $column
 * @param int    $post_id
 */
if (!function_exists('idc_depoimento_admin_column_content')) {
	function idc_depoimento_admin_column_content(string $column, int $post_id): void {
		if ($column === 'idc_dep_rating') {
			$r = (int) idc_dep_meta('idc_dep_rating', $post_id);
			echo $r > 0 ? esc_html(str_repeat('★', $r)) : '—';
			return;
		}
		if ($column === 'idc_dep_role') {
			$role = (string) idc_dep_meta('idc_dep_role', $post_id);
			echo $role !== '' ? esc_html($role) : '—';
			return;
		}
		if ($column === 'idc_dep_photo') {
			$thumb = get_the_post_thumbnail($post_id, [40, 40]);
			echo $thumb !== '' ? $thumb : '—'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
	add_action('manage_idc_depoimento_posts_custom_column', 'idc_depoimento_admin_column_content', 10, 2);
}

/**
 * Placeholder do título no editor.
 *
 * @param string $text
 * @return string
 */
if (!function_exists('idc_depoimento_enter_title')) {
	function idc_depoimento_enter_title(string $text): string {
		$screen = function_exists('get_current_screen') ? get_current_screen() : null;
		if ($screen && $screen->post_type === 'idc_depoimento') {
			return __('Nome do paciente', 'instituto-dr-chao');
		}
		return $text;
	}
	add_filter('enter_title_here', 'idc_depoimento_enter_title');
}

/**
 * Lista depoimentos publicados para o front (ordenados por menu_order).
 *
 * @return list<array{rating:int,quote:string,name:string,role:string,photo:?array|string}>
 */
if (!function_exists('idc_get_testimonials_from_cpt')) {
	function idc_get_testimonials_from_cpt(): array {
		$q = new WP_Query([
			'post_type'              => 'idc_depoimento',
			'post_status'            => 'publish',
			'posts_per_page'         => -1,
			'orderby'                => ['menu_order' => 'ASC', 'date' => 'DESC'],
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => false,
		]);

		$items = [];
		foreach ($q->posts as $post) {
			if (!$post instanceof WP_Post) {
				continue;
			}
			$quote = (string) idc_dep_meta('idc_dep_quote', $post->ID);
			if ($quote === '' && $post->post_content !== '') {
				$quote = wp_strip_all_tags($post->post_content);
			}
			$rating = (int) idc_dep_meta('idc_dep_rating', $post->ID);
			$role   = (string) idc_dep_meta('idc_dep_role', $post->ID);
			$thumb  = get_post_thumbnail_id($post->ID);

			$items[] = [
				'rating' => max(1, min(5, $rating > 0 ? $rating : 5)),
				'quote'  => $quote,
				'name'   => get_the_title($post),
				'role'   => $role,
				'photo'  => $thumb > 0 ? $thumb : null,
			];
		}

		return $items;
	}
}

/**
 * Grava campos ACF (ou post_meta) de um depoimento.
 */
if (!function_exists('idc_dep_save_fields')) {
	function idc_dep_save_fields(int $post_id, int $rating, string $quote, string $role): void {
		$rating = max(1, min(5, $rating > 0 ? $rating : 5));
		if (function_exists('update_field')) {
			update_field('field_idc_dep_rating', $rating, $post_id);
			update_field('field_idc_dep_quote', $quote, $post_id);
			update_field('field_idc_dep_role', $role, $post_id);
		}
		update_post_meta($post_id, 'idc_dep_rating', $rating);
		update_post_meta($post_id, 'idc_dep_quote', $quote);
		update_post_meta($post_id, 'idc_dep_role', $role);
		update_post_meta($post_id, '_idc_dep_rating', 'field_idc_dep_rating');
		update_post_meta($post_id, '_idc_dep_quote', 'field_idc_dep_quote');
		update_post_meta($post_id, '_idc_dep_role', 'field_idc_dep_role');
		if ($quote !== '') {
			wp_update_post([
				'ID'           => $post_id,
				'post_content' => $quote,
			]);
		}
	}
}

/**
 * Migra repeater ACF → CPT (uma vez).
 */
if (!function_exists('idc_upgrade_11213_testimonials_cpt')) {
	function idc_upgrade_11213_testimonials_cpt(): void {
		if (function_exists('opcache_reset')) {
			@opcache_reset();
		}

		idc_register_cpt_depoimento();

		$existing = get_posts([
			'post_type'      => 'idc_depoimento',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		]);
		if ($existing !== []) {
			return;
		}

		$items = [];
		if (function_exists('get_field')) {
			$from_opt = get_field('idc_testimonials', 'option');
			if (is_array($from_opt) && $from_opt !== []) {
				$items = $from_opt;
			}
			$front_id = (int) get_option('page_on_front');
			if ($items === [] && $front_id > 0) {
				$from_front = get_field('idc_testimonials', $front_id);
				if (is_array($from_front) && $from_front !== []) {
					$items = $from_front;
				}
			}
		}
		if ($items === []) {
			$items = idc_default_testimonials();
		}

		$order = 0;
		foreach ($items as $row) {
			if (!is_array($row)) {
				continue;
			}
			$name  = trim((string) ($row['name'] ?? ''));
			$quote = trim((string) ($row['quote'] ?? ''));
			if ($name === '' && $quote === '') {
				continue;
			}
			$post_id = wp_insert_post([
				'post_type'    => 'idc_depoimento',
				'post_status'  => 'publish',
				'post_title'   => $name !== '' ? $name : __('Paciente', 'instituto-dr-chao'),
				'post_content' => $quote,
				'menu_order'   => $order++,
			], true);
			if (is_wp_error($post_id) || $post_id <= 0) {
				continue;
			}
			idc_dep_save_fields(
				(int) $post_id,
				(int) ($row['rating'] ?? 5),
				$quote,
				(string) ($row['role'] ?? '')
			);
			$photo = $row['photo'] ?? null;
			$aid   = 0;
			if (is_numeric($photo)) {
				$aid = (int) $photo;
			} elseif (is_array($photo) && !empty($photo['ID'])) {
				$aid = (int) $photo['ID'];
			}
			if ($aid > 0) {
				set_post_thumbnail($post_id, $aid);
			}
		}
	}
}

/**
 * Backfill de citações/categorias nos CPT já criados.
 */
if (!function_exists('idc_upgrade_11214_testimonials_meta')) {
	function idc_upgrade_11214_testimonials_meta(): void {
		if (function_exists('opcache_reset')) {
			@opcache_reset();
		}
		idc_register_cpt_depoimento();

		$defaults = [];
		foreach (idc_default_testimonials() as $row) {
			$name = trim((string) ($row['name'] ?? ''));
			if ($name !== '') {
				$defaults[mb_strtolower($name)] = $row;
			}
		}

		$posts = get_posts([
			'post_type'      => 'idc_depoimento',
			'post_status'    => 'any',
			'posts_per_page' => -1,
		]);

		foreach ($posts as $post) {
			if (!$post instanceof WP_Post) {
				continue;
			}
			$key = mb_strtolower(trim(get_the_title($post)));
			$row = $defaults[$key] ?? null;

			$quote = (string) idc_dep_meta('idc_dep_quote', $post->ID);
			if ($quote === '' && $post->post_content !== '') {
				$quote = wp_strip_all_tags($post->post_content);
			}
			if ($quote === '' && is_array($row)) {
				$quote = (string) ($row['quote'] ?? '');
			}

			$role = (string) idc_dep_meta('idc_dep_role', $post->ID);
			if ($role === '' && is_array($row)) {
				$role = (string) ($row['role'] ?? '');
			}

			$rating = (int) idc_dep_meta('idc_dep_rating', $post->ID);
			if ($rating <= 0 && is_array($row)) {
				$rating = (int) ($row['rating'] ?? 5);
			}

			idc_dep_save_fields($post->ID, $rating, $quote, $role);
		}
	}
}
