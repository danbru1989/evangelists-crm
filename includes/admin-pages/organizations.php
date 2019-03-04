<?php
/**
 * Organization CPT Admin Page.
 *
 * @package      BrubakerDesignServices\EvangelistsCRM
 * @author       Dan Brubaker
 * @since        1.0.0
 **/

add_action( 'acf/render_field/key=field_5c6b29ef6e70d', 'ecrm_output_organizations_quick_actions' );
/**
 * Outputs the Quick Actions
 *
 * @param array $field The field to pass in.
 * @return void
 */
function ecrm_output_organizations_quick_actions() {

	if ( ! get_field( 'primary_organization_contact' ) ) {

		echo '<div class="ecrm-notice">Please save a primary contact to see more details...</div>';

	} else {
		$contact = get_field( 'primary_organization_contact' );

		$phone   = $contact->preferred_phone;
		$email   = $contact->preferred_email;
		$address = ecrm_get_long_address( get_field( 'physical_address' ) );
		$website = get_field( 'website' );

		echo '<div class="ecrm-quick-action-wrap">';
			echo '<ul class="left-column">';

		if ( $phone ) {
			echo '<li><span class="dashicons dashicons-phone"></span><a href="tel:+1' . esc_attr( $phone ) . '">' . esc_html( $phone ) . '</a></li>';
		}

		if ( $email ) {
			echo '<li><span class="dashicons dashicons-email"></span><a href="mailto:' . esc_attr( $email ) . '">' . esc_attr( $email ) . '</a></li>';
		}

		if ( $address ) {
			echo '<li><span class="dashicons dashicons-location"></span><div class="address">' . $address . '</div></li>';
		}

			echo '</ul>';
			echo '<ul class="right-column">';

				echo '<li><span class="dashicons dashicons-admin-tools"></span><a href="/wp-admin/post.php?post=' . $contact->ID . '&action=edit" alt="Edit Contact">Edit Contact</a></li>';

		if ( $website ) {
			echo '<li><span class="dashicons dashicons-admin-links"></span><a href="' . $website . '" target="_blank">View Website</a></li>';
		}

		if ( $address ) {
			echo '<li><span class="dashicons dashicons-location-alt"></span><a href="https://www.google.com/maps/dir/Current+Location/' . $address . '" target="_blank">Get Directions</a></li>';
		}

			echo '</ul>';
		echo '</div>';
	}
}








// add_action( 'acf/render_field/key=field_5c6b29ef91dc9', 'ecrm_output_other_contacts_info', 1 );
/**
 * Outputs the Other Contacts' information
 *
 * @param array $field The field to pass in.
 * @return void
 */
function ecrm_output_other_contacts_info() {

	if ( get_field( 'other_contact' ) ) {
		$other_contact = get_field( 'other_contact' );

		d( $other_contact );

		echo '<div class="other-contact-info-wrap">';

		if ( $other_contact->preferred_phone ) {
			echo 'yes';
		}

		echo '</div>';
	}
}
