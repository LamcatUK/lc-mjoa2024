<?php
$txtcol = get_field('order') == 'text' ? 'order-2 order-md-1' : 'order-2 order-md-2';
$imgcol = get_field('order') == 'text' ? 'order-1 order-md-2' : 'order-1 order-md-1';

$txtanim = get_field('order') == 'text' ? 'fade-right' : 'fade-left';
$imganim = get_field('order') == 'text' ? 'fade-left' : 'fade-right';

$txtanim = 'fade';
$imganim = 'fade';

$polaroid = get_field('polaroid') ?? null;

$classes = $block['className'] ?? 'py-5';

?>
<section class="text_image mx-4 <?=$classes?>">
    <div class="container-xl">
        <?php
        if (get_field('title') ?? null) {
            ?>
        <div class="h2 text-center d-md-none headline mb-5" data-aos="fade">
            <?=get_field('title')?>
        </div>
        <?php
        }
?>
        <div class="row g-4">
            <div class="col-md-8 <?=$txtcol?> d-flex flex-column justify-content-center"
                data-aos="<?=$txtanim?>">
                <?php
        if (get_field('title') ?? null) {
            ?>
                <h2 class="d-none d-md-block h2 headline">
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
                    class="mt-4 btn btn-primary text-center align-self-center align-self-md-start"><?=$l['title']?></a>
                <?php
}
?>
            </div>
            <div class="col-md-4 <?=$imgcol?> d-flex align-items-center mb-4"
                data-aos="<?=$imganim?>">
                <?php
                if ($polaroid) {

                    ?>
                <div class="polaroid mx-auto">
                    <div class="polaroid__image">
                        <img src="<?=wp_get_attachment_image_url(get_field('image'), 'large')?>"
                            alt="<?=get_field('caption')?>">
                    </div>
                    <div class="polaroid__title">
                        <?=get_field('caption')?>
                    </div>
                </div>
                <?php
                } else {
                    ?>
                <img src="<?=wp_get_attachment_image_url(get_field('image'), 'large')?>"
                    alt="<?=get_field('title')?>"
                    class="feature__img mx-auto">
                <?php
                }
?>
            </div>
        </div>
    </div>
</section>