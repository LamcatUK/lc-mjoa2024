<?php
$link = get_the_permalink(get_field('product'));
?>
<a class="banner mb-5" href="<?=$link?>">
    <div class="container-xl">
        <div class="banner__grid">
            <div class="banner__title">
                <img
                    src="<?=wp_get_attachment_image_url(get_field('title_image'), 'full')?>">
            </div>
            <div class="banner__button">
                <?=get_field('cta_text')?>
            </div>
            <div class="banner__img"><img
                    src="<?=wp_get_attachment_image_url(get_field('product_image'), 'large')?>">
            </div>
        </div>
    </div>
</a>