<?php
/**
 * Full text block component.
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="full_text">
    <div class="container-xl py-5" data-aos="fade">
        <?php
        if ( get_field( 'title' ) ?? null ) {
            ?>
        <h2 class="d-none d-md-block text-center h2 headline">
            <?= wp_kses_post( get_field( 'title' ) ); ?>
        </h2>
            <?php
        }
        ?>
        <div class="max-ch mx-auto text-center">
            <?= wp_kses_post( apply_filters( 'the_content', get_field( 'content' ) ) ); ?>
        </div>
    </div>
</section>