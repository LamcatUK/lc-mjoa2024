<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

$shop_page = wc_get_page_id('shop');

get_header();
$bg = get_the_post_thumbnail_url($shop_page, 'full') ?? null;
?>
<section class="hero pb-5 hero--hills">
    <img src="<?=$bg?>" class="hero__bg">
</section>
<h1 class="hero__title">
    MJ's Trading Post
</h1>
<main id="main">
    <?php
echo apply_filters('the_content', get_the_content(null, false, $shop_page));
?>
</main>
<?php

get_footer();
?>