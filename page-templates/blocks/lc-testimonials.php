<?php
/**
 * Testimonials block template.
 *
 * Displays a carousel of testimonials.
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;

$q = new WP_Query(
    array(
        'post_type'      => 'testimonial',
        'posts_per_page' => -1,
    )
);
?>
<section class="testimonials py-5 mb-5" style="min-height:380px">
    <div class="container-xl">
        <h2 class="headline text-center mb-4">Testimonials</h2>
        <div id="testimonials" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                $active = 'active';
                while ( $q->have_posts() ) {
                    $q->the_post();
                    ?>
                <div class="carousel-item <?= esc_attr( $active ); ?>">
                    <div class="testimonial">
                        <div class="testimonial__body">
                            <?= wp_kses_post( get_the_content() ); ?>
                        </div>
                        <div class="testimonial__cite">
                            <?= esc_html( get_the_title() ); ?>
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
wp_reset_postdata();