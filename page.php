<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
get_header();

$post_content = get_the_content();
$blocks = parse_blocks($post_content);
foreach ($blocks as $block) {
    if ($block['blockName'] == 'acf/lc-hero') {
        echo render_block($block);
    }
}

?>
<main id="main">
    <?php
    if (!is_front_page()) {
        ?>
    <section class="breadcrumbs container-xl text-center mb-4">
        <?php
if (function_exists('yoast_breadcrumb')) {
    yoast_breadcrumb('<div id="breadcrumbs" class="my-2">', '</div>');
}
        ?>
    </section>
    <?php
    }
the_post();
foreach ($blocks as $block) {
    if ($block['blockName'] != 'acf/lc-hero') {
        echo do_shortcode(render_block($block));
    }
}
// the_content();
?>
</main>
<?php
get_footer();
?>