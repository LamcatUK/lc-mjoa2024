<?php
/**
 * Hikes list block to display upcoming hikes and walks.
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;

$class = $block['className'] ?? 'pb-5';
?>
<div class="hikes-list mx-4 pb-5 <?= esc_attr( $class ); ?>">
    <?php
    $cats = get_field( 'category' );

    $noun   = get_field( 'noun' ) ? get_field( 'noun' ) : 'hikes';
    $limit  = get_field( 'limit' );
    $output = lc_products_by_category( $cats, $noun, $limit ) ? lc_products_by_category( $cats, $noun, $limit ) : null;

    if ( ! empty( $output ) ) {
        if ( is_front_page() ) {
            ?>
        <h2 class="h2 headline mb-4">Upcoming Hikes &amp; Walks</h2>
            <?php
        } elseif ( is_page( 'hikes' ) ) {
            ?>
        <h2 class="h2 headline mb-4">Upcoming Hikes &amp; Walks</h2>
            <?php
        } elseif ( ! is_page( 'all-hikes' ) ) {
            ?>
        <h2 class="h2 headline mb-4">Upcoming <?= esc_html( get_the_title( get_the_ID() ) ); ?></h2>
            <?php
        }
        $e = 0;
        usort(
            $output,
            function ( $a, $b ) {
                if ( $a['start'] === $b['start'] ) {
                    return 0;
                }
                return ( $a['start'] < $b['start'] ) ? -1 : 1;
            }
        );
        $today = new DateTime( 'today' );
        foreach ( $output as $h ) {
            if ( $h['start'] < $today ) {
                continue;
            }
            ++$e;
            $product        = wc_get_product( $h['product'] );
            $stock_quantity = $product->get_stock_quantity();

            $banner       = '';
            $banner_class = '';

            if ( is_numeric( $stock_quantity ) ) {
                if ( 0 === $stock_quantity ) {
                    $banner       = 'SOLD OUT';
                    $banner_class = 'sold-out';
                } elseif ( $stock_quantity < 3 ) {
                    $banner       = '*NEARLY FULL*';
                    $banner_class = 'last-few';
                }
            } else {
                $banner = 'Stock status: ' . $product->get_stock_status(); // Out of stock, on backorder, etc.
            }
            ?>
        <a class="hikes-list__row"
            href="<?= esc_url( $h['link'] ); ?>">
            <?php
            if ( 'sold-out' === $banner_class ) {
                ?>
            <div class="hikes-list__banner--<?= esc_attr( $banner_class ); ?>">
                <?= esc_html( $banner ); ?>
            </div>
                <?php
            }
            ?>
            <img class="hikes-list__icon"
                src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icons/icon--' . $h['slug'] . '.svg' ); ?>">
            <div class="hikes-list__meta">
                <div class="hikes-list__date">
                    <?= esc_html( $h['start']->format( 'D jS F, Y' ) ); ?>
                    <?php
                    if ( 'last-few' === $banner_class ) {
                        ?>
                    <span class="hikes-list__banner--<?= esc_attr( $banner_class ); ?>">
                        <?= wp_kses_post( $banner ); ?>
                    </span>
                        <?php
                    }
                    ?>
                </div>
                <div class="hikes-list__title">
                    <?= esc_html( $h['title'] ); ?>
                </div>
                <div class="hikes-list__price">
                    <?= wp_kses_post( $h['price'] ); ?>
                </div>
                <div class="hikes-list__desc">
                    <?= esc_html( $h['product']->get_short_description() ); ?>
                </div>
            </div>
        </a>
            <?php
        }
        if ( 0 === $e ) {
            ?>
        <div class="text-center h3 headline">
            No upcoming events found
        </div>
            <?php
        }
    } else {
        ?>
        <div class="text-center h3 headline">
            No upcoming events found
        </div>
        <?php
    }

    if ( ! is_page( 'all-hikes' ) ) {
        ?>
    <div class="pt-4 text-center">
        <a href="/hikes/all-hikes/" class="btn btn-primary">All hikes</a>
    </div>
        <?php
    }
    ?>
</div>