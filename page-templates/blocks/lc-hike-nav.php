<?php
/**
 * Hike Navigation block.
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;

$classes     = $block['className'] ?? 'mb-5';
$has_content = get_field( 'content' ) ? 'hike-nav--content' : null;
?>
<section
    class="hike-nav py-5 <?= esc_attr( $classes ); ?> <?= esc_attr( $has_content ); ?>">
    <?php
    if ( get_field( 'content' ) ?? null ) {
        ?>
    <div class="hike-nav__content mx-4 mb-4" data-aos="fade-up">
        <h2 class="headline">
            <?= esc_html( get_field( 'title' ) ); ?>
        </h2>
        <div class="mb-4">
            <?= wp_kses_post( get_field( 'content' ) ); ?>
        </div>
        <div class="text-center">
            <a href="/hikes/" class="btn btn-primary">Find out more</a>
        </div>
    </div>
        <?php
    }
    ?>
    <div class="mx-4 mx-lg-auto mb-4 hike-nav__inner d-flex flex-wrap gap-2 justify-content-center" data-aos="fade-up">
    <?php
    $hikes       = get_page_by_path( 'hikes', OBJECT, 'page' );
    $child_pages = get_pages(
        array(
            'child_of'    => $hikes->ID,
            'sort_column' => 'menu_order',
        )
    );

    $all   = new stdClass();
    $today = new DateTime( 'today' );

    foreach ( $child_pages as $child_page ) {
        if ( 'All Hikes' === $child_page->post_title ) {
            $all = $child_page;
            continue;
        }

        $category_slug = $child_page->post_name;

        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => $category_slug,
                ),
            ),
        );

        $q = new WP_Query( $args );

        $product_count = 0;

        while ( $q->have_posts() ) {
            $q->the_post();
            $start = get_post_meta( get_the_ID(), 'WooCommerceEventsDate', true );
            $start = new DateTime( $start );
            if ( $start > $today ) {
                ++$product_count;
            }
        }

        $img  = get_the_post_thumbnail_url( $child_page->ID, 'large' );
        $icon = get_field( 'icon', $child_page->ID );
        $icon = 'icon--' . $icon;
        ?>
            <a class="hike-nav__card <?= esc_attr( $icon ); ?>"
                href="<?= esc_url( get_the_permalink( $child_page->ID ) ); ?>">
                <div class="polaroid">
                    <?php
                    if ( $product_count > 0 ) {
                        ?>
                    <div class="count"><?= esc_html( $product_count ); ?></div>
                        <?php
                    }
                    ?>
                    <div class="polaroid__image">
                        <img src="<?= esc_url( $img ); ?>"
                            alt="<?= esc_html( $child_page->post_title ); ?>">
                    </div>
                    <div class="polaroid__title"><?= esc_html( $child_page->post_title ); ?>
                    </div>
                </div>
            </a>
            <?php
    }
    ?>
        <div class="text-center pt-4">
            <a href="<?= esc_url( get_the_permalink( $all->ID ) ); ?>"
                class="btn btn-primary">All Hikes</a>
        </div>
    </div>
</section>
<script>
    document.querySelectorAll('.polaroid__title').forEach(function(element) {
        if (element.textContent.trim().length > 20) {
            // console.log(element.textContent.trim().length);
            element.style.fontSize = '1.8rem';
            element.style.lineHeight = '0.8';
        }
    });
</script>
<?php

wp_reset_postdata();