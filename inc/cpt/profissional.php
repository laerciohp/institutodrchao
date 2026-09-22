<?php
/**
 * CPT Profissional (Corpo clínico).
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

function idc_register_cpt_profissional(): void {
	register_post_type('idc_profissional', [
		'labels' => [
			'name'          => __('Corpo clínico', 'instituto-dr-chao'),
			'singular_name' => __('Profissional', 'instituto-dr-chao'),
			'add_new_item'  => __('Adicionar profissional', 'instituto-dr-chao'),
			'edit_item'     => __('Editar profissional', 'instituto-dr-chao'),
			'menu_name'     => __('Corpo clínico', 'instituto-dr-chao'),
		],
		'public'       => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-groups',
		'has_archive'  => 'corpo-clinico',
		'rewrite'      => ['slug' => 'corpo-clinico'],
		'supports'     => ['title', 'editor', 'thumbnail', 'page-attributes'],
	]);
}
add_action('init', 'idc_register_cpt_profissional');

/**
 * Flush rewrite rules ao ativar o tema (arquivo CPT / archive).
 */
function idc_profissional_flush_rewrite(): void {
	idc_register_cpt_profissional();
	flush_rewrite_rules();
}
add_action('after_switch_theme', 'idc_profissional_flush_rewrite');

/**
 * Archive: ordenar por menu_order e listar todos.
 *
 * @param WP_Query $query
 */
function idc_profissional_archive_query(WP_Query $query): void {
	if (is_admin() || !$query->is_main_query()) {
		return;
	}

	if (!$query->is_post_type_archive('idc_profissional')) {
		return;
	}

	$query->set('orderby', ['menu_order' => 'ASC', 'title' => 'ASC']);
	$query->set('posts_per_page', -1);
}
add_action('pre_get_posts', 'idc_profissional_archive_query');
