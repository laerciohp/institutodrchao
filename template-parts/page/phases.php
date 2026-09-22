<?php
/**
 * Fases da recuperação — Fisioterapia (Figma 133:1596).
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args
 */

$title = (string) ($args['title'] ?? __('As 4 fases da recuperação', 'instituto-dr-chao'));

$phases = $args['phases'] ?? null;
if (!is_array($phases) || $phases === []) {
	$phases = idc_default_fisioterapia_phases();
}
?>
<section class="idc-phases idc-specialty__block" aria-labelledby="idc-phases-title">
	<div class="idc-container">
		<header class="idc-specialty__block-header idc-specialty__block-header--center">
			<h2 id="idc-phases-title" class="idc-specialty__block-title"><?php echo esc_html($title); ?></h2>
		</header>

		<ol class="idc-phases__grid">
			<?php foreach ($phases as $index => $phase) :
				$phase_title = (string) ($phase['title'] ?? '');
				$text        = (string) ($phase['text'] ?? '');
				$icon        = (string) ($phase['icon'] ?? '');
				$label       = (string) ($phase['label'] ?? sprintf('FASE %d', $index + 1));
				$number      = (string) ($phase['number'] ?? (string) ($index + 1));
				if ($phase_title === '') {
					continue;
				}
				?>
				<li class="idc-phases__card">
					<span class="idc-phases__watermark" aria-hidden="true"><?php echo esc_html($number); ?></span>
					<?php if ($icon !== '') : ?>
						<div class="idc-phases__icon-wrap">
							<img class="idc-phases__icon" src="<?php echo esc_url($icon); ?>" alt="" width="20" height="20" decoding="async">
						</div>
					<?php else : ?>
						<span class="idc-phases__icon-wrap idc-phases__icon-wrap--num"><?php echo esc_html((string) ($index + 1)); ?></span>
					<?php endif; ?>
					<p class="idc-phases__label"><?php echo esc_html($label); ?></p>
					<h3 class="idc-phases__card-title"><?php echo esc_html($phase_title); ?></h3>
					<?php if ($text !== '') : ?>
						<p class="idc-phases__card-text"><?php echo esc_html($text); ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
