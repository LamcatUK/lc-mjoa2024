<?php
$classes = $block['className'] ?? 'mb-5';
$hasContent = get_field('content') ? 'hike-nav--content' : null;
?>
<section
    class="hike-nav py-5 <?=$classes?> <?=$hasContent?>">
    <?php
    if (get_field('content') ?? null) {
        ?>
    <div class="hike-nav__content mx-4 mb-4" data-aos="fade-up">
        <h2 class="headline">
            <?=get_field('title')?>
        </h2>
        <div class="mb-4">
            <?=get_field('content')?>
        </div>
        <div class="text-center">
            <a href="/hikes/" class="btn btn-primary">Find out more</a>
        </div>
    </div>
    <?php
    }
?>
    <div class="mx-4 mx-lg-auto mb-4 hike-nav__inner d-flex flex-wrap gap-2 justify-content-center" data-aos="fade-up">
        <?php
    $hikes = get_page_by_path('hikes', OBJECT, 'page');
$child_pages = get_pages(array('child_of' => $hikes->ID,'sort_column' => 'menu_order'));
$all = new stdClass();
$today = new DateTime('today');
foreach ($child_pages as $page) {
    if ($page->post_title == 'All Hikes') {
        $all = $page;
        continue;
    }

    $category_slug = $page->post_name;

    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $category_slug,
            ),
        ),
    );

    $q = new WP_Query($args);

    $product_count = 0;
    while ($q->have_posts()) {
        $q->the_post();
        $start = get_post_meta(get_the_ID(), 'WooCommerceEventsDate', true);
        $start = new DateTime($start);
        if ($start > $today) {
            $product_count++;
        }
    }

    $img = get_the_post_thumbnail_url($page->ID, 'large');
    $icon = get_field('icon', $page->ID);
    $icon = 'icon--' . $icon;
    ?>
        <a class="hike-nav__card <?=$icon?>"
            href="<?=get_the_permalink($page->ID)?>">
            <div class="polaroid">
                <?php
                if ($product_count > 0) {
                    ?>
                <div class="count"><?=$product_count?></div>
                <?php
                }
    ?>
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
        <div class="text-center pt-4">
            <a href="<?=get_the_permalink($all->ID)?>"
                class="btn btn-primary">All Hikes</a>
        </div>
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