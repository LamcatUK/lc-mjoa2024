<?php

//add cart to nav if not empty
add_action('wp_head', 'add_cart_icon_to_header_nav');

function add_cart_icon_to_header_nav()
{
    if (WC()->cart->is_empty()) {
        return;
    }

?>
    <style type="text/css">
        .header-cart-icon {
            /* Style your cart icon as desired */
        }
    </style>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var cartIcon =
                '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item nav-item d-none d-md-block"><a title="Cart" href="<?= wc_get_cart_url() ?>" class="nav-link"><i class="fa-solid fa-basket-shopping"></i> (' +
                <?php echo WC()->cart->get_cart_contents_count(); ?> +
                ')</a></li>';
            var cartIcon2 =
                '<div class="d-md-none"><a title="Cart" href="<?= wc_get_cart_url() ?>" class="nav-link"><i class="fa-solid fa-basket-shopping"></i> (' +
                <?php echo WC()->cart->get_cart_contents_count(); ?> +
                ')</a></div>';

            $('#main-menu').append(cartIcon);
            var $cartIcon2 = $(cartIcon2); // Assuming cartIcon2 is your element or a selector
            var $children = $('#main-nav').children(); // Get all child elements of #main-nav
            var penultimateIndex = $children.length - 2; // Calculate the penultimate index

            // Check if there are at least two elements to have a penultimate position
            if (penultimateIndex >= 0) {
                $children.eq(penultimateIndex).before(
                    $cartIcon2); // Insert cartIcon2 before the penultimate element
            } else {
                // If there are less than 2 elements, just append it (or handle as needed)
                $('#main-nav').append($cartIcon2);
            }

        });
    </script>
<?php
}


// bread
add_filter('woocommerce_breadcrumb_defaults', 'custom_woocommerce_breadcrumbs_defaults');
function custom_woocommerce_breadcrumbs_defaults($defaults)
{
    // Change the breadcrumb home text from 'Home' to 'Your Home Text'
    // $defaults['home'] = 'Your Home Text';
    // Change breadcrumb delimiter from '/' to '>'
    $defaults['delimiter'] = ' / ';
    // You can also customize 'wrap_before', 'wrap_after', 'before', 'after', 'breadcrumb_class' etc.

    return $defaults;
}

// hike vs product bread
add_filter('woocommerce_get_breadcrumb', 'custom_woocommerce_breadcrumbs', 10, 2);
function custom_woocommerce_breadcrumbs($crumbs, $breadcrumb)
{
    // Check if we're on a single product page, otherwise, leave breadcrumbs as they are
    if (is_product()) {
        global $post;
        $terms = get_the_terms($post->ID, 'product_cat');
        $slugs = array_column((array) $terms, 'slug');

        // Check if product has specific categories
        if (array_intersect($slugs, array('multi-day-hikes', 'group-tours', 'guided-walks', 'challenge-events', 'womens-wellness-walks', 'community-events'))) {
            // Define the new breadcrumb path for specific categories
            $crumbs = array(
                array(
                    0 => 'Home',
                    1 => '/'
                ),
                array(
                    0 => 'Hikes',
                    1 => '/hikes/'
                ),
                array(
                    0 => ucwords(str_replace('-', ' ', $terms[0]->slug)), // Converts first term slug to title; adjust as necessary
                    1 => '/hikes/' . $terms[0]->slug . '/'
                ),
                array(
                    0 => get_the_title(),
                )
            );
        } else {
            // Define the default breadcrumb path for other categories
            $crumbs = array(
                array(
                    0 => 'Home',
                    1 => '/'
                ),
                array(
                    0 => 'Shop',
                    1 => '/shop/'
                ),
                array(
                    0 => get_the_title(),
                )
            );
        }
    }

    return $crumbs;
}

// remove zoom
add_action('wp', 'remove_product_image_zoom_support', 99);

function remove_product_image_zoom_support()
{
    if (is_product()) {
        remove_theme_support('wc-product-gallery-zoom');
    }
}

function lc_products_by_category($catids, $title = 'products', $limit = -1)
{

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $limit,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $catids,
                'operator' => 'IN'
            ),
        ),
    );

    $q = new WP_Query($args);

    $output = array();

    if ($q->have_posts()) {
        $a = 0;
        $today = new DateTime('today');
        while ($q->have_posts()) {
            $q->the_post();
            $start = get_post_meta(get_the_ID(), 'WooCommerceEventsDate', true);
            $start = new DateTime($start);
            if ($start < $today) {
                continue;
            }
            $output[$a]['title'] = get_the_title();
            $output[$a]['start'] = $start;
            $output[$a]['link'] = get_the_permalink();

            $product = wc_get_product(get_the_ID());

            $output[$a]['price'] = $product->get_price_html();

            $terms = wp_get_post_terms(get_the_ID(), 'product_cat', array('fields' => 'slugs'));
            $output[$a]['slug'] = $terms[0];

            $output[$a]['product'] = $product;

            $a++;
        }
        wp_reset_postdata();
    } else {
        // echo 'No ' . $title . ' found in this category.';
    }

    return $output;
}

function lc_woocommerce_show_stock()
{
    global $product;
    if (! $product->managing_stock() && ! $product->is_in_stock()) {
        return; // If the product doesn't manage stock or isn't in stock, don't display anything.
    }

    $stock_quantity = $product->get_stock_quantity(); // Retrieves the stock quantity.
    $backorders_allowed = $product->get_backorders();

    if ($stock_quantity > 2) {
        return '<p class="in-stock">' . sprintf('%s tickets available', $stock_quantity) . '</p>';
    } elseif ($stock_quantity > 1) {
        return '<p class="in-stock text-warning fw-bold">' . sprintf('Only %s tickets left', $stock_quantity) . '</p>';
    } elseif ($stock_quantity == 1) {
        if ($backorders_allowed === 'no') {
            return '<p class="in-stock text-danger fw-bold">Only one ticket left</p><style>.quantity{display:none}</style>';
        } else {
            return '<p class="in-stock text-danger fw-bold">Only one ticket left.<br><a href="/contact-us/"><u>Contact us</u> to join the waiting list if you need more.</a></p>';
        }
    } else {
        if ($backorders_allowed === 'no') {
            return '<p class="out-of-stock text-danger fw-bold">Sold Out!</p>';
        } else {
            return '<p class="in-stock text-danger fw-bold">Sold Out!<br><a href="/contact-us/"><u>Contact us</u> to join the waiting list.</a></p>';
        }
    }
}

add_action('woocommerce_checkout_process', 'block_specific_emails');
add_action('woocommerce_registration_errors', 'block_specific_emails', 10, 3);

function block_specific_emails($errors)
{

    $blocked_emails = textarea_to_array();
    // $blocked_emails = array(
    //     'blocked@example.com', // Replace with the email address you want to block
    // );

    $user_email = isset($_POST['billing_email']) ? $_POST['billing_email'] : $_POST['email'];

    if (in_array($user_email, $blocked_emails)) {
        wc_add_notice(__('Sorry, this email address is not allowed to place an order.', 'woocommerce'), 'error');
        if (is_wp_error($errors)) {
            $errors->add('blocked_email', __('Sorry, this email address is not allowed to register.', 'woocommerce'));
        }
    }

    return $errors;
}

?>