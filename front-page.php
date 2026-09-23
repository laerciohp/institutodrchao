<?php
/**
 * Front page — Home (seções ordenáveis via ACF).
 *
 * @package Instituto_Dr_Chao
 */

get_header();

$sections = idc_page_field('idc_home_sections', null);
if (!is_array($sections) || $sections === []) {
	if (function_exists('get_field')) {
		$from_opt = get_field('idc_home_sections', 'option');
		if (is_array($from_opt) && $from_opt !== []) {
			$sections = $from_opt;
		}
	}
}
if (!is_array($sections) || $sections === []) {
	$sections = idc_default_home_sections();
}

$map = [
	'hero'         => 'hero',
	'trust'        => 'trust-bar',
	'pillars'      => 'pillars',
	'why'          => 'why',
	'testimonials' => 'testimonials',
	'team'         => 'team',
	'blog'         => 'blog',
	'cta'          => 'final-cta',
];
?>

<main id="main" class="site-main">
	<?php
	foreach ($sections as $row) {
		if (!is_array($row)) {
			continue;
		}
		$type = (string) ($row['acf_fc_layout'] ?? $row['section'] ?? '');
		$part = $map[$type] ?? '';
		if ($part === '') {
			continue;
		}
		get_template_part('template-parts/home/' . $part);
	}
	?>
</main>

<?php
get_footer();
