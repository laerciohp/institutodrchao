<?php
/**
 * Seções de conteúdo — páginas de especialidade.
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$sections = $args['sections'] ?? null;

if (!is_array($sections) || $sections === []) {
	$acf_sections = function_exists('get_field') ? get_field('idc_specialty_sections') : null;
	$sections     = is_array($acf_sections) && $acf_sections !== [] ? $acf_sections : idc_default_specialty_sections($args['slug'] ?? 'ortopedia');
}
?>
<section class="idc-specialty__content" aria-label="<?php esc_attr_e('Sobre o tratamento', 'instituto-dr-chao'); ?>">
	<div class="idc-container">
		<div class="idc-specialty__sections">
			<?php foreach ($sections as $section) :
				$title = (string) ($section['title'] ?? '');
				$text  = (string) ($section['text'] ?? '');
				if ($title === '') {
					continue;
				}
				?>
				<article class="idc-specialty__section">
					<h2 class="idc-specialty__section-title"><?php echo esc_html($title); ?></h2>
					<p class="idc-specialty__section-text"><?php echo esc_html($text); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
