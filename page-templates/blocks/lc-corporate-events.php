<?php
/**
 * Corporate Events routes block.
 *
 * Routes grouped into County tabs. Each route is a polaroid-style card
 * (matching .hike-nav__card) that opens a popup with the full detail.
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;

$classes = $block['className'] ?? 'py-5';
$uid     = isset( $block['id'] ) ? sanitize_title( $block['id'] ) : uniqid( 'cr' );

// Preferred county display order; any others fall in alphabetically after.
$county_order = array( 'london', 'kent', 'surrey', 'sussex' );

$counties = get_terms(
	array(
		'taxonomy'   => 'event_county',
		'hide_empty' => true,
	)
);

if ( is_wp_error( $counties ) || empty( $counties ) ) {
	if ( ! empty( $is_preview ) ) {
		echo '<p class="px-4"><em>No routes yet. Add routes under <strong>Corporate Events &rarr; Add New Route</strong> and assign each a County.</em></p>';
	}
	return;
}

usort(
	$counties,
	function ( $a, $b ) use ( $county_order ) {
		$ia = array_search( $a->slug, $county_order, true );
		$ib = array_search( $b->slug, $county_order, true );
		$ia = ( false === $ia ) ? PHP_INT_MAX : $ia;
		$ib = ( false === $ib ) ? PHP_INT_MAX : $ib;
		return ( $ia === $ib ) ? strcasecmp( $a->name, $b->name ) : $ia <=> $ib;
	}
);

$modals = '';
?>
<section class="corporate-routes <?= esc_attr( $classes ); ?>">
	<div class="container-xl">

		<ul class="nav nav-pills corporate-routes__tabs justify-content-center flex-wrap gap-2 mb-5" role="tablist">
			<?php
			$first = true;
			foreach ( $counties as $county ) {
				$tab_id = $uid . '-' . $county->slug;
				?>
			<li class="nav-item" role="presentation">
				<button class="nav-link <?= $first ? 'active' : ''; ?>" id="<?= esc_attr( $tab_id ); ?>-tab"
					data-bs-toggle="tab" data-bs-target="#<?= esc_attr( $tab_id ); ?>" type="button" role="tab"
					aria-controls="<?= esc_attr( $tab_id ); ?>" aria-selected="<?= $first ? 'true' : 'false'; ?>">
					<?= esc_html( $county->name ); ?>
				</button>
			</li>
				<?php
				$first = false;
			}
			?>
		</ul>

		<div class="tab-content">
			<?php
			$first = true;
			foreach ( $counties as $county ) {
				$tab_id = $uid . '-' . $county->slug;
				$routes = new WP_Query(
					array(
						'post_type'      => 'corporate_event',
						'posts_per_page' => -1,
						'orderby'        => array(
							'menu_order' => 'ASC',
							'title'      => 'ASC',
						),
						'tax_query'      => array(
							array(
								'taxonomy' => 'event_county',
								'field'    => 'term_id',
								'terms'    => $county->term_id,
							),
						),
					)
				);
				?>
			<div class="tab-pane fade <?= $first ? 'show active' : ''; ?>" id="<?= esc_attr( $tab_id ); ?>"
				role="tabpanel" aria-labelledby="<?= esc_attr( $tab_id ); ?>-tab" tabindex="0">
				<div class="corporate-routes__inner d-flex flex-wrap justify-content-center gap-4">
					<?php
					while ( $routes->have_posts() ) {
						$routes->the_post();

						$id         = get_the_ID();
						$title      = get_the_title();
						$distance   = get_field( 'distance', $id );
						$ascent     = get_field( 'ascent', $id );
						$descent    = get_field( 'descent', $id );
						$time       = get_field( 'approx_time', $id );
						$difficulty = get_field( 'difficulty', $id );
						$blurb      = get_field( 'blurb', $id );
						$thumb      = get_the_post_thumbnail_url( $id, 'large' );
						$modal_id   = $uid . '-route-' . $id;
						?>
					<button type="button" class="corporate-routes__card" data-bs-toggle="modal"
						data-bs-target="#<?= esc_attr( $modal_id ); ?>" aria-label="View <?= esc_attr( $title ); ?>">
						<div class="polaroid">
							<div class="polaroid__image">
								<?php if ( $thumb ) : ?>
								<img src="<?= esc_url( $thumb ); ?>" alt="<?= esc_attr( $title ); ?>">
								<?php endif; ?>
							</div>
							<div class="polaroid__title"><?= esc_html( $title ); ?></div>
							<?php if ( $distance || $time || $difficulty ) : ?>
							<div class="polaroid__meta">
								<?php if ( $distance ) : ?><span><?= esc_html( $distance ); ?></span><?php endif; ?>
								<?php if ( $time ) : ?><span><?= esc_html( $time ); ?></span><?php endif; ?>
								<?php if ( $difficulty ) : ?><span><?= esc_html( $difficulty ); ?></span><?php endif; ?>
							</div>
							<?php endif; ?>
						</div>
					</button>
						<?php
						// Build the modal, output later (moved to <body> via JS).
						ob_start();
						?>
					<div class="modal fade route-modal" id="<?= esc_attr( $modal_id ); ?>" tabindex="-1"
						aria-labelledby="<?= esc_attr( $modal_id ); ?>-label" aria-hidden="true">
						<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
							<div class="modal-content route-modal__content">
								<div class="modal-header border-0">
									<h2 class="modal-title h3 headline" id="<?= esc_attr( $modal_id ); ?>-label">
										<?= esc_html( $title ); ?>
									</h2>
									<button type="button" class="btn-close" data-bs-dismiss="modal"
										aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<?php if ( $thumb ) : ?>
									<img class="route-modal__img mb-4" src="<?= esc_url( $thumb ); ?>"
										alt="<?= esc_attr( $title ); ?>">
									<?php endif; ?>
									<ul class="route-modal__stats list-unstyled row g-3 mb-4">
										<?php if ( $distance ) : ?>
										<li class="col-6 col-md-3"><span class="route-modal__stat-label">Distance</span><span class="route-modal__stat-value"><?= esc_html( $distance ); ?></span></li>
										<?php endif; ?>
										<?php if ( $ascent ) : ?>
										<li class="col-6 col-md-3"><span class="route-modal__stat-label">Ascent</span><span class="route-modal__stat-value"><?= esc_html( $ascent ); ?></span></li>
										<?php endif; ?>
										<?php if ( $descent ) : ?>
										<li class="col-6 col-md-3"><span class="route-modal__stat-label">Descent</span><span class="route-modal__stat-value"><?= esc_html( $descent ); ?></span></li>
										<?php endif; ?>
										<?php if ( $time ) : ?>
										<li class="col-6 col-md-3"><span class="route-modal__stat-label">Time</span><span class="route-modal__stat-value"><?= esc_html( $time ); ?></span></li>
										<?php endif; ?>
										<?php if ( $difficulty ) : ?>
										<li class="col-6 col-md-3"><span class="route-modal__stat-label">Difficulty</span><span class="route-modal__stat-value"><?= esc_html( $difficulty ); ?></span></li>
										<?php endif; ?>
									</ul>
									<?php if ( $blurb ) : ?>
									<div class="route-modal__blurb"><?= wp_kses_post( $blurb ); ?></div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
						<?php
						$modals .= ob_get_clean();
					}
					wp_reset_postdata();
					?>
				</div>
			</div>
				<?php
				$first = false;
			}
			?>
		</div>
	</div>

	<div class="corporate-routes__modals">
		<?= $modals; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped output above. ?>
	</div>
</section>
<script>
	(function () {
		function ready(fn) {
			if (document.readyState !== 'loading') { fn(); } else { document.addEventListener('DOMContentLoaded', fn); }
		}
		ready(function () {
			// Move modals to <body> so transformed ancestors don't break Bootstrap's fixed positioning.
			document.querySelectorAll('.corporate-routes__modals .route-modal').forEach(function (m) {
				document.body.appendChild(m);
			});
			// Shrink long polaroid titles to fit, matching the hike-nav behaviour.
			document.querySelectorAll('.corporate-routes .polaroid__title').forEach(function (el) {
				if (el.textContent.trim().length > 18) {
					el.style.fontSize = '1.6rem';
					el.style.lineHeight = '0.9';
				}
			});
		});
	})();
</script>
