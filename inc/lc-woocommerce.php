<?php

/**
 * Product category hibernation helpers.
 */

/**
 * Check whether a product category is hibernated.
 *
 * @param int|WP_Term $term Product category term or ID.
 * @return bool
 */
function lc_is_product_category_hibernated( $term ) {
    $term_id = $term instanceof WP_Term ? $term->term_id : (int) $term;

    if ( ! $term_id ) {
        return false;
    }

    return '1' === get_term_meta( $term_id, '_lc_hibernate_product_category', true );
}

/**
 * Get hibernated product category IDs.
 *
 * @return int[]
 */
function lc_get_hibernated_product_category_ids() {
    $terms = get_terms(
        array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'fields'     => 'ids',
            'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                array(
                    'key'   => '_lc_hibernate_product_category',
                    'value' => '1',
                ),
            ),
        )
    );

    if ( is_wp_error( $terms ) ) {
        return array();
    }

    return array_map( 'intval', $terms );
}

/**
 * Get hibernated product category slugs.
 *
 * @return string[]
 */
function lc_get_hibernated_product_category_slugs() {
    $terms = get_terms(
        array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'fields'     => 'id=>slug',
            'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                array(
                    'key'   => '_lc_hibernate_product_category',
                    'value' => '1',
                ),
            ),
        )
    );

    if ( is_wp_error( $terms ) ) {
        return array();
    }

    return array_values( $terms );
}

/**
 * Add the hibernate field to product categories.
 *
 * @return void
 */
function lc_product_category_hibernate_add_field() {
    ?>
    <div class="form-field term-hibernate-wrap">
        <label for="lc-hibernate-product-category">
            <?php esc_html_e( 'Hibernate category', 'lc-mjoa2024' ); ?>
        </label>
        <label>
            <input type="checkbox" id="lc-hibernate-product-category" name="lc_hibernate_product_category" value="1">
            <?php esc_html_e( 'Hide this category and its products from site visitors until it is reactivated.', 'lc-mjoa2024' ); ?>
        </label>
    </div>
    <?php
}
add_action( 'product_cat_add_form_fields', 'lc_product_category_hibernate_add_field' );

/**
 * Add the hibernate field to the product category edit screen.
 *
 * @param WP_Term $term The term being edited.
 * @return void
 */
function lc_product_category_hibernate_edit_field( $term ) {
    ?>
    <tr class="form-field term-hibernate-wrap">
        <th scope="row">
            <label for="lc-hibernate-product-category">
                <?php esc_html_e( 'Hibernate category', 'lc-mjoa2024' ); ?>
            </label>
        </th>
        <td>
            <label>
                <input type="checkbox" id="lc-hibernate-product-category" name="lc_hibernate_product_category" value="1" <?php checked( lc_is_product_category_hibernated( $term ) ); ?>>
                <?php esc_html_e( 'Hide this category and its products from site visitors until it is reactivated.', 'lc-mjoa2024' ); ?>
            </label>
        </td>
    </tr>
    <?php
}
add_action( 'product_cat_edit_form_fields', 'lc_product_category_hibernate_edit_field' );

/**
 * Persist the hibernate state for a product category.
 *
 * @param int $term_id The term ID.
 * @return void
 */
function lc_save_product_category_hibernate_state( $term_id ) {
    $hibernate = isset( $_POST['lc_hibernate_product_category'] ) ? '1' : '0';
    update_term_meta( $term_id, '_lc_hibernate_product_category', $hibernate );
}
add_action( 'created_product_cat', 'lc_save_product_category_hibernate_state' );
add_action( 'edited_product_cat', 'lc_save_product_category_hibernate_state' );

/**
 * Show hibernation status in the product category table.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function lc_product_category_hibernate_column( $columns ) {
    $columns['lc_hibernated'] = __( 'Hibernate', 'lc-mjoa2024' );
    return $columns;
}
add_filter( 'manage_edit-product_cat_columns', 'lc_product_category_hibernate_column' );

/**
 * Render the product category hibernation column.
 *
 * @param string $content Existing column content.
 * @param string $column_name Current column name.
 * @param int    $term_id Current term ID.
 * @return string
 */
function lc_product_category_hibernate_column_content( $content, $column_name, $term_id ) {
    if ( 'lc_hibernated' !== $column_name ) {
        return $content;
    }

    return lc_is_product_category_hibernated( $term_id )
        ? esc_html__( 'Hibernated', 'lc-mjoa2024' )
        : esc_html__( 'Active', 'lc-mjoa2024' );
}
add_filter( 'manage_product_cat_custom_column', 'lc_product_category_hibernate_column_content', 10, 3 );

/**
 * Exclude hibernated categories from visitor-facing product queries.
 *
 * @param WP_Query $query The query being prepared.
 * @return void
 */
function lc_hide_hibernated_product_categories_from_queries( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $hibernated_ids = lc_get_hibernated_product_category_ids();

    if ( empty( $hibernated_ids ) ) {
        return;
    }

    if ( ! $query->is_post_type_archive( 'product' ) && ! $query->is_tax( 'product_cat' ) ) {
        return;
    }

    $tax_query   = (array) $query->get( 'tax_query' );
    $tax_query[] = array(
        'taxonomy' => 'product_cat',
        'field'    => 'term_id',
        'terms'    => $hibernated_ids,
        'operator' => 'NOT IN',
    );

    $query->set( 'tax_query', $tax_query );
}
add_action( 'pre_get_posts', 'lc_hide_hibernated_product_categories_from_queries' );

/**
 * Redirect visitors away from hibernated category pages and products.
 *
 * @return void
 */
function lc_redirect_hibernated_product_category_content() {
    if ( is_admin() || current_user_can( 'edit_posts' ) ) {
        return;
    }

    if ( is_tax( 'product_cat' ) ) {
        $term = get_queried_object();

        if ( $term instanceof WP_Term && lc_is_product_category_hibernated( $term ) ) {
            wp_safe_redirect( home_url( '/hikes/' ), 302 );
            exit;
        }
    }

    if ( is_product() ) {
        $terms = get_the_terms( get_the_ID(), 'product_cat' );

        if ( ! empty( $terms ) ) {
            foreach ( $terms as $term ) {
                if ( lc_is_product_category_hibernated( $term ) ) {
                    wp_safe_redirect( home_url( '/hikes/' ), 302 );
                    exit;
                }
            }
        }
    }

    if ( is_page() ) {
        $page = get_queried_object();

        if ( $page instanceof WP_Post ) {
            $parent = wp_get_post_parent_id( $page->ID );
            $hikes  = get_page_by_path( 'hikes', OBJECT, 'page' );

            if ( $hikes instanceof WP_Post && (int) $parent === (int) $hikes->ID ) {
                $term = get_term_by( 'slug', $page->post_name, 'product_cat' );

                if ( $term instanceof WP_Term && lc_is_product_category_hibernated( $term ) ) {
                    wp_safe_redirect( home_url( '/hikes/' ), 302 );
                    exit;
                }
            }
        }
    }
}
add_action( 'template_redirect', 'lc_redirect_hibernated_product_category_content' );

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

    $catids = array_filter( array_map( 'intval', (array) $catids ) );

    if ( empty( $catids ) ) {
        return array();
    }

    $catids = array_values( array_diff( $catids, lc_get_hibernated_product_category_ids() ) );

    if ( empty( $catids ) ) {
        return array();
    }

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
        wc_add_notice(__('Sorry, this email address is invalid.', 'woocommerce'), 'error');
        if (is_wp_error($errors)) {
            $errors->add('blocked_email', __('Sorry, this email address is invalid.', 'woocommerce'));
        }
    }

    return $errors;
}

?>
