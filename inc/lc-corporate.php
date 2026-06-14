<?php
/**
 * Corporate Events route CPT + County taxonomy.
 *
 * Evergreen, bookable corporate/charity walking routes, grouped by county.
 * These are intentionally separate from WooCommerce/FooEvents hikes.
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the corporate_event post type.
 */
function lc_register_corporate_event_cpt() {
	register_post_type(
		'corporate_event',
		array(
			'labels'             => array(
				'name'               => 'Corporate Events',
				'singular_name'      => 'Corporate Event',
				'menu_name'          => 'Corporate Events',
				'all_items'          => 'All Routes',
				'add_new'            => 'Add New Route',
				'add_new_item'       => 'Add New Route',
				'edit_item'          => 'Edit Route',
				'new_item'           => 'New Route',
				'view_item'          => 'View Route',
				'search_items'       => 'Search Routes',
				'not_found'          => 'No routes found',
				'not_found_in_trash' => 'No routes found in Trash',
			),
			'public'             => false,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => false,
			'show_in_rest'       => true,
			'has_archive'        => false,
			'rewrite'            => false,
			'menu_icon'          => 'dashicons-location-alt',
			'supports'           => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'lc_register_corporate_event_cpt' );

/**
 * Register the event_county taxonomy.
 */
function lc_register_event_county_taxonomy() {
	register_taxonomy(
		'event_county',
		'corporate_event',
		array(
			'labels'            => array(
				'name'          => 'Counties',
				'singular_name' => 'County',
				'menu_name'     => 'Counties',
				'all_items'     => 'All Counties',
				'edit_item'     => 'Edit County',
				'add_new_item'  => 'Add New County',
				'new_item_name' => 'New County Name',
				'search_items'  => 'Search Counties',
			),
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => false,
		)
	);
}
add_action( 'init', 'lc_register_event_county_taxonomy' );
