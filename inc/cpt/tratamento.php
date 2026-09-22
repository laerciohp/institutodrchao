<?php
/**
 * CPT Tratamento.
 *
 * @package Instituto_Dr_Chao
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

function idc_register_cpt_tratamento(): void {
	register_post_type('idc_tratamento', [
		'labels' => [
			'name'          => __('Tratamentos', 'instituto-dr-chao'),
			'singular_name' => __('Tratamento', 'instituto-dr-chao'),
			'add_new_item'  => __('Adicionar tratamento', 'instituto-dr-chao'),
			'edit_item'     => __('Editar tratamento', 'instituto-dr-chao'),
			'menu_name'     => __('Tratamentos', 'instituto-dr-chao'),
		],
		'public'       => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-heart',
		'has_archive'  => true,
		'rewrite'      => ['slug' => 'tratamentos'],
		'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'],
	]);

	register_taxonomy('idc_especialidade', 'idc_tratamento', [
		'labels' => [
			'name'          => __('Especialidades', 'instituto-dr-chao'),
			'singular_name' => __('Especialidade', 'instituto-dr-chao'),
		],
		'public'       => true,
		'hierarchical' => true,
		'show_in_rest' => true,
		'rewrite'      => ['slug' => 'especialidade'],
	]);
}
add_action('init', 'idc_register_cpt_tratamento');
