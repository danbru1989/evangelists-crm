<?php
/**
 * Sets up the plugin.
 *
 * @package      BrubakerDesignServices\EvangelistsCRM
 * @author       Dan Brubaker
 * @since        1.0.0
 **/

/**
 * Registers Custom Post Types
 *
 * @return void
 */
function ecrm_register_custom_post_types() {

	/**
	 * Post Type: Contacts.
	 */

	$labels = array(
		'name'          => __( 'Contacts', ECRM_PLUGIN_TEXT_DOMAIN ),
		'singular_name' => __( 'Contact', ECRM_PLUGIN_TEXT_DOMAIN ),
		'all_items'     => __( 'All Contacts', ECRM_PLUGIN_TEXT_DOMAIN ),
	);

	$args = array(
		'label'                 => __( 'Contacts', ECRM_PLUGIN_TEXT_DOMAIN ),
		'labels'                => $labels,
		'description'           => 'This is for storing contact information for pastors and other ministry leaders.',
		'public'                => true,
		'publicly_queryable'    => false,
		'show_ui'               => true,
		'delete_with_user'      => false,
		'show_in_rest'          => true,
		'rest_base'             => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'has_archive'           => false,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => false,
		'exclude_from_search'   => true,
		'capability_type'       => 'post',
		'map_meta_cap'          => true,
		'hierarchical'          => false,
		'rewrite'               => array(
			'slug'       => 'contact',
			'with_front' => true,
		),
		'query_var'             => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-businessman',
		'supports'              => array( 'title' ),
	);

	register_post_type( 'contact', $args );

	/**
	 * Post Type: Organizations.
	 */

	$labels = array(
		'name'          => __( 'Organizations', ECRM_PLUGIN_TEXT_DOMAIN ),
		'singular_name' => __( 'Organization', ECRM_PLUGIN_TEXT_DOMAIN ),
		'all_items'     => __( 'All Organizations', ECRM_PLUGIN_TEXT_DOMAIN ),
	);

	$args = array(
		'label'                 => __( 'Organizations', ECRM_PLUGIN_TEXT_DOMAIN ),
		'labels'                => $labels,
		'description'           => 'This is for storing organization information for businesses, churches, and other ministries.',
		'public'                => true,
		'publicly_queryable'    => true,
		'show_ui'               => true,
		'delete_with_user'      => false,
		'show_in_rest'          => true,
		'rest_base'             => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'has_archive'           => false,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => false,
		'exclude_from_search'   => true,
		'capability_type'       => 'post',
		'map_meta_cap'          => true,
		'hierarchical'          => false,
		'rewrite'               => array(
			'slug'       => 'organization',
			'with_front' => true,
		),
		'query_var'             => true,
		'menu_position'         => 6,
		'menu_icon'             => 'dashicons-admin-multisite',
		'supports'              => array( 'title' ),
	);

	register_post_type( 'organization', $args );

	/**
	 * Post Type: Events.
	 */

	$labels = array(
		'name'          => __( 'Itinerary', ECRM_PLUGIN_TEXT_DOMAIN ),
		'singular_name' => __( 'Event', ECRM_PLUGIN_TEXT_DOMAIN ),
		'plural_name' => __( 'Events', ECRM_PLUGIN_TEXT_DOMAIN ),
		'all_items'     => __( 'All Events', ECRM_PLUGIN_TEXT_DOMAIN ),
	);

	$args = array(
		'label'                 => __( 'Itinerary', ECRM_PLUGIN_TEXT_DOMAIN ),
		'labels'                => $labels,
		'description'           => 'This is for storing itinerary information for meetings, personal events, and other itinerary records.',
		'public'                => true,
		'publicly_queryable'    => true,
		'show_ui'               => true,
		'delete_with_user'      => false,
		'show_in_rest'          => true,
		'rest_base'             => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'has_archive'           => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'exclude_from_search'   => true,
		'capability_type'       => 'post',
		'map_meta_cap'          => true,
		'hierarchical'          => false,
		'rewrite'               => array(
			'slug'       => 'itinerary',
			'with_front' => true,
		),
		'query_var'             => true,
		'menu_position'         => 7,
		'menu_icon'             => 'dashicons-calendar',
		'supports'              => array( 'title', 'genesis-cpt-archives-settings' ),
	);

	register_post_type( 'event', $args );

	/**
	 * Post Type: Communications.
	 */

	$labels = array(
		'name'          => __( 'Communications', ECRM_PLUGIN_TEXT_DOMAIN ),
		'singular_name' => __( 'Communication', ECRM_PLUGIN_TEXT_DOMAIN ),
		'all_items'     => __( 'All Communications', ECRM_PLUGIN_TEXT_DOMAIN ),
	);

	$args = array(
		'label'                 => __( 'Communications', ECRM_PLUGIN_TEXT_DOMAIN ),
		'labels'                => $labels,
		'description'           => 'This is for keeping record of communications that have taken place.',
		'public'                => true,
		'publicly_queryable'    => false,
		'show_ui'               => true,
		'delete_with_user'      => false,
		'show_in_rest'          => true,
		'rest_base'             => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'has_archive'           => false,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => false,
		'exclude_from_search'   => true,
		'capability_type'       => 'post',
		'map_meta_cap'          => true,
		'hierarchical'          => false,
		'rewrite'               => array(
			'slug'       => 'communication',
			'with_front' => true,
		),
		'query_var'             => true,
		'menu_position'         => 8,
		'menu_icon'             => 'dashicons-format-chat',
		'supports'              => false,
	);

	register_post_type( 'communication', $args );
}
add_action( 'init', 'ecrm_register_custom_post_types' );

/**
 * Registers Custom Taxonomies
 *
 * @return void
 */
function ecrm_register_taxonomies() {

	/**
	 * Taxonomy: Contact Categories.
	 */

	$labels = array(
		'name'          => __( 'Categories', ECRM_PLUGIN_TEXT_DOMAIN ),
		'singular_name' => __( 'Category', ECRM_PLUGIN_TEXT_DOMAIN ),
	);

	$args = array(
		'label'                 => __( 'Categories', ECRM_PLUGIN_TEXT_DOMAIN ),
		'labels'                => $labels,
		'public'                => true,
		'publicly_queryable'    => true,
		'hierarchical'          => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'query_var'             => true,
		'rewrite'               => array(
			'slug'       => 'contact_category',
			'with_front' => true,
		),
		'show_admin_column'     => true,
		'show_in_rest'          => true,
		'rest_base'             => 'contact_category',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'show_in_quick_edit'    => true,
	);
	register_taxonomy( 'contact_category', array( 'contact' ), $args );

	/**
	 * Taxonomy: Organization Categories.
	 */

	$labels = array(
		'name'          => __( 'Categories', ECRM_PLUGIN_TEXT_DOMAIN ),
		'singular_name' => __( 'Category', ECRM_PLUGIN_TEXT_DOMAIN ),
	);

	$args = array(
		'label'                 => __( 'Categories', ECRM_PLUGIN_TEXT_DOMAIN ),
		'labels'                => $labels,
		'public'                => true,
		'publicly_queryable'    => false,
		'hierarchical'          => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => false,
		'query_var'             => true,
		'rewrite'               => array(
			'slug'       => 'organization_category',
			'with_front' => true,
		),
		'show_admin_column'     => true,
		'show_in_rest'          => true,
		'rest_base'             => 'organization_category',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'show_in_quick_edit'    => true,
	);
	register_taxonomy( 'organization_category', array( 'organization' ), $args );

	/**
	 * Taxonomy: Event Categories.
	 */

	$labels = array(
		'name'          => __( 'Categories', ECRM_PLUGIN_TEXT_DOMAIN ),
		'singular_name' => __( 'Category', ECRM_PLUGIN_TEXT_DOMAIN ),
	);

	$args = array(
		'label'                 => __( 'Categories', ECRM_PLUGIN_TEXT_DOMAIN ),
		'labels'                => $labels,
		'public'                => true,
		'publicly_queryable'    => false,
		'hierarchical'          => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => false,
		'query_var'             => true,
		'rewrite'               => array(
			'slug'       => 'event_category',
			'with_front' => true,
		),
		'show_admin_column'     => true,
		'show_in_rest'          => true,
		'rest_base'             => 'event_category',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'show_in_quick_edit'    => true,
	);

	register_taxonomy( 'event_category', array( 'event' ), $args );
}
add_action( 'init', 'ecrm_register_taxonomies' );
