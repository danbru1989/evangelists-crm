<?php
/**
 * Advanced Custom Fields Plugin functionality.
 *
 * @package      BrubakerDesignServices\EvangelistsCRM
 * @author       Dan Brubaker
 * @since        1.0.0
 **/

// add_action( 'acf/init', 'ecrm_acf_init' );
/**
 * Add Google Maps API Key. - NOT USING RIGHT NOW.
 *
 * @return void
 */
function ecrm_acf_init() {
	acf_update_setting( 'google_api_key', 'ADD KEY HERE' );
}

add_filter( 'acf/fields/post_object/result/name=organization', 'ecrm_change_organization_term_results', 10, 4 );
add_filter( 'acf/fields/post_object/result/name=event_organization', 'ecrm_change_organization_term_results', 10, 4 );
/**
 * Adds the City and State to the "Organization" field search results in the Contacts admin
 *
 * @return $title
 */
function ecrm_change_organization_term_results( $title, $post, $field, $post_id ) {

	$address = ecrm_get_short_address( get_field( 'physical_address', $post->ID ), false );

	$title = sprintf(
		'%s – %s',
		$title,
		$address
	);

	return $title;
}

add_filter( 'acf/fields/post_object/result/name=primary_organization_contact', 'ecrm_change_primary_organization_contact_term_results', 10, 4 );
add_filter( 'acf/fields/post_object/result/name=name', 'ecrm_change_primary_organization_contact_term_results', 10, 4 );
add_filter( 'acf/fields/post_object/result/name=primary_event_contact', 'ecrm_change_primary_organization_contact_term_results', 10, 4 );
add_filter( 'acf/fields/post_object/result/name=communication_contact', 'ecrm_change_primary_organization_contact_term_results', 10, 4 );
/**
 * Adds the Prefix, City, and State / Country to the "Primary Contact" field search results in the Organizations admin
 *
 * @return $title
 */
function ecrm_change_primary_organization_contact_term_results( $title, $post, $field, $post_id ) {

	$categories = ecrm_get_terms_list( $post, 'contact_category', ' / ', 1 );
	$address    = ecrm_get_short_address( get_field( 'physical_address', $post->ID ), false );

	$title = sprintf(
		'%s %s – %s',
		$categories,
		$title,
		$address
	);

	return $title;
}
