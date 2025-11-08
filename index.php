<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;


$page_for_posts = get_option( 'page_for_posts' );

get_header();
$bg = get_the_post_thumbnail_url( $page_for_posts, 'full' ) ?? null;

// Pagination.
$current_paged  = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
$posts_per_page = get_option( 'posts_per_page', 10 );
$sticky         = get_option( 'sticky_posts' );
if ( ! is_array( $sticky ) ) {
    $sticky = array();
}

// Calculate total regular posts (excluding stickies).
$regular_count_query = new WP_Query(
    array(
        'post_type'           => 'post',
        'post__not_in'        => $sticky,
        'posts_per_page'      => -1,
        'fields'              => 'ids',
        'ignore_sticky_posts' => 1,
    )
);
$total_regular_posts = $regular_count_query->found_posts;
wp_reset_postdata();

$total_posts   = $total_regular_posts + count( $sticky );
$max_num_pages = ceil( $total_posts / $posts_per_page );

if ( 1 === $current_paged && ! empty( $sticky ) ) {
    // Page 1: sticky + regular (up to posts_per_page).
    $args         = array(
        'post_type'           => 'post',
        'post__in'            => $sticky,
        'posts_per_page'      => count( $sticky ),
        'orderby'             => 'post__in',
        'ignore_sticky_posts' => 1,
    );
    $sticky_query = new WP_Query( $args );
    $sticky_posts = $sticky_query->posts;

    $needed        = $posts_per_page - count( $sticky_posts );
    $regular_posts = array();
    if ( $needed > 0 ) {
        $regular_query = new WP_Query(
            array(
                'post_type'           => 'post',
                'post__not_in'        => $sticky,
                'posts_per_page'      => $needed,
                'paged'               => 1,
                'ignore_sticky_posts' => 1,
            )
        );
        $regular_posts = $regular_query->posts;
    }
    $all_posts = array_merge( $sticky_posts, $regular_posts );
} else {
    // Page 2+: regular posts only, offset by posts already shown on page 1.
    $offset     = ( $posts_per_page - count( $sticky ) ) + ( $posts_per_page * ( $current_paged - 2 ) );
    $main_query = new WP_Query(
        array(
            'post_type'           => 'post',
            'post__not_in'        => $sticky,
            'posts_per_page'      => $posts_per_page,
            'offset'              => $offset,
            'ignore_sticky_posts' => 1,
        )
    );
    $all_posts  = $main_query->posts;
}
?>
<section class="hero pb-5 hero--hills">
    <img src="<?= esc_url( $bg ); ?>" class="hero__bg">
</section>
<h1 class="hero__title">
    Trail Tales &amp; Tips
</h1>
<main id="main">
    <section class="breadcrumbs container-xl text-center mb-4">
        <?php
        if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<div id="breadcrumbs" class="my-2">', '</div>' ); // phpcs:ignore
        }
        ?>
    </section>
    <section class="news_index pb-4">
        <div class="container-xl bg-white pb-4">
            <div class="max-ch mx-auto text-center mb-5">
                <?= wp_kses_post( apply_filters( 'the_content', get_the_content( null, false, $page_for_posts ) ) ); ?>
            </div>
            <div class="news_index__grid">
                <?php
                $style  = 'news_index__card--first';
                $length = 50;
                foreach ( $all_posts as $the_post ) {
                    setup_postdata( $the_post );
                    $categories = get_the_category( $the_post->ID );
                    ?>
                <a href="<?= esc_url( get_permalink( $the_post ) ); ?>"
                    class="news_index__card <?= esc_attr( $style ); ?>">
                    <div class="news_index__image">
                        <img src="<?= esc_url( get_the_post_thumbnail_url( $the_post->ID, 'large' ) ); ?>" alt="">
                    </div>
                    <div class="news_index__inner">
                        <h2><?= esc_html( get_the_title( $the_post ) ); ?></h2>
                        <p><?= wp_kses_post( wp_trim_words( get_the_content( null, false, $the_post->ID ), $length ) ); ?></p>
                        <div class="news_index__meta">
                            <div class="fs-300 fw-bold">
                                <?= esc_html( get_the_date( 'j F, Y', $the_post ) ); ?>
                            </div>
                            <?php
                            if ( $categories ) {
                                foreach ( $categories as $category ) {
                                    ?>
                            <span class="news_index__category"><?= esc_html( $category->name ); ?></span>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </a>
                    <?php
                    $style  = '';
                    $length = 20;
                }
                wp_reset_postdata();
                ?>
            </div>
            <?php
            // Pagination links.
            $big        = 999999999; // need an unlikely integer.
            $pagination = paginate_links(
                array(
                    'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                    'format'    => '?paged=%#%',
                    'current'   => max( 1, $current_paged ),
                    'total'     => $max_num_pages,
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                )
            );
            if ( $pagination ) {
                echo '<nav class="pagination mt-4">' . wp_kses_post( $pagination ) . '</nav>';
            }
            ?>
        </div>
    </section>
</main>
<?php
get_footer();