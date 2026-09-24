<?php
/**
 * Modal carrossel do corpo clínico — foto | bio | contatos opcionais.
 *
 * @package Instituto_Dr_Chao
 */

$query = new WP_Query([
	'post_type'      => 'idc_profissional',
	'posts_per_page' => -1,
	'orderby'        => ['menu_order' => 'ASC', 'title' => 'ASC'],
	'post_status'    => 'publish',
	'no_found_rows'  => true,
]);

if (!$query->have_posts()) {
	return;
}

$total = (int) $query->post_count;
?>
<div
	class="idc-team-modal"
	data-idc-team-modal
	hidden
	role="dialog"
	aria-modal="true"
	aria-labelledby="idc-team-modal-title"
>
	<div class="idc-team-modal__backdrop" data-idc-team-modal-close tabindex="-1"></div>

	<div class="idc-team-modal__dialog" role="document">
		<button
			type="button"
			class="idc-team-modal__close"
			data-idc-team-modal-close
			aria-label="<?php esc_attr_e('Fechar', 'instituto-dr-chao'); ?>"
		>
			<span aria-hidden="true">&times;</span>
		</button>

		<?php if ($total > 1) : ?>
			<button
				type="button"
				class="idc-team-modal__nav idc-team-modal__nav--prev"
				data-idc-team-modal-prev
				aria-label="<?php esc_attr_e('Profissional anterior', 'instituto-dr-chao'); ?>"
			>
				<span aria-hidden="true">‹</span>
			</button>
			<button
				type="button"
				class="idc-team-modal__nav idc-team-modal__nav--next"
				data-idc-team-modal-next
				aria-label="<?php esc_attr_e('Próximo profissional', 'instituto-dr-chao'); ?>"
			>
				<span aria-hidden="true">›</span>
			</button>
		<?php endif; ?>

		<div class="idc-team-modal__viewport">
			<div class="idc-team-modal__track" data-idc-team-modal-track>
				<?php
				$i = 0;
				while ($query->have_posts()) :
					$query->the_post();
					$post_id  = (int) get_the_ID();
					$crm      = idc_profissional_meta($post_id, 'idc_crm');
					$spec     = idc_profissional_meta($post_id, 'idc_especialidade_txt');
					$excerpt  = idc_profissional_excerpt_html($post_id, 3);
					$contacts = idc_profissional_contacts($post_id);
					$content  = apply_filters('the_content', get_the_content());
					$plain_ex = trim(wp_strip_all_tags($excerpt));
					$plain_full = trim(wp_strip_all_tags($content));
					$has_more = $plain_full !== '' && $plain_full !== $plain_ex && mb_strlen($plain_full) > mb_strlen($plain_ex) + 12;
					$active   = $i === 0;
					?>
					<article
						class="idc-team-modal__slide<?php echo $active ? ' is-active' : ''; ?>"
						data-idc-team-modal-slide
						data-idc-team-id="<?php echo esc_attr((string) $post_id); ?>"
						<?php echo $active ? '' : ' hidden'; ?>
					>
						<figure class="idc-team-modal__photo">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('large', ['loading' => 'lazy']); ?>
							<?php endif; ?>
						</figure>

						<div class="idc-team-modal__body">
							<p class="idc-eyebrow idc-eyebrow--accent"><?php esc_html_e('CORPO CLÍNICO', 'instituto-dr-chao'); ?></p>
							<h2
								class="idc-team-modal__name"
								<?php echo $active ? ' id="idc-team-modal-title"' : ''; ?>
							>
								<?php the_title(); ?>
							</h2>
							<?php if ($spec !== '') : ?>
								<p class="idc-team-modal__spec"><?php echo esc_html($spec); ?></p>
							<?php endif; ?>
							<?php if ($crm !== '') : ?>
								<p class="idc-team-modal__crm"><?php echo esc_html($crm); ?></p>
							<?php endif; ?>

							<div class="idc-team-modal__bio" data-idc-team-modal-bio>
								<div class="idc-team-modal__excerpt" data-idc-team-modal-excerpt>
									<?php echo wp_kses_post($excerpt !== '' ? $excerpt : wpautop(esc_html(wp_trim_words($plain_full, 40, '…')))); ?>
								</div>
								<?php if ($has_more) : ?>
									<div class="idc-team-modal__full" data-idc-team-modal-full hidden>
										<?php echo wp_kses_post($content); ?>
									</div>
									<button
										type="button"
										class="idc-team-modal__more"
										data-idc-team-modal-more
										aria-expanded="false"
									>
										<?php esc_html_e('Continuar lendo', 'instituto-dr-chao'); ?>
									</button>
								<?php endif; ?>
							</div>

							<?php if ($contacts !== []) : ?>
								<ul class="idc-team-modal__socials" aria-label="<?php esc_attr_e('Contato e redes', 'instituto-dr-chao'); ?>">
									<?php foreach ($contacts as $item) : ?>
										<li>
											<a
												class="idc-team-modal__social idc-team-modal__social--<?php echo esc_attr($item['type']); ?>"
												href="<?php echo esc_url($item['url']); ?>"
												<?php echo $item['type'] === 'email' ? '' : ' target="_blank" rel="noopener noreferrer"'; ?>
												aria-label="<?php echo esc_attr($item['label']); ?>"
											>
												<img
													src="<?php echo esc_url(idc_asset($item['icon'])); ?>"
													alt=""
													width="18"
													height="18"
													decoding="async"
												>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
					</article>
					<?php
					$i++;
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>

		<?php if ($total > 1) : ?>
			<p class="idc-team-modal__counter" data-idc-team-modal-counter aria-live="polite">
				<span data-idc-team-modal-counter-current>1</span>
				<span aria-hidden="true"> / </span>
				<span class="screen-reader-text"><?php esc_html_e('de', 'instituto-dr-chao'); ?> </span>
				<span data-idc-team-modal-counter-total><?php echo esc_html((string) $total); ?></span>
			</p>
		<?php endif; ?>
	</div>
</div>
