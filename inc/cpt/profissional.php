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
		'has_archive'  => false,
		'rewrite'      => ['slug' => 'corpo-clinico'],
		'supports'     => ['title', 'editor', 'thumbnail', 'page-attributes'],
	]);
}
add_action('init', 'idc_register_cpt_profissional');
