<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

get_header('shop');

while (have_posts()) {
    the_post();

    if (get_post_meta(get_the_ID(), 'WooCommerceEventsDate', true) ?? null) {

        $start = get_post_meta(get_the_ID(), 'WooCommerceEventsDate', true);
        $datetime = DateTime::createFromFormat('F d, Y', $start);

        
        if (!$datetime) {
            $datetime = DateTime::createFromFormat('j F Y', $start);
        }

        if (!$datetime instanceof DateTime) {
            error_log("Invalid WooCommerceEventsDate for product " . get_the_ID() . ": " . $start);
        }
        if ($datetime instanceof DateTime) {
            $zdate = $datetime->format('Y-m-d');
        } else {
            // Handle unexpected format – maybe log it or use the raw value
            $zdate = $start;
        }

        ?>
<main id="main" class="single-hike">
    <section id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
        <div class="container-xl pt-4 pb-5">
            <?php woocommerce_breadcrumb(); ?>
            <?php wc_print_notices(); ?>
            <div class="row">
                <div class="col-md-8 col-lg-9">
                    <div class="product__intro">
                        <div class="h3 d-md-none"><?=$start?></div>
                        <h1><?php the_title(); ?></h1>
                        <?php woocommerce_template_single_excerpt(); ?>
                        <div class="product__meta pt-4">
                            <div class="start_date"><strong>Date:</strong>
                                <?=$start?>
                            </div>
                            <?php
                            $startHours = get_post_meta(get_the_ID(), 'WooCommerceEventsHour', true);
        $startMinutes = get_post_meta(get_the_ID(), 'WooCommerceEventsMinutes', true);
        $endHours = get_post_meta(get_the_ID(), 'WooCommerceEventsHourEnd', true);
        $endMinutes = get_post_meta(get_the_ID(), 'WooCommerceEventsMinutesEnd', true);
        if ($startHours != '00') {
            echo '<div class="start_time"><strong>Time:</strong> <span>' . $startHours . ':' . $startMinutes;
            if ($endHours != '00') {
                echo ' - ' . $endHours . ':' . $endMinutes;
            }
            echo '</span></div>';
        }

        $location = get_post_meta(get_the_ID(), 'WooCommerceEventsLocation', true);
        if ($location ?? null) {
            ?>
                            <div class="start_loca"><strong>Location:</strong>
                                <span><?=$location?></span>
                            </div>
                            <?php
        }
        ?>

                        </div>
                    </div>
                    <div class="text-center mb-4 d-md-none">
                        <a href="#cartbar" class="btn btn-primary">Buy Tickets</a>
                    </div>
                    <div class="px-2">
                        <?=apply_filters('the_content', get_the_content())?>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a id="cartbar" class="anchor"></a>
                    <div class="sticky-top">
                        <?php if ($datetime instanceof DateTime): ?>
                            <time datetime="<?=$zdate?>" class="icon mb-4">
                                <em><?=$datetime->format('l')?></em>
                                <strong><?=$datetime->format('F')?></strong>
                                <span><?=$datetime->format('j')?></span>
                            </time>
                        <?php else: ?>
                            <div class="icon mb-4">
                                <!-- <em>Unknown day</em> -->
                                <strong><?=esc_html($start)?></strong>
                            </div>
                        <?php endif; ?>
                        <?=lc_woocommerce_show_stock()?>
                        <?php woocommerce_template_single_price(); ?>
                        <?php woocommerce_template_single_add_to_cart(); ?>
                        <div class="max-ch mx-auto text-center">
                            <?=apply_filters('the_content', get_field('content'))?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
    } else {
        ?>
<main id="main" class="single-product">
    <section id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
        <div class="container-xl pt-4 pb-5">
            <?php woocommerce_breadcrumb(); ?>
            <?php wc_print_notices(); ?>
            <h1 class="headline mb-4"><?=get_the_title()?></h1>
            <div class="row mx-0 g-4">
                <div class="col-md-4">
                    <?php do_action('woocommerce_before_single_product_summary'); ?>
                </div>
                <div class="col-md-8">
                    <?php
                woocommerce_template_single_excerpt();
        woocommerce_template_single_price();
        woocommerce_template_single_add_to_cart();
        ?>
                    <div class="mt-4">
                        <?=apply_filters('the_content', get_the_content())?>
                    </div>
                </div>
            </div>

        </div>
    </section>
</main>
<?php
    }
}
get_footer('shop');
?>