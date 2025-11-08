<?php
/**
 * Hero block with background image and optional title.
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;

if ( ! empty( wp_get_attachment_image_url( get_field( 'background' ), 'full' ) ) ) {
    $bg = wp_get_attachment_image_url( get_field( 'background' ), 'full' );
} elseif ( ! empty( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ) ) {
    $bg = get_the_post_thumbnail_url( get_the_ID(), 'full' );
} else {
    $bg = get_stylesheet_directory_uri() . '/img/missing-hero.jpg';
}
$classes = $block['className'] ?? 'pb-5';
$hills   = 'Yes' === get_field( 'show_hills' )[0] ? 'hero--hills' : '';

?>
<section
    class="hero <?= esc_attr( $classes ); ?> <?= esc_attr( $hills ); ?>">
    <img src="<?= esc_url( $bg ); ?>" class="hero__bg">
</section>
<?php
if ( get_field( 'title' ) ?? null ) {
    ?>
<h1 class="hero__title">
    <?= esc_html( get_field( 'title' ) ); ?>
</h1>
    <?php
}
?>