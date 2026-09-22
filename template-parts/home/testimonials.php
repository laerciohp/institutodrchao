<?php
/**
 * Depoimentos — Figma 133:493.
 *
 * @package Instituto_Dr_Chao
 */

$badge = (string) idc_option('idc_testimonials_badge', '5.0 Avaliação Média');
$title = (string) idc_option('idc_testimonials_title', 'O que dizem nossos pacientes');
$items = idc_option('idc_testimonials', []);
if (!is_array($items) || $items === []) {
	$items = idc_default_testimonials();
}
?>
<section class="idc-testimonials" aria-labelledby="idc-testimonials-title">
	<div class="idc-container">
		<header class="idc-testimonials__intro">
			<div class="idc-testimonials__badge">
				<img src="<?php echo esc_url(idc_asset('assets/icons/star.svg')); ?>" alt="" width="20" height="19" decoding="async">
				<span><?php echo esc_html($badge); ?></span>
			</div>
			<h2 id="idc-testimonials-title" class="idc-testimonials__title"><?php echo esc_html($title); ?></h2>
		</header>

		<div class="idc-testimonials__grid">
			<?php foreach ($items as $item) :
				$rating = max(1, min(5, (int) ($item['rating'] ?? 5)));
				$quote  = (string) ($item['quote'] ?? '');
				$name   = (string) ($item['name'] ?? '');
				$role   = (string) ($item['role'] ?? '');
				$photo  = idc_image_url($item['photo'] ?? null);
				?>
				<blockquote class="idc-testimonial">
					<div class="idc-testimonial__stars" aria-label="<?php echo esc_attr(sprintf(/* translators: %d stars */ __('%d de 5 estrelas', 'instituto-dr-chao'), $rating)); ?>">
						<?php for ($i = 0; $i < $rating; $i++) : ?>
							<img src="<?php echo esc_url(idc_asset('assets/icons/star-sm.svg')); ?>" alt="" width="20" height="19" decoding="async">
						<?php endfor; ?>
					</div>
					<p class="idc-testimonial__quote">&ldquo;<?php echo esc_html($quote); ?>&rdquo;</p>
					<footer class="idc-testimonial__author">
						<div class="idc-testimonial__avatar">
							<?php if ($photo !== '') : ?>
								<img src="<?php echo esc_url($photo); ?>" alt="" width="48" height="48" loading="lazy" decoding="async">
							<?php endif; ?>
						</div>
						<div>
							<p class="idc-testimonial__name"><?php echo esc_html($name); ?></p>
							<p class="idc-testimonial__role"><?php echo esc_html($role); ?></p>
						</div>
					</footer>
				</blockquote>
			<?php endforeach; ?>
		</div>
	</div>
</section>
