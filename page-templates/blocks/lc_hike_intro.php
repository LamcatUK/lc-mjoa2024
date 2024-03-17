<?php

$classes = $block['className'] ?? 'py-5';
$icon = get_field('icon', get_the_ID());
?>
<section class="hike_intro mx-4 <?=$classes?>">
    <div class="row g-4">
        <div class="col-md-2 text-center">
            <img src="<?=get_stylesheet_directory_uri()?>/img/icons/icon--<?=$icon?>.svg"
                alt="<?=get_the_title()?>">

        </div>
        <div class="col-md-10">
            <?=get_field('content')?>
        </div>
    </div>
</section>