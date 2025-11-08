<?php
/**
 * Latest News block
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;

// Get sticky posts.
$sticky = get_option( 'sticky_posts' );
if ( ! is_array( $sticky ) ) {
    $sticky = array();
}

$latest_news_posts = array();

// Get up to 4 sticky posts.
if ( ! empty( $sticky ) ) {
    $sticky_query = new WP_Query(
        array(
            'post__in'            => $sticky,
            'posts_per_page'      => 4,
            'orderby'             => 'post__in',
            'ignore_sticky_posts' => 1,
        )
    );
    foreach ( $sticky_query->posts as $p ) {
        $latest_news_posts[] = $p;
    }
}

// If less than 4, fill with regular posts (excluding stickies).
$needed = 4 - count( $latest_news_posts );
if ( $needed > 0 ) {
    $regular_query = new WP_Query(
        array(
            'post__not_in'        => $sticky,
            'posts_per_page'      => $needed,
            'ignore_sticky_posts' => 1,
        )
    );
    foreach ( $regular_query->posts as $p ) {
        $latest_news_posts[] = $p;
    }
}

// Only keep up to 4 posts.
$latest_news_posts = array_slice( $latest_news_posts, 0, 4 );

if ( ! empty( $latest_news_posts ) ) {
    ?>
<section class="related_news mx-4 pb-2">
    <div class="container-xl">
        <div class="bg-white pb-4 mb-2">
            <h2 class="headline my-3">Latest News</h2>
            <div class="related_news__grid">
                <?php
                foreach ( $latest_news_posts as $p ) {
                    setup_postdata( $p );
                    ?>
                    <a class="related_news__card" href="<?= esc_url( get_permalink( $p ) ); ?>">
                        <div class="polaroid">
                            <div class="polaroid__image">
                                <img src="<?= esc_url( get_the_post_thumbnail_url( $p->ID, 'large' ) ); ?>"
                                    alt="">
                            </div>
                            <div class="polaroid__title">
                                <?= esc_html( get_the_title( $p ) ); ?>
                            </div>
                        </div>
                    </a>
                    <?php
                }
                wp_reset_postdata();
                ?>
            </div>
            <div class="text-center mt-4">
                <a href="/news/" class="btn btn-primary">All News</a>
            </div>
        </div>
    </div>
</section>
    <?php
}