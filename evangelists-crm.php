<?php
/**
 * Plugin Name: Evangelists CRM
 * Description: A simple and intuitive Contact Relations Manager built for itinerate evangelists.
 *
 * Version:     1.0.0
 *
 * Author:      Dan Brubaker
 * Author URI:  https://brubakerservices.org/
 *
 * @package    BrubakerDesignServices\EvangelistsCRM
 * @since      1.0.0
 *
 * Text Domain: evangelists-crm
 */

// Initialize Constants.
define( 'ECRM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ECRM_PLUGIN_TEXT_DOMAIN', 'evangelists-crm' );
define( 'ECRM_PLUGIN_VERSION', '1.0.0' );

add_action( 'plugins_loaded', 'ecrm_init' );
/**
 * Start loading the plugin after dependencies have been checked.
 *
 * @return void
 */
function ecrm_init() {
	require_once __DIR__ . '/includes/dependencies.php';

	if ( ! ecrm_check_dependencies() ) {

		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'ecrm_init_fail_notice' );

	} else {

		require_once __DIR__ . '/includes/load-includes.php';
		require_once __DIR__ . '/includes/load-assets.php';
	}
}
