<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;
get_header();

$post_content = get_the_content();
$blocks       = parse_blocks( $post_content );
foreach ( $blocks as $block ) {
    if ( 'acf/lc-hero' === $block['blockName'] ) {
        echo wp_kses_post( render_block( $block ) );
    }
}
?>
<main id="main">
    <?php
    if ( ! is_front_page() ) {
        ?>
    <section class="breadcrumbs container-xl text-center mb-4">
        <?php
        if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<div id="breadcrumbs" class="my-2">', '</div>' );
        }
        ?>
    </section>
        <?php
    }
    the_post();
    foreach ( $blocks as $block ) {
        if ( 'acf/lc-hero' !== $block['blockName'] ) {
            echo do_shortcode( render_block( $block ) );
        }
    }
    ?>
</main>
<?php
get_footer();
