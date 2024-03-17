<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

$page_for_posts = get_option('page_for_posts');

get_header();
$bg = get_the_post_thumbnail_url($page_for_posts, 'full') ?? null;
?>
<section class="hero pb-5 hero--hills">
    <img src="<?=$bg?>" class="hero__bg">
</section>
<h1 class="hero__title">
    Trail Tales &amp; Tips
</h1>
<main id="main">
    <section class="news_index pb-4">
        <div class="container-xl bg-white py-4">
            <div class="news_index__grid">
                <?php
    $style = 'news_index__card--first';
$length = 50;
while (have_posts()) {
    the_post();
    $categories = get_the_category();
    ?>
                <a href="<?=get_the_permalink()?>"
                    class="news_index__card <?=$style?>">
                    <div class="news_index__image">
                        <img src="<?=get_the_post_thumbnail_url(get_the_ID(), 'large')?>"
                            alt="">
                    </div>
                    <div class="news_index__inner">
                        <h2><?=get_the_title()?></h2>
                        <p><?=wp_trim_words(get_the_content(), $length)?>
                        </p>
                        <div class="news_index__meta">
                            <div class="fs-300 fw-bold">
                                <?=get_the_date()?>
                            </div>
                            <?php
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
                    </div>
                </a>
                <?php
    $style = '';
    $length = 20;
}
?>
            </div>
        </div>
    </section>
</main>
<?php

get_footer();
?>