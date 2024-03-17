<section class="full_text">
    <div class="container-xl py-5" data-aos="fade">
        <?php
        if (get_field('title') ?? null) {
            ?>
        <h2 class="d-none d-md-block text-center h2">
            <?=get_field('title')?>
        </h2>
        <?php
        }
        ?>
        <div class="max-ch mx-auto text-center">
            <?=apply_filters('the_content', get_field('content'))?>
        </div>
    </div>
</section>