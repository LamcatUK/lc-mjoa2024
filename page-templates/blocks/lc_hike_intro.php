<?php

$classes = $block['className'] ?? 'pb-5';
$icon = get_field('icon', get_the_ID());
$svg_file_path = get_stylesheet_directory() . '/img/icons/icon--' . $icon . '.svg';
$svg_content = file_get_contents($svg_file_path);
if (file_exists($svg_file_path)) {
    $svg_content = file_get_contents($svg_file_path);
} else {
    $svg_content = 'SVG file not found.';
}
?>
<section class="hike_intro mx-4 <?=$classes?>">
    <div class="row g-4">
        <div class="col-md-2 text-center">
            <?=$svg_content?>
        </div>
        <div class="col-md-10">
            <?=get_field('content')?>
        </div>
    </div>
</section>