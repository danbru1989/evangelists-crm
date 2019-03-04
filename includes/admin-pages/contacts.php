<?php
/**
 * Contact CPT Admin Page.
 *
 * @package      BrubakerDesignServices\EvangelistsCRM
 * @author       Dan Brubaker
 * @since        1.0.0
 **/

add_action( 'acf/render_field/key=field_5c6b3712018a4', 'ecrm_output_contacts_quick_actions' );
/**
 * Outputs the Quick Actions
 *
 * @return void
 */
function ecrm_output_contacts_quick_actions() {

	if ( ! get_field( 'organization' ) ) {

		echo '<div class="ecrm-notice">Please save an organization to see more details...</div>';

	} else {
		$organization = get_field( 'organization' );

		$phone   = get_field( 'preferred_phone' );
		$email   = get_field( 'preferred_email' );
		$address = ecrm_get_long_address( get_field( 'physical_address' ) );
		$website = $organization->website;

		// $communication     = ecrm_add_communications_query( get_the_ID(), 'communication_group_communication_contact', 1 ); // Breaks lower communications loop for some reason...
		echo '<div class="ecrm-quick-action-wrap">';
			echo '<ul class="left-column">';

		if ( $phone ) {
			echo '<li><span class="dashicons dashicons-phone"></span><a href="tel:+1' . esc_attr( $phone ) . '">' . esc_html( $phone ) . '</a></li>';
		}

		if ( $email ) {
			echo '<li><span class="dashicons dashicons-email"></span><a href="mailto:' . esc_attr( $email ) . '">' . esc_attr( $email ) . '</a></li>';
		}

		if ( $address ) {
			echo '<li><span class="dashicons dashicons-admin-home"></span><div class="address">' . $address . '</div></li>';
		}

			echo '</ul>';
			echo '<ul class="right-column">';

				echo '<li><span class="dashicons dashicons-admin-tools"></span><a href="/wp-admin/post.php?post=' . $organization->ID . '&action=edit" alt="Edit Organization">Edit Organization</a></li>';

		if ( $website ) {
			echo '<li><span class="dashicons dashicons-admin-links"></span><a href="' . $website . '" target="_blank">View Website</a></li>';
		}

		if ( $address ) {
			echo '<li><span class="dashicons dashicons-location-alt"></span><a href="https://www.google.com/maps/dir/Current+Location/' . $address . '" target="_blank">Get Directions</a></li>';
		}

		// The WHILE statement breaks loop in Recent Communications Loop. Reason UNKNOWN...
		// if ( $communication->have_posts() ) {
		// while ( $communication->have_posts() ) {
		// $communication->the_post();
		// printf(
		// '<li><span class="dashicons dashicons-clock"></span><div class="communication"><strong>Last Communication</strong><br/>%s</div></li>',
		// get_field( 'communication_group_communication_date' )
		// );
		// }
		// wp_reset_postdata();
		// } else {
		// echo '—';
		// }
			echo '</ul>';
		echo '</div>';
	}
}

add_action( 'acf/render_field/key=field_5c6e9dab848c7', 'ecrm_output_contacts_recent_communication_entries' );
/**
 * Outputs the Recent Communication Entries
 *
 * @return void
 */
function ecrm_output_contacts_recent_communication_entries() {

	$communications = ecrm_add_communications_query( get_the_ID(), 'communication_group_communication_contact' );

	ecrm_add_communications_loop( $communications, 20 );

}
