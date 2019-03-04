<?php
/**
 * Event CPT Admin Page.
 *
 * @package      BrubakerDesignServices\EvangelistsCRM
 * @author       Dan Brubaker
 * @since        1.0.0
 **/

add_action( 'acf/render_field/key=field_5c6fe0d46ec45', 'ecrm_output_events_quick_actions' );
/**
 * Outputs the Quick Actions
 *
 * @return void
 */
function ecrm_output_events_quick_actions() {

	if ( ! get_field( 'primary_event_contact' ) && ! get_field( 'event_organization' ) ) {

		echo '<div class="ecrm-notice">Please save a contact or location to see more details...</div>';

	} else {
		$contact      = get_field( 'primary_event_contact' );
		$organization = get_field( 'event_organization' );

		$phone   = $contact->preferred_phone;
		$email   = $contact->preferred_email;
		$address = ecrm_get_long_address( get_field( 'physical_address', $organization->ID ) );
		$website = $organization->website;

		echo '<div class="ecrm-quick-action-wrap">';
			echo '<ul class="left-column">';

		if ( $contact ) {
			echo '<li><span class="dashicons dashicons-admin-tools"></span><a href="/wp-admin/post.php?post=' . $contact->ID . '&action=edit" alt="Edit Contact">Edit Contact</a></li>';
		}

		if ( $phone ) {
			echo '<li><span class="dashicons dashicons-phone"></span><a href="tel:+1' . esc_attr( $phone ) . '">' . esc_html( $phone ) . '</a></li>';
		}

		if ( $email ) {
			echo '<li><span class="dashicons dashicons-email"></span><a href="mailto:' . esc_attr( $email ) . '">' . esc_attr( $email ) . '</a></li>';
		}

		if ( $contact ) {
			echo '<li><span class="dashicons dashicons-plus"></span><a href="/wp-admin/post-new.php?post_type=communication" target="_blank" alt="Add a new Communication Entry">Add Communication</a></li>';
		}

			echo '</ul>';
			echo '<ul class="right-column">';

		if ( $organization ) {
			echo '<li><span class="dashicons dashicons-admin-tools"></span><a href="/wp-admin/post.php?post=' . $organization->ID . '&action=edit" alt="Edit Organization">Edit Organization</a></li>';
		}

		if ( $website ) {
			echo '<li><span class="dashicons dashicons-admin-links"></span><a href="' . $website . '" target="_blank">View Website</a></li>';
		}

		if ( $address ) {
			echo '<li><span class="dashicons dashicons-location-alt"></span><a href="https://www.google.com/maps/dir/Current+Location/' . $address . '" target="_blank">Get Directions</a></li>';
		}

		if ( $address ) {
			echo '<li><span class="dashicons dashicons-location"></span><div class="address">' . $address . '</div></li>';
		}

			echo '</ul>';
		echo '</div>';
	}
}

add_action( 'acf/render_field/key=field_5c6dbe661386b', 'ecrm_output_events_recent_communication_entries' );
/**
 * Outputs the Recent Communication Entries
 *
 * @return void
 */
function ecrm_output_events_recent_communication_entries() {

	$contact = get_field( 'primary_event_contact' );

	$communications = ecrm_add_communications_query( $contact->ID, 'communication_group_communication_contact' );

	ecrm_add_communications_loop( $communications );

}
