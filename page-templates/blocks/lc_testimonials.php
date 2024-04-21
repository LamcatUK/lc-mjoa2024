<?php
$q = new WP_Query(array(
   'post_type' => 'testimonial',
   'posts_per_page' => -1
));
?>
<section class="testimonials py-5 mb-5">
    <div class="container-xl">
        <h2 class="headline text-center mb-4">Testimonials</h2>
        <div id="testimonials" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                $active = 'active';
while ($q->have_posts()) {
    $q->the_post();
    ?>
                <div class="carousel-item <?=$active?>">
                    <div class="testimonial">
                        <div class="testimonial__body">
                            <?=get_the_content()?>
                        </div>
                        <div class="testimonial__cite">
                            <?=get_the_title()?>
                        </div>
                    </div>
                </div>
                <?php
                $active = '';
}
?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonials" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonials" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

    </div>
</section>
<?php
add_action('wp_footer', function () {
    ?>
<script>
    function adjustCarouselHeight() {
        var carousel = document.querySelector('#testimonials .carousel-inner');
        var items = carousel.querySelectorAll('.carousel-item');

        var tallest = 0;
        items.forEach(function(item) {
            var itemHeight = item.getBoundingClientRect().height;
            console.log('item: ' + itemHeight);
            console.log('tallest: ' + tallest);
            if (itemtHeight > tallest) {
                tallest = item.offsetHeight;
            }
        });

        carousel.style.height = tallest + 'px';
    }

    document.addEventListener('DOMContentLoaded', adjustCarouselHeight);
    window.addEventListener('resize', adjustCarouselHeight);
</script>
<?php
});
?>