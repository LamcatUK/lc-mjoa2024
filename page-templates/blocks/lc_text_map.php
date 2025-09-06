<?php
/**
 * Text and Map component for displaying text content alongside a map.
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

$classes = $block['className'] ?? 'py-5';

?>
<section class="feature <?= esc_attr( $classes ); ?>">
    <div class="container-xl">
        <?php
        if ( get_field( 'title' ) ?? null ) {
            ?>
        <div class="h2 text-center d-md-none" data-aos="fade">
            <?= esc_html( get_field( 'title' ) ); ?>
        </div>
            <?php
        }
        ?>
        <div class="row g-4">
            <div class="col-md-6 <?= esc_attr( $txtcol ); ?> d-flex flex-column justify-content-center"
                data-aos="<?= esc_attr( $txtanim ); ?>">
        <?php
        if ( get_field( 'title' ) ?? null ) {
            ?>
                <h2 class="d-none d-md-block h2">
                    <?= esc_html( get_field( 'title' ) ); ?>
                </h2>
            <?php
        }
        ?>
                <div><?= wp_kses_post( get_field( 'content' ) ); ?></div>
        <?php
        if ( get_field( 'link' ) ?? null ) {
            $l = get_field( 'link' );
            ?>
                <a href="<?= esc_url( $l['url'] ); ?>"
                    target="<?= esc_attr( $l['target'] ); ?>"
                    class="mt-4 btn btn-red text-center align-self-center align-self-md-start"><?= esc_html( $l['title'] ); ?></a>
            <?php
        }
        ?>
            </div>
            <div class="col-md-6 <?= esc_attr( $imgcol ); ?> d-flex align-items-center"
                data-aos="<?= esc_attr( $imganim ); ?>">
                <?php
                $map_url = get_field( 'map_url' );
                ?>
                <iframe src="<?= esc_url( $map_url ); ?>" width="100%" height="500"
                    style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</section>