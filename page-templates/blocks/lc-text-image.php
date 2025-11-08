<?php
/**
 * Text + Image component
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;

$txtcol = 'text' === get_field( 'order' ) ? 'order-2 order-md-1' : 'order-2 order-md-2';
$imgcol = 'text' === get_field( 'order' ) ? 'order-1 order-md-2' : 'order-1 order-md-1';

$txtanim = 'text' === get_field( 'order' ) ? 'fade-right' : 'fade-left';
$imganim = 'text' === get_field( 'order' ) ? 'fade-left' : 'fade-right';

$txtanim = 'fade';
$imganim = 'fade';

$polaroid = get_field( 'polaroid' ) ?? null;

$classes = $block['className'] ?? 'py-5';

$heading_level = get_field( 'heading_level' ) ? get_field( 'heading_level' ) : 'h2';

?>
<section class="text_image mx-4 <?= esc_attr( $classes ); ?>">
    <div class="container-xl">
        <?php
        if ( get_field( 'title' ) ?? null ) {
            ?>
        <div class="h2 text-center d-md-none headline mb-5" data-aos="fade">
            <?= esc_html( get_field( 'title' ) ); ?>
        </div>
            <?php
        }
        ?>
        <div class="row g-4">
            <div class="col-md-8 <?= esc_attr( $txtcol ); ?> d-flex flex-column justify-content-center"
                data-aos="<?= esc_attr( $txtanim ); ?>">
        <?php
        if ( get_field( 'title' ) ?? null ) {
            ?>
                <<?= esc_attr( $heading_level ); ?> class="d-none d-md-block <?= esc_attr( $heading_level ); ?> headline">
                    <?= esc_html( get_field( 'title' ) ); ?>
                </<?= esc_attr( $heading_level ); ?>>
                <?php
        }
        ?>
                <div><?= wp_kses_post( get_field( 'content' ) ); ?>
                </div>
        <?php
        if ( get_field( 'link' ) ?? null ) {
            $l = get_field( 'link' );
            ?>
                <a href="<?= esc_url( $l['url'] ); ?>"
                    target="<?= esc_attr( $l['target'] ); ?>"
                    class="mt-4 btn btn-primary text-center align-self-center align-self-md-start"><?= esc_html( $l['title'] ); ?></a>
            <?php
        }
        ?>
            </div>
            <div class="col-md-4 <?= esc_attr( $imgcol ); ?> d-flex align-items-center mb-4"
                data-aos="<?= esc_attr( $imganim ); ?>">
        <?php
        if ( $polaroid ) {
            ?>
                <div class="polaroid mx-auto">
                    <div class="polaroid__image">
                        <img src="<?= esc_url( wp_get_attachment_image_url( get_field( 'image' ), 'large' ) ); ?>"
                            alt="<?= esc_attr( get_field( 'caption' ) ); ?>">
                    </div>
                    <div class="polaroid__title">
                        <?= esc_html( get_field( 'caption' ) ); ?>
                    </div>
                </div>
            <?php
        } else {
            ?>
                <img src="<?= esc_url( wp_get_attachment_image_url( get_field( 'image' ), 'large' ) ); ?>"
                    alt="<?= esc_attr( get_field( 'title' ) ); ?>"
                    class="feature__img mx-auto">
            <?php
        }
        ?>
            </div>
        </div>
    </div>
</section>