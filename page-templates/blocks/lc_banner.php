<?php
/**
 * Banner block template.
 *
 * Displays a banner with title image, CTA text, and product image.
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;

$banner_link = get_the_permalink( get_field( 'product' ) );
?>
<a class="banner mb-5" href="<?= esc_url( $banner_link ); ?>">
    <div class="container-xl">
        <div class="banner__grid">
            <div class="banner__title">
                <img
                    src="<?= esc_url( wp_get_attachment_image_url( get_field( 'title_image' ), 'full' ) ); ?>">
            </div>
            <div class="banner__button">
                <?= esc_html( get_field( 'cta_text' ) ); ?>
            </div>
            <div class="banner__img"><img
                    src="<?= esc_url( wp_get_attachment_image_url( get_field( 'product_image' ), 'large' ) ); ?>">
            </div>
        </div>
    </div>
</a>