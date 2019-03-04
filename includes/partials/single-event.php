<?php
/**
 * Builds the Single Event page content.
 *
 * @package      BrubakerDesignServices\EvangelistsCRM
 * @author       Dan Brubaker
 * @since        1.0.0
 **/

/**
 * Add the event's content.
 *
 * @return void
 */
function ecrm_add_single_event_content() {

	$location = get_field( 'event_organization' );
	$contact  = get_field( 'primary_event_contact' );

	$location_address         = ecrm_get_long_address( get_field( 'physical_address', $location->ID ) );
	$location_address_encoded = rawurlencode( ecrm_get_long_address( get_field( 'physical_address', $location->ID ), true ) );
	$location_website         = $location->website;
	$contact_name             = ecrm_get_full_title_contact( $contact->ID, 1 );
	$terms                    = ecrm_get_terms_list( $post_id, 'event_category' );
	$public_details           = get_field( 'public_details' );

	echo '<h3 class="contact-wrap">' . $contact_name . '</h3>';

	echo '<div class="buttons-wrap">';

	if ( $location ) {
		echo '<a class="button" href="https://www.google.com/maps/dir/?api=1&origin=Current+Location&destination=' . $location_address_encoded . '" target="_blank" alt="Get directions to this event">Get Directions</a>';
	}

	if ( $location_website ) {
		echo '<a class="button" href="' . esc_url( $location_website ) . '" target="_blank" alt="Visit the website for this location">Ministry Website</a>';
	}

	echo '</div>';

	if ( function_exists( 'ecrm_get_event_dates' ) ) {
		$dates = ecrm_get_event_dates( 'F j, Y' );
		$days  = ecrm_get_event_dates( 'l' );
	}

	?>
		<div class="columns-wrap">
			<div class="left-column">
				<h2 class="categories"><?php echo esc_html( $terms ); ?></h2>
				<div class="date-info">
					<h4 class="dates"><?php echo $dates; ?></h4>
					<h4 class="days"><?php echo $days; ?></h4>
				</div>
				<?php
				if ( $location ) {
					?>
						<div class="location-info">
							<h3 class="location-title">Location</h3>
							<?php echo $location->post_title; ?><br/><?php echo $location_address; ?> 
						</div>
					<?php
				}
				if ( $public_details['event_website'] ) {
					?>
						<div class="event-website-button-wrap">
							<a class="button" href="<?php echo esc_url( $public_details['event_website'] ); ?>" target="_blank" alt="Visit the unique website for this event">Event Website</a>
						</div>

					<?php
				}
				if ( $public_details['more_info'] ) {
					?>
						<div class="event-description-wrap">
							<h4>More Info</h4>
							<div class="description-wrap"><?php echo $public_details['more_info']; ?></div>
						</div>
					<?php
				}
				?>
			</div>
			<?php
			if ( $location ) {

				?>
					<div class="right-column">
						<div class="map-wrap"><a href="https://www.google.com/maps/dir/?api=1&origin=Current+Location&destination=<?php echo $location_address_encoded; ?>" target="_blank"><img src="https://maps.googleapis.com/maps/api/staticmap?markers=size:mid%7C<?php echo $location_address_encoded; ?>&zoom=7&size=600x400&scale=2&maptype=roadmap&key=AIzaSyAWn2qEoqAbberTemCAjhzCYzEAlu81Sq8"></a></div>
						<div class="itinerary-button-wrap">
							<a class="button" href="/itinerary/" alt="View itinerary">View Itinerary</a>
						</div>
					</div>
				<?php
			}
			?>
		</div>
	<?php
}
