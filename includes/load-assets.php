<?php
/**
 * Loads the Assets.
 *
 * @package      BrubakerDesignServices\EvangelistsCRM
 * @author       Dan Brubaker
 * @since        1.0.0
 **/

add_action( 'wp_enqueue_scripts', 'ecrm_enqueue_assets' );
/**
 * Enqueue Scripts and Styles
 *
 * @since 1.0.0
 *
 * @return void
 */
function ecrm_enqueue_assets() {

	wp_enqueue_style(
		ECRM_PLUGIN_TEXT_DOMAIN . '-public-styles',
		ECRM_PLUGIN_URL . 'assets/css/public.css',
		array(),
		ECRM_PLUGIN_VERSION
	);

	wp_enqueue_script(
		'gmapsapi',
		'https://maps.googleapis.com/maps/api/js?key=AIzaSyAWn2qEoqAbberTemCAjhzCYzEAlu81Sq8',
		array(),
		ECRM_PLUGIN_VERSION,
		true
	);

	wp_enqueue_script(
		ECRM_PLUGIN_TEXT_DOMAIN . '-gmapscode',
		ECRM_PLUGIN_URL . 'assets/js/google-maps.js',
		array( 'gmapsapi', 'jquery' ),
		ECRM_PLUGIN_VERSION,
		true
	);
}

add_action( 'admin_enqueue_scripts', 'ecrm_enqueue_admin_assets' );
/**
 * Enqueue Admin Scripts and Styles
 *
 * @since 1.0.0
 *
 * @return void
 */
function ecrm_enqueue_admin_assets() {

	wp_enqueue_style(
		ECRM_PLUGIN_TEXT_DOMAIN . '-admin-styles',
		ECRM_PLUGIN_URL . 'assets/css/admin.css',
		array(),
		ECRM_PLUGIN_VERSION
	);
}
