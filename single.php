<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
get_header();
$img = get_the_post_thumbnail_url(get_the_ID(), 'full');
?>
<main id="main" class="blog">
    <?php
    $content = get_the_content();
$blocks = parse_blocks($content);
$sidebar = array();
$after;
?>
    <section class="breadcrumbs container-xl">
        <?php
if (function_exists('yoast_breadcrumb')) {
    yoast_breadcrumb('<div id="breadcrumbs" class="my-2">', '</div>');
}
?>
    </section>
    <div class="container-xl">
        <div class="row g-2">
            <div class="col-lg-8 order-1">
                <img src="<?=$img?>" alt="" class="blog__image">
                <div class="blog__content bg-white pt-4 mb-2">
                    <h1 class="blog__title"><?=get_the_title()?></h1>
                    <div class="news_index__meta mb-2">
                        <div class="fs-300 fw-bold">
                            <?=get_the_date()?>
                        </div>
                        <?php

$categories = get_the_category();

if ($categories) {
    foreach ($categories as $category) {
        ?>
                        <span
                            class="news_index__category"><?=esc_html($category->name)?></span>
                        <?php
    }
}
?>
                    </div>
                    <?php
$count = estimate_reading_time_in_minutes(get_the_content(), 200, true, true);
?>
                    <div class="reading"><?=$count?></div>
                    <?php

foreach ($blocks as $block) {
    if ($block['blockName'] == 'core/heading') {
        if (!array_key_exists('level', $block['attrs'])) {
            $heading = strip_tags($block['innerHTML']);
            $id = acf_slugify($heading);
            echo '<a id="' . $id . '" class="anchor"></a>';
            $sidebar[$heading] = $id;
        }
    }
    echo render_block($block);
}

echo lc_post_nav();

?>
                    <hr>
                </div>
            </div>
            <div class="col-lg-4 order-2">
                <div class="sidebar pb-2">
                    <?php
                    if (isset($sidebar)) {
                        echo '<div>Quicklinks</div>';
                        foreach ($sidebar as $heading => $id) {
                            echo '<li><a href="#' . $id . '">' . $heading . '</a></li>';
                        }
                    }
?>
                </div>
            </div>
        </div>
    </div>
    <?php
            $cats = get_the_category();
$ids = wp_list_pluck($cats, 'term_id');
$r = new WP_Query(array(
    'category__in' => $ids,
    'posts_per_page' => 3,
    'post__not_in' => array(get_the_ID())
));
if ($r->have_posts()) {
    ?>
    <section class="related_news pb-2">
        <div class="container-xl">
            <div class="bg-white pb-4 mb-2">
                <h2 class="headline mb-3">Related News</h2>
                <div class="related_news__grid">
                    <?php
while ($r->have_posts()) {
    $r->the_post();
    ?>
                    <a class="related_news__card"
                        href="<?=get_the_permalink()?>">
                        <div class="polaroid">
                            <div class="polaroid__image">
                                <img src="<?=get_the_post_thumbnail_url(get_the_ID(), 'large')?>"
                                    alt="">
                            </div>
                            <div class="polaroid__title">
                                <?=get_the_title()?>
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
?>
</main>
<?php
get_footer();
?>