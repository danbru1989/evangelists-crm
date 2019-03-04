<?php
/**
 * Checks dependencies to see if they exist.
 *
 * @package      BrubakerDesignServices\EvangelistsCRM
 * @author       Dan Brubaker
 * @since        1.0.0
 **/

/**
 * Checks dependencies.
 *
 * This plugin requires Advanced Custom Fields and either Events Organiser or Events Manager.
 *
 * @return boolean
 */
function ecrm_check_dependencies() {

	// Check for Advanced Custom Fields.
	if ( ! class_exists( 'ACF' ) ) {

		return false;
	}

	// All dependencies found.
	return true;
}

/**
 * Outputs an error message when dependencies are not detected.
 *
 * @return void
 */
function ecrm_init_fail_notice() {
	echo '<div class="error"><p><strong>Evangelists CRM cannot be activated. </strong>The Advanced Custom Fields plugin is required.</p></div>';
}
