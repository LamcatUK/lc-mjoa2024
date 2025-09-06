<?php
/**
 * FAQ Block component.
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="faq_block py-5">
    <div class="container-xl" data-aos="fade-up">
        <?php
        if ( get_field( 'faq_title' ) ) {
            ?>
        <h2 class="h2 text-center text-md-start mb-4">
            <?= esc_html( get_field( 'faq_title' ) ); ?>
        </h2>
            <?php
        }
        if ( get_field( 'faq_intro' ) ) {
            ?>
        <div class="mb-4 faq_intro">
            <?= esc_html( get_field( 'faq_intro' ) ); ?>
        </div>
            <?php
        }
        ?>
        <div class="faq_block__inner">
            <?php
            $accordion = random_str( 5 );

            echo '<div itemscope="" itemtype="https://schema.org/FAQPage" id="accordion' .
                esc_attr( $accordion ) .
                '" class="accordion accordion-flush">';

            $counter   = 0;
            $show      = '';
            $collapsed = 'collapsed';

            while ( have_rows( 'faqs' ) ) {
                the_row();
                $ac = $accordion . '_' . $counter;
                ?>
                <div itemscope="" itemprop="mainEntity" itemtype="https://schema.org/Question" class="accordion-item">
                    <div class="accordion-head accordion-collapse <?= esc_attr( $collapsed ); ?>"
                    itemprop="name" data-bs-toggle="collapse"
                    id="heading_<?= esc_attr( $ac ); ?>"
                    data-bs-target="#c<?= esc_attr( $ac ); ?>" role="button"
                    aria-expanded="true" aria-controls="c<?= esc_attr( $ac ); ?>">
                    <div class="pb-1">
                        <?= esc_html( get_sub_field( 'question' ) ); ?>
                    </div>
                </div>
                <div class="collapse <?= esc_attr( $show ); ?>" itemscope=""
                    itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"
                    id="c<?= esc_attr( $ac ); ?>"
                    aria-labelledby="heading_<?= esc_attr( $ac ); ?>"
                    data-bs-parent="#accordion<?= esc_attr( $accordion ); ?>">
                    <div itemprop="text" class="faq__answer mb-4">
                        <p><?= esc_html( get_sub_field( 'answer' ) ); ?></p>
                    </div>
                </div>
            </div>
                <?php
                ++$counter;
                $show      = '';
                $collapsed = 'collapsed';
            }
            ?>
        </div>
    </div>
</section>