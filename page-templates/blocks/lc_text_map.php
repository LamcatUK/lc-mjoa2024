<?php
$txtcol = get_field('order') == 'text' ? 'order-2 order-md-1' : 'order-2 order-md-2';
$imgcol = get_field('order') == 'text' ? 'order-1 order-md-2' : 'order-1 order-md-1';

$txtanim = get_field('order') == 'text' ? 'fade-right' : 'fade-left';
$imganim = get_field('order') == 'text' ? 'fade-left' : 'fade-right';

$txtanim = 'fade';
$imganim = 'fade';

$classes = $block['className'] ?? 'py-5';

?>
<section class="feature <?=$classes?>">
    <div class="container-xl">
        <?php
        if (get_field('title') ?? null) {
            ?>
        <div class="h2 text-center d-md-none" data-aos="fade">
            <?=get_field('title')?>
        </div>
        <?php
        }
?>
        <div class="row g-4">
            <div class="col-md-6 <?=$txtcol?> d-flex flex-column justify-content-center"
                data-aos="<?=$txtanim?>">
                <?php
        if (get_field('title') ?? null) {
            ?>
                <h2 class="d-none d-md-block h2">
                    <?=get_field('title')?>
                </h2>
                <?php
        }
?>
                <div><?=get_field('content')?>
                </div>
                <?php
if (get_field('link') ?? null) {
    $l = get_field('link');
    ?>
                <a href="<?=$l['url']?>"
                    target="<?=$l['target']?>"
                    class="mt-4 btn btn-red text-center align-self-center align-self-md-start"><?=$l['title']?></a>
                <?php
}
?>
            </div>
            <div class="col-md-6 <?=$imgcol?> d-flex align-items-center"
                data-aos="<?=$imganim?>">
                <?php
$map_url = get_field('map_url');

?>
                <iframe src="<?=$map_url?>" width="100%" height="500"
                    style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</section>