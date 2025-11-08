<?php
/**
 * Hike Intro component for displaying an icon and introductory content.
 *
 * @package lc-mjoa2024
 */

$classes       = $block['className'] ?? 'pb-5';
$icon          = get_field( 'icon', get_the_ID() );
$svg_file_path = get_stylesheet_directory() . '/img/icons/icon--' . $icon . '.svg';

if ( file_exists( $svg_file_path ) ) {
    $svg_content = file_get_contents( $svg_file_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
} else {
    $svg_content = 'SVG file not found.';
}
?>
<section class="hike_intro mx-4 <?= esc_attr( $classes ); ?>">
    <div class="row g-4">
        <div class="col-md-2 text-center">
            <?= $svg_content; // phpcs:ignore ?>
        </div>
        <div class="col-md-10">
            <?= wp_kses_post( get_field( 'content' ) ); ?>
        </div>
    </div>
</section>