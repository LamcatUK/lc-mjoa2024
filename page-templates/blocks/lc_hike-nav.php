<?php
$classes = $block['className'] ?? 'mb-5';
?>
<section class="hike-nav py-5 <?=$classes?>">
    <?php
    if (get_field('content')) {
        ?>
    <div class="hike-nav__content mx-4 mb-4" data-aos="fade-up">
        <h2 class="headline">
            <?=get_field('title')?>
        </h2>
        <div class="row">
            <div class="col-md-8 mb-4">
                <?=get_field('content')?>
            </div>
            <div class="col-md-4">
                <a href="/hikes/" class="btn btn-primary">Find out more</a>
            </div>
        </div>
    </div>
    <?php
    }
?>
    <div class="mx-4 mb-4 hike-nav__slider d-flex flex-wrap gap-2 justify-content-center" data-aos="fade-up">
        <?php
    $hikes = get_page_by_path('hikes', OBJECT, 'page');
$child_pages = get_pages(array('child_of' => $hikes->ID));
$all = new stdClass();
foreach ($child_pages as $page) {
    if ($page->post_title == 'All Hikes') {
        $all = $page;
        continue;
    }
    $img = get_the_post_thumbnail_url($page->ID, 'large');
    $icon = get_field('icon', $page->ID);
    $icon = 'icon--' . $icon;
    ?>
        <a class="hike-nav__card <?=$icon?>"
            href="<?=get_the_permalink($page->ID)?>">
            <div class="polaroid">
                <div class="polaroid__image">
                    <img src="<?=$img?>"
                        alt="<?=$page->post_title?>">
                </div>
                <div class="polaroid__title"><?=$page->post_title?>
                </div>
            </div>
        </a>
        <?php
}
?>
    </div>
    <div class="text-center">
        <a href="<?=get_the_permalink($all->ID)?>"
            class="btn btn-primary">All Hikes</a>
    </div>
</section>
<script>
    document.querySelectorAll('.polaroid__title').forEach(function(element) {
        if (element.textContent.trim().length > 20) {
            // console.log(element.textContent.trim().length);
            element.style.fontSize = '1.8rem';
            element.style.lineHeight = '0.8';
        }
    });
</script>