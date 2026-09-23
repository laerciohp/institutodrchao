<?php
/**
 * Accordion FAQ — design system.
 *
 * @package Instituto_Dr_Chao
 *
 * @var array $args {
 *   @type string $title
 *   @type list<array{question:string,answer:string}> $items
 * }
 */

$args  = isset($args) && is_array($args) ? $args : [];
$title = (string) ($args['title'] ?? __('Perguntas frequentes', 'instituto-dr-chao'));
$items = isset($args['items']) && is_array($args['items']) ? $args['items'] : [];

if ($items === []) {
	return;
}
?>
<section class="idc-accordion" aria-labelledby="idc-accordion-title">
	<div class="idc-container">
		<?php if ($title !== '') : ?>
			<header class="idc-section-header">
				<h2 id="idc-accordion-title" class="idc-section-header__title"><?php echo esc_html($title); ?></h2>
			</header>
		<?php endif; ?>
		<div class="idc-accordion__list">
			<?php foreach ($items as $index => $item) : ?>
				<?php
				$q = (string) ($item['question'] ?? '');
				$a = (string) ($item['answer'] ?? '');
				if ($q === '') {
					continue;
				}
				$id = 'idc-acc-' . (int) $index;
				?>
				<details class="idc-accordion__item" <?php echo $index === 0 ? 'open' : ''; ?>>
					<summary class="idc-accordion__summary" id="<?php echo esc_attr($id); ?>">
						<span class="idc-accordion__question"><?php echo esc_html($q); ?></span>
					</summary>
					<div class="idc-accordion__panel">
						<p><?php echo esc_html($a); ?></p>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
