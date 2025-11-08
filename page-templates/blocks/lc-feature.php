<?php
/**
 * Feature block component.
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="feature py-5 mx-4" data-aos="fade">
	<div class="container-xl">
		<h2 class="h2 headline mb-4">As Featured In</h2>
		<div class="max-ch mb-4">
			<?= wp_kses_post( get_field( 'intro' ) ); ?>
		</div>
		<?php
		$logos = get_field( 'logos' );
		if ( $logos ) {
			?>
		<div class="feature__marquee mb-4">
			<div class="feature__track">
				<div class="swiper-wrapper">
					<?php
					foreach ( $logos as $logo ) {
						?>
					<div class="swiper-slide">
						<?= wp_get_attachment_image( $logo, 'full', false, array( 'class' => 'feature__logo img-fluid', 'alt' => get_post_meta( $logo, '_wp_attachment_image_alt', true ) ) ); ?>
					</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
			<?php
		}
		?>
		<div class="row">
			<div class="col-md-4">
				<?php
				$video = get_field( 'video' );
				if ( $video ) {
					?>
				<video controls class="w-100">
					<source src="<?= esc_url( $video['url'] ); ?>" type="<?= esc_attr( $video['mime_type'] ); ?>">
					Your browser does not support the video tag.
				</video>
					<?php
				}
				?>
			</div>
			<div class="col-md-8">
				<h3 class="headline mb-4">Publications</h3>
				<?php
				while ( have_rows( 'features' ) ) {
					the_row();
					$l = get_sub_field( 'link' );
					?>
				<a href="<?= esc_url( $l['url'] ); ?>" target="_blank" class="d-block feature__item">
					<h3 class="h4"><?= esc_html( get_sub_field( 'title' ) ); ?> <i class="fas fa-external-link-alt font-size-base"></i></h3>
					<div><?= esc_html( get_sub_field( 'description' ) ); ?></div>
				</a>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>
<?php
add_action(
	'wp_footer',
	function () {
		?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var wrapper = document.querySelector('.feature__track .swiper-wrapper');
	if (!wrapper) return;
	var wrapperWidth = wrapper.scrollWidth;
	var container = document.querySelector('.feature__marquee');
	var containerWidth = container.offsetWidth;
	// Duplicate logos for seamless loop if needed
	if (wrapperWidth < containerWidth * 2) {
			wrapper.innerHTML += wrapper.innerHTML;
			wrapperWidth = wrapper.scrollWidth;
	}
	var pxPerSecond = 40; // Adjust for desired speed
	var distance = wrapperWidth / 2;
	var duration = distance / pxPerSecond;
	gsap.to(wrapper, {
			x: -distance,
			duration: duration,
			ease: 'none',
			repeat: -1,
	});
});
</script>
		<?php
	}
);