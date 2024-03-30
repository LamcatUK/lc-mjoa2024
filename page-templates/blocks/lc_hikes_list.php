<?php
$class = $block['className'] ?? 'pb-5';
?>
<div class="hikes-list mx-4 pb-5 <?=$class?>">
    <?php
    $cats = get_field('category');

$noun = get_field('noun') ?: 'hikes';
$limit = get_field('limit');
$output = lc_products_by_category($cats, $noun, $limit) ?: null;

if (!empty($output)) {
    if (is_front_page()) {
        ?>
    <h2 class="h2 headline mb-4">Upcoming Hikes &amp; Walks
    </h2>
    <?php
    } elseif (is_page('hikes')) {
        ?>
    <h2 class="h2 headline mb-4">Upcoming Hikes &amp; Walks
    </h2>
    <?php
    } elseif (! is_page('all-hikes')) {
        ?>
    <h2 class="h2 headline mb-4">Upcoming
        <?=get_the_title(get_the_ID())?>
    </h2>
    <?php
    }
    $e = 0;
    usort($output, function ($a, $b) {
        if ($a['start'] == $b['start']) {
            return 0;
        }
        return ($a['start'] < $b['start']) ? -1 : 1;
    });
    $today = new DateTime('today');
    foreach ($output as $h) {
        if ($h['start'] < $today) {
            continue;
        }
        $e++;
        ?>
    <a class="hikes-list__row"
        href="<?=$h['link']?>">
        <img class="hikes-list__icon"
            src="<?=get_stylesheet_directory_uri()?>/img/icons/icon--<?=$h['slug']?>.svg">
        <div class="hikes-list__meta">
            <div class="hikes-list__date">
                <?=$h['start']->format('jS F, Y')?>
            </div>
            <div class="hikes-list__title">
                <?=$h['title']?>
            </div>
            <div class="hikes-list__price">
                <?=$h['price']?>
            </div>
            <div class="hikes-list__desc">
                <?=$h['product']->get_short_description()?>
            </div>
        </div>
    </a>
    <?php
    }
    if ($e == 0) {
        ?>
    <div class="text-center h3 headline">
        No upcoming events found
    </div>
    <?php
    }
} else {
    ?>
    <div class="text-center h3 headline">
        No upcoming events found
    </div>
    <?php
}

if (! is_page('all-hikes')) {
    ?>
    <div class="pt-4 text-center">
        <a href="/hikes/all-hikes/" class="btn btn-primary">All hikes</a>
    </div>
    <?php
}

?>
</div>