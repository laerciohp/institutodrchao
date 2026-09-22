<?php
/**
 * Conteúdo — Instalações (galeria).
 *
 * @package Instituto_Dr_Chao
 */

$gallery = function_exists('get_field') ? get_field('idc_instalacoes_gallery') : null;
if (!is_array($gallery) || $gallery === []) {
	$gallery = idc_default_instalacoes_gallery();
}

$section_title = (string) idc_page_field('idc_instalacoes_section_title', __('Conheça nossos espaços', 'instituto-dr-chao'));
$section_lead  = (string) idc_page_field(
	'idc_instalacoes_section_lead',
	__('Da recepção às salas de fisioterapia e cuidados integrativos.', 'instituto-dr-chao')
);
?>
<section class="idc-instalacoes" aria-labelledby="idc-instalacoes-title">
	<div class="idc-container">
		<header class="idc-instalacoes__intro">
			<h2 id="idc-instalacoes-title" class="idc-instalacoes__title">
				<?php echo esc_html($section_title); ?>
			</h2>
			<?php if ($section_lead !== '') : ?>
				<p class="idc-instalacoes__lead">
					<?php echo esc_html($section_lead); ?>
				</p>
			<?php endif; ?>
		</header>

		<div class="idc-instalacoes__grid">
			<?php foreach ($gallery as $index => $item) :
				$caption = (string) ($item['caption'] ?? '');
				$url     = idc_image_url($item['image'] ?? null);
				$alt     = idc_image_alt(
					$item['image'] ?? null,
					$caption !== '' ? $caption : __('Instalações do Instituto Dr. Chao', 'instituto-dr-chao')
				);
				if ($url === '') {
					continue;
				}
				$wide = ($index % 5 === 0);
				?>
				<figure class="idc-instalacoes__item<?php echo $wide ? ' idc-instalacoes__item--wide' : ''; ?>">
					<img
						src="<?php echo esc_url($url); ?>"
						alt="<?php echo esc_attr($alt); ?>"
						loading="<?php echo $index < 2 ? 'eager' : 'lazy'; ?>"
						decoding="async"
					>
					<?php if ($caption !== '') : ?>
						<figcaption class="idc-instalacoes__caption"><?php echo esc_html($caption); ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
