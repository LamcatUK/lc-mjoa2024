<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;

get_header();
$img = get_the_post_thumbnail_url( get_the_ID(), 'full' );
?>
<main id="main" class="blog">
    <?php
    $content = get_the_content();
    $blocks  = parse_blocks( $content );
    $sidebar = array();
    $after;
    ?>
    <section class="breadcrumbs container-xl">
        <?php
        if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<div id="breadcrumbs" class="my-2">', '</div>' );
        }
        ?>
    </section>
    <div class="container-xl">
        <div class="row g-2">
            <div class="col-lg-8 order-2 order-lg-1">
                <img src="<?= esc_url( $img ); ?>" alt="" class="blog__image">
                <div class="blog__content bg-white pt-4 mb-2">
                    <h1 class="blog__title"><?= esc_html( get_the_title() ); ?></h1>
                    <div class="news_index__meta mb-2">
                        <div class="fs-300 fw-bold">
                            <?= esc_html( get_the_date() ); ?>
                        </div>
                        <?php
                        $categories = get_the_category();

                        if ( $categories ) {
                            foreach ( $categories as $category ) {
                                ?>
                        <span class="news_index__category"><?= esc_html( $category->name ); ?></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <?php
                    $count = estimate_reading_time_in_minutes( get_the_content(), 200, true, true );
                    ?>
                    <div class="reading"><?= wp_kses_post( $count ); ?></div>
                    <?php
                    foreach ( $blocks as $block ) {
                        if ( 'core/heading' === $block['blockName'] ) {
                            if ( ! array_key_exists( 'level', $block['attrs'] ) ) {
                                $heading  = wp_strip_all_tags( $block['innerHTML'] );
                                $block_id = sanitize_title( $heading );
                                echo '<a id="' . esc_attr( $block_id ) . '" class="anchor"></a>';
                                $sidebar[ $heading ] = $block_id;
                            }
                        }
                        echo wp_kses_post( render_block( $block ) );
                    }

                    echo wp_kses_post( lc_post_nav() );
                    ?>
                    <hr>
                </div>
            </div>
            <div class="col-lg-4 order-1 order-lg-2">
                <div class="sidebar pb-2">
                    <div class="h6 d-lg-none headline mb-0 collapsed" data-bs-toggle="collapse" href="#links"
                        role="button">Quick Links</div>
                    <div class="h6 d-none d-lg-block headline">Quick Links</div>
                    <div class="collapse d-lg-block" id="links">
                        <ul class="pt-3 pt-lg-0 ps-0 ff-body">
                            <?php
                            foreach ( $sidebar as $sidebar_title => $sidebar_link ) {
                                ?>
                            <li><a href="#<?= esc_attr( $sidebar_link ); ?>"><?= esc_html( $sidebar_title ); ?></a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $cats = get_the_category();
    $ids  = wp_list_pluck( $cats, 'term_id' );
    $r    = new WP_Query(
        array(
            'category__in'   => $ids,
            'posts_per_page' => 3,
            'post__not_in'   => array( get_the_ID() ),
        )
    );
    if ( $r->have_posts() ) {
        ?>
    <section class="related_news pb-2">
        <div class="container-xl">
            <div class="bg-white pb-4 mb-2">
                <h2 class="headline mb-3">Related News</h2>
                <div class="related_news__grid">
                    <?php
                    while ( $r->have_posts() ) {
                        $r->the_post();
                        ?>
                    <a class="related_news__card" href="<?= esc_url( get_the_permalink() ); ?>">
                        <div class="polaroid">
                            <div class="polaroid__image">
                                <img src="<?= esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ); ?>"
                                    alt="">
                            </div>
                            <div class="polaroid__title">
                                <?= esc_html( get_the_title() ); ?>
                            </div>
                        </div>
                    </a>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
                    <?php
    }
    wp_reset_postdata();
    ?>
</main>
<?php
get_footer();