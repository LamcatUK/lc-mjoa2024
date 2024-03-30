<?php

if (!empty(wp_get_attachment_image_url(get_field('background'), 'full'))) {
    $bg = wp_get_attachment_image_url(get_field('background'), 'full');
} elseif (!empty(get_the_post_thumbnail_url(get_the_ID(), 'full'))) {
    $bg = get_the_post_thumbnail_url(get_the_ID(), 'full');
} else {
    $bg = get_stylesheet_directory_uri() . '/img/missing-hero.jpg';
}
$classes = $block['className'] ?? 'pb-5';
$hills = get_field('show_hills')[0] == 'Yes' ? 'hero--hills' : '';

?>
<section
    class="hero <?=$classes?> <?=$hills?>">
    <img src="<?=$bg?>" class="hero__bg">
</section>
<?php
if (get_field('title') ?? null) {
    ?>
<h1 class="hero__title">
    <?=get_field('title')?>
</h1>
<?php
}
?>