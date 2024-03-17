<?php

$r = new WP_Query(array(
    'posts_per_page' => 4,
));
if ($r->have_posts()) {
    ?>
<section class="related_news mx-4 pb-2">
    <div class="container-xl">
        <div class="bg-white pb-4 mb-2">
            <h2 class="headline my-3">Latest News</h2>
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
            <div class="text-center mt-4">
                <a href="/news/" class="btn btn-primary">View All</a>
            </div>
        </div>
    </div>
</section>
<?php
}
?>