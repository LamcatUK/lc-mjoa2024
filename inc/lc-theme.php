<?php
/**
 * Theme initialization and customizations for LC MJOA 2024.
 *
 * This file contains theme setup, hooks, filters, and utility functions.
 *
 * @package lc-mjoa2024
 */

defined('ABSPATH') || exit;


require_once LC_THEME_DIR . '/inc/lc-utility.php';
require_once LC_THEME_DIR . '/inc/lc-blocks.php';
require_once LC_THEME_DIR . '/inc/lc-woocommerce.php';
require_once LC_THEME_DIR . '/inc/lc-blog.php';


// Remove unwanted SVG filter injection WP.
remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');


add_filter('big_image_size_threshold', '__return_false');

// Remove comment-reply.min.js from footer
function remove_comment_reply_header_hook()
{
    wp_deregister_script('comment-reply');
}
add_action('init', 'remove_comment_reply_header_hook');

add_action('admin_menu', 'remove_comments_menu');
function remove_comments_menu()
{
    remove_menu_page('edit-comments.php');
}

add_filter('theme_page_templates', 'child_theme_remove_page_template');
function child_theme_remove_page_template( $page_templates )
{
    unset(
        $page_templates['page-templates/blank.php'],
        $page_templates['page-templates/empty.php'],
        $page_templates['page-templates/left-sidebarpage.php'],
        $page_templates['page-templates/right-sidebarpage.php'],
        $page_templates['page-templates/both-sidebarspage.php']
    );
    return $page_templates;
}
add_action('after_setup_theme', 'remove_understrap_post_formats', 11);
function remove_understrap_post_formats()
{
    remove_theme_support('post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ));
}

if (function_exists('acf_add_options_page')) {
    acf_add_options_page(
        array(
            'page_title'     => 'Site-Wide Settings',
            'menu_title'    => 'Site-Wide Settings',
            'menu_slug'     => 'theme-general-settings',
            'capability'    => 'edit_posts',
        )
    );
}

function widgets_init()
{
    register_nav_menus(
        array(
        'primary_nav' => __('Primary Nav', 'lc-mjoa2024'),
        'footer_menu1' => __('Footer Menu 1', 'lc-mjoa2024'),
        )
    );

    unregister_sidebar('hero');
    unregister_sidebar('herocanvas');
    unregister_sidebar('statichero');
    unregister_sidebar('left-sidebar');
    unregister_sidebar('right-sidebar');
    unregister_sidebar('footerfull');
    unregister_nav_menu('primary');

    add_theme_support('disable-custom-colors');
    add_theme_support(
        'editor-color-palette',
        array(
            array(
                'name'  => 'Guided Walks',
                'slug'  => 'guided-walks',
                'color' => '#C75B39',
            ),
            array(
                'name'  => 'Group Tours',
                'slug'  => 'group-tours',
                'color' => '#287C7A',
            ),
            array(
                'name'  => 'Multi-Day Hikes',
                'slug'  => 'multi-day-hikes',
                'color' => '#2A3D66',
            ),
            array(
                'name'  => 'Challenge Events',
                'slug'  => 'challenge-events',
                'color' => '#B02E2E',
            ),
            array(
                'name'  => "Women's Wellness Walks",
                'slug'  => 'womens-wellness-walks',
                'color' => '#A890C5',
            ),
            array(
                'name'  => 'Community Events',
                'slug'  => 'community-events',
                'color' => '#FF6B4A',
            ),
            array(
                'name'  => 'Dark',
                'slug'  => 'dark',
                'color' => '#333333',
            ),
        )
    );

}
add_action('widgets_init', 'widgets_init', 11);


remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

//Custom Dashboard Widget
add_action('wp_dashboard_setup', 'register_LC_dashboard_widget');
function register_LC_dashboard_widget()
{
    wp_add_dashboard_widget(
        'lc_dashboard_widget',
        'Lamcat',
        'lc_dashboard_widget_display'
    );
}

function lc_dashboard_widget_display()
{
    ?>
<div style="display: flex; align-items: center; justify-content: space-around;">
    <img style="width: 50%;"
        src="<?php echo get_stylesheet_directory_uri().'/img/lc-full.jpg'; ?>">
    <a class="button button-primary" target="_blank" rel="noopener nofollow noreferrer"
        href="mailto:hello@lamcat.co.uk/">Contact</a>
</div>
<div>
    <p><strong>Thanks for choosing Lamcat!</strong></p>
    <hr>
    <p>Got a problem with your site, or want to make some changes & need us to take a look for you?</p>
    <p>Use the link above to get in touch and we'll get back to you ASAP.</p>
</div>
    <?php
}


// add_filter(
//     'wpseo_breadcrumb_links',
//     function ($links) {
//         global $post;
//         if (is_singular('fighters')) {
//             $t = get_the_category($post->ID);
//             $breadcrumb[] = array(
//                 'url' => '/fighters/',
//                 'text' => 'Fighters',
//             );
//             array_splice($links, 1, -2, $breadcrumb);
//         }
//         if (is_singular('events')) {
//             $t = get_the_category($post->ID);
//             $breadcrumb[] = array(
//                 'url' => '/events/',
//                 'text' => 'Events',
//             );
//             array_splice($links, 1, -2, $breadcrumb);
//         }
//         return $links;
//     }
// );

// remove discussion metabox
function cc_gutenberg_register_files()
{
    // script file
    wp_register_script(
        'cc-block-script',
        get_stylesheet_directory_uri() .'/js/block-script.js', // adjust the path to the JS file
        array( 'wp-blocks', 'wp-edit-post' )
    );
    // register block editor script
    register_block_type(
        'cc/ma-block-files', array(
        'editor_script' => 'cc-block-script'
        )
    );
}
add_action('init', 'cc_gutenberg_register_files');

function understrap_all_excerpts_get_more_link($post_excerpt)
{
    if (is_admin() || ! get_the_ID()) {
        return $post_excerpt;
    }
    return $post_excerpt;
}

//* Remove Yoast SEO breadcrumbs from Revelanssi's search results
add_filter('the_content', 'wpdocs_remove_shortcode_from_index');
function wpdocs_remove_shortcode_from_index($content)
{
    if (is_search()) {
        $content = strip_shortcodes($content);
    }
    return $content;
}

// GF really is pants.
/**
 * Change submit from input to button
 *
 * Do not use example provided by Gravity Forms as it strips out the button attributes including onClick
 */
// function wd_gf_update_submit_button($button_input, $form)
// {
//     //save attribute string to $button_match[1]
//     preg_match("/<input([^\/>]*)(\s\/)*>/", $button_input, $button_match);

//     //remove value attribute (since we aren't using an input)
//     $button_atts = str_replace("value='" . $form['button']['text'] . "' ", "", $button_match[1]);

//     // create the button element with the button text inside the button element instead of set as the value
//     return '<button ' . $button_atts . '><span>' . $form['button']['text'] . '</span></button>';
// }
// add_filter('gform_submit_button', 'wd_gf_update_submit_button', 10, 2);




function LC_theme_enqueue()
{
    // Get the theme data.
    $the_theme     = wp_get_theme();
    $theme_version = $the_theme->get('Version');

    $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
    // Grab asset urls.
    $theme_styles  = "/css/child-theme{$suffix}.css";
    $theme_scripts = "/js/child-theme{$suffix}.js";
    
    $css_version = $theme_version . '.' . filemtime(get_stylesheet_directory() . $theme_styles);

    // wp_enqueue_style('lightbox-stylesheet', get_stylesheet_directory_uri() . '/css/lightbox.min.css', array(), $the_theme->get('Version'));
    // wp_enqueue_script('lightbox-scripts', get_stylesheet_directory_uri() . '/js/lightbox-plus-jquery.min.js', array(), $the_theme->get('Version'), true);
    // wp_enqueue_script('lightbox-scripts', get_stylesheet_directory_uri() . '/js/lightbox.min.js', array(), $the_theme->get('Version'), true);
    // wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js', array(), null, true);
    wp_enqueue_style('slick-styles', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css', array(), $the_theme->get('Version'));
    wp_enqueue_style('slick-theme-styles', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css', array(), $the_theme->get('Version'));
    wp_enqueue_script('slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array(), null, true);
    wp_enqueue_style('aos-style', "https://unpkg.com/aos@2.3.1/dist/aos.css", array());
    wp_enqueue_script('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array(), null, true);
    // wp_enqueue_script('parallax', get_stylesheet_directory_uri() . '/js/parallax.min.js', array('jquery'), $the_theme->get('Version'), true);
    // wp_enqueue_script('child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array('jquery'), $css_version, true);
    wp_enqueue_script('child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $css_version, true);

    wp_enqueue_style('child-understrap-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $css_version);
    // wp_enqueue_script( 'jquery' );
    
    $js_version = $theme_version . '.' . filemtime(get_stylesheet_directory() . $theme_scripts);
    
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'LC_theme_enqueue');


// black thumbnails - fix alpha channel
/**
 * Patch to prevent black PDF backgrounds.
 *
 * https://core.trac.wordpress.org/ticket/45982
 */
// require_once ABSPATH . 'wp-includes/class-wp-image-editor.php';
// require_once ABSPATH . 'wp-includes/class-wp-image-editor-imagick.php';

// // phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace
// final class ExtendedWpImageEditorImagick extends WP_Image_Editor_Imagick
// {
//     /**
//      * Add properties to the image produced by Ghostscript to prevent black PDF backgrounds.
//      *
//      * @return true|WP_error
//      */
//     // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
//     protected function pdf_load_source()
//     {
//         $loaded = parent::pdf_load_source();

//         try {
//             $this->image->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
//             $this->image->setBackgroundColor('#ffffff');
//         } catch (Exception $exception) {
//             error_log($exception->getMessage());
//         }

//         return $loaded;
//     }
// }

// /**
//  * Filters the list of image editing library classes to prevent black PDF backgrounds.
//  *
//  * @param array $editors
//  * @return array
//  */
// add_filter('wp_image_editors', function (array $editors): array {
//     array_unshift($editors, ExtendedWpImageEditorImagick::class);

//     return $editors;
// });



// /* append button to primary nav */
// add_filter('wp_nav_menu_items', 'add_admin_link', 10, 2);
// function add_admin_link($items, $args)
// {
//     if ($args->theme_location == 'primary_nav') {
//         $items .= '<div class="nav-btns">';
//         $items .= '</div>';
//     }
//     return $items;
// }

// get image id from first slide in lc-hero
function get_hero($postID)
{
    $blocks = parse_blocks(get_the_content(null, false, $postID));
    $bg = '';
    foreach ($blocks as $b) {
        if ('acf/lc-hero' === $b['blockName']) {
            $bg = $b['attrs']['data']['slides_0_background'];
            return $bg;
        }
    }
    return;
}

add_filter('wpcf7_autop_or_not', '__return_false');

// add_action('nav_menu_css_class', 'add_current_nav_class', 10, 2);
function add_current_nav_class($classes, $item)
{
    if (! ($item instanceof WP_Post)) {
        return $classes;
    }

    $post = get_post();
    if (empty($post)) {
        return $classes;
    }

    $post_type          = get_post_type($post->ID);
    $post_type_object   = get_post_type_object($post_type);

    if (! ($post_type_object instanceof WP_Post_Type) || ! $post_type_object->has_archive) {
        return $classes;
    }
        
    $post_type_slug = $post_type_object->rewrite['slug'];
    $menu_slug      = strtolower(trim($item->url));

    if (empty($post_type_slug) || empty($menu_slug)) {
        return $classes;
    }
    if (strpos($menu_slug, $post_type_slug) === false) {
        return $classes;
    }
        
    $classes[] = 'current-menu-item';

    return $classes;
}

/**
 * FooEvents 48-Hour Reminder System
 *
 * Automatically sends email reminders to customers 48 hours before their event starts.
 * Triggers when WooCommerce orders containing FooEvents products are completed.
 */

/**
 * Configuration: Set reminder offset time
 *
 * Change this value to adjust when reminders are sent.
 * Default: 48 hours before event start time.
 */
$reminder_offset = 48 * HOUR_IN_SECONDS; // 172800 seconds = 48 hours.

/**
 * Schedule event reminders when an order is completed.
 *
 * This function runs when a WooCommerce order status changes to 'completed'.
 * It checks if the order contains FooEvents products and schedules reminder emails.
 *
 * @param int $order_id The WooCommerce order ID.
 */
function lc_schedule_fooevents_reminders( $order_id )
{
    global $reminder_offset;

    // Get the order object.
    $order = wc_get_order($order_id);
    if (! $order ) {
        return;
    }

    // Get customer information for the reminder email.
    $customer_first_name = $order->get_billing_first_name();
    $customer_email      = $order->get_billing_email();

    // Check each item in the order for FooEvents products.
    foreach ( $order->get_items() as $item_id => $item ) {
        $product_id = $item->get_product_id();
        $product    = wc_get_product($product_id);

        if (! $product ) {
            continue;
        }

        // Check if this product has FooEvents data (event start date and time).
        $event_start_date = get_post_meta($product_id, '_event_start_date', true);
        $event_start_time = get_post_meta($product_id, '_event_start_time', true);

        // Skip if this isn't a FooEvents product or missing event data.
        if (empty($event_start_date) || empty($event_start_time) ) {
            continue;
        }

        // Combine date and time to create event start timestamp.
        $event_datetime_string = $event_start_date . ' ' . $event_start_time;
        $event_timestamp       = strtotime($event_datetime_string);

        // Skip if we couldn't parse the event datetime.
        if (false === $event_timestamp ) {
            continue;
        }

        // Calculate when the reminder should be sent (offset before event start).
        $reminder_timestamp = $event_timestamp - $reminder_offset;
        $current_timestamp  = time();

        // Skip scheduling if the reminder time has already passed.
        if ($reminder_timestamp <= $current_timestamp ) {
            continue;
        }

        // Create unique hook name for this specific reminder.
        $hook_name = 'lc_send_fooevents_reminder';
        $hook_args = array(
            'order_id'            => $order_id,
            'product_id'          => $product_id,
            'customer_email'      => $customer_email,
            'customer_first_name' => $customer_first_name,
            'event_timestamp'     => $event_timestamp,
        );

        // Schedule the reminder email to be sent.
        wp_schedule_single_event($reminder_timestamp, $hook_name, $hook_args);
    }
}
add_action('woocommerce_order_status_completed', 'lc_schedule_fooevents_reminders');

/**
 * Send the actual reminder email.
 *
 * This function is triggered by the WP-Cron system at the scheduled time.
 * It sends a reminder email to the customer about their upcoming event.
 *
 * @param int    $order_id            The WooCommerce order ID.
 * @param int    $product_id          The FooEvents product ID.
 * @param string $customer_email      The customer's email address.
 * @param string $customer_first_name The customer's first name.
 * @param int    $event_timestamp     The event start timestamp.
 */
function lc_send_fooevents_reminder( $order_id, $product_id, $customer_email, $customer_first_name, $event_timestamp )
{
    // Get product information for the email.
    $product = wc_get_product($product_id);
    if (! $product ) {
        return;
    }

    $event_name = $product->get_name();

    // Format the event date and time for display.
    $event_date_formatted = date_i18n(get_option('date_format'), $event_timestamp);
    $event_time_formatted = date_i18n(get_option('time_format'), $event_timestamp);

    // Prepare email content.
    $site_name = get_bloginfo('name');
    $subject   = sprintf('Event Reminder: %s - Starting Soon!', $event_name);

    // Build the email message.
    $message = sprintf(
        "Hi %s,\n\n" .
        "This is a friendly reminder that your event is starting in 48 hours!\n\n" .
        "Event Details:\n" .
        "• Event: %s\n" .
        "• Date: %s\n" .
        "• Time: %s\n\n" .
        "We look forward to seeing you there!\n\n" .
        "Best regards,\n" .
        'The %s Team',
        esc_html($customer_first_name),
        esc_html($event_name),
        esc_html($event_date_formatted),
        esc_html($event_time_formatted),
        esc_html($site_name)
    );

    // Set email headers for better formatting.
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . get_option('admin_email') . '>',
    );

    // Send the reminder email.
    wp_mail($customer_email, $subject, $message, $headers);
}
add_action('lc_send_fooevents_reminder', 'lc_send_fooevents_reminder', 10, 5);

/**
 * Clean up scheduled reminders when orders are cancelled or refunded.
 *
 * This prevents sending reminders for events that customers can no longer attend.
 *
 * @param int $order_id The WooCommerce order ID.
 */
function lc_cancel_fooevents_reminders( $order_id )
{
    // Get all scheduled events for this order.
    $scheduled_hooks = _get_cron_array();
    if (empty($scheduled_hooks) ) {
        return;
    }

    // Loop through all scheduled cron events.
    foreach ( $scheduled_hooks as $timestamp => $hooks ) {
        if (! isset($hooks['lc_send_fooevents_reminder']) ) {
            continue;
        }

        // Check each scheduled reminder.
        foreach ( $hooks['lc_send_fooevents_reminder'] as $hook_key => $hook_data ) {
            $args = $hook_data['args'];

            // If this reminder is for the cancelled order, remove it.
            if (isset($args[0]) && (int) $args[0] === $order_id ) {
                wp_unschedule_event($timestamp, 'lc_send_fooevents_reminder', $args);
            }
        }
    }
}
add_action('woocommerce_order_status_cancelled', 'lc_cancel_fooevents_reminders');
add_action('woocommerce_order_status_refunded', 'lc_cancel_fooevents_reminders');

?>