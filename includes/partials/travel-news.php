<?php
/**
 * Builds the markup for displaying a paragraph describing travel plans.
 *
 * @package      BrubakerDesignServices\EvangelistsCRM
 * @author       Dan Brubaker
 * @since        1.0.0
 **/

/**
 * Output Travel News.
 *
 * @return void
 */
function ecrm_travel_news() {

	do_action( 'ecrm_before_travel_news_loop' );

	ecrm_do_travel_news_loop();

	do_action( 'ecrm_after_travel_news_loop' );

}

/**
 * Output the Travel News loop.
 *
 * Gets the current or next event and outputs a paragraph describing travel plans.
 *
 * @return void
 */
function ecrm_do_travel_news_loop() {

	// Query events for current or next event.
	$args = array(
		'post_type'        => 'event',
		'suppress_filters' => false,
		'posts_per_page'   => 1,
		'event_end_after'  => 'today',
		'meta_key'         => 'event_display_settings',
		'meta_value'       => 'public',
	);

	$event = new \WP_Query( $args );

	if ( $event->have_posts() ) {

		do_action( 'ecrm_before_travel_news_while' );

		while ( $event->have_posts() ) {
			$event->the_post();

			do_action( 'ecrm_before_travel_news_content' );

			$title    = get_the_title();
			$location = ecrm_get_travel_news_location();
			$contact  = ecrm_get_travel_news_contact();

			// Output for current event spanning a single day.
			if ( ecrm_get_event_start( 'Y-m-d' ) == date( 'Y-m-d' ) && ecrm_get_event_end( 'Y-m-d' ) == date( 'Y-m-d' ) ) {

				// Event has location and contact.
				if ( $location && $contact ) {
					printf(
						'<div class="travel-news">We are currently at %s in %s, with %s. The meeting is today only.</div>',
						$title,
						$location,
						$contact
					);
				}

				// Event has location only.
				if ( $location && ! $contact ) {
					printf(
						'<div class="travel-news">We are currently at %s in %s. The meeting is today only.</div>',
						$title,
						$location
					);
				}

				// Event has contact only.
				if ( ! $location && $contact ) {
					printf(
						'<div class="travel-news">We are currently at %s, with %s. The meeting is today only.</div>',
						$title,
						$contact
					);
				}

				// Event has neither location or contact.
				if ( ! $location && ! $contact ) {
					printf(
						'<div class="travel-news">We are currently at %s. The meeting is today only.</div>',
						$title
					);
				}
			}

			// Output for current event spanning multiple days.
			if ( ecrm_get_event_start( 'Y-m-d' ) <= date( 'Y-m-d' ) && ecrm_get_event_end( 'Y-m-d' ) > date( 'Y-m-d' ) ) {

				// Event has location and contact.
				if ( $location && $contact ) {
					printf(
						'<div class="travel-news">We are currently at %s in %s, with %s. The meetings began on %s and are scheduled to finish on %s.</div>',
						$title,
						$location,
						$contact,
						ecrm_get_event_start( 'F jS' ),
						ecrm_get_event_end( 'l, F jS' )
					);
				}

				// Event has location only.
				if ( $location && ! $contact ) {
					printf(
						'<div class="travel-news">We are currently at %s in %s. The meetings began on %s and are scheduled to finish on %s.</div>',
						$title,
						$location,
						ecrm_get_event_start( 'F jS' ),
						ecrm_get_event_end( 'l, F jS' )
					);
				}

				// Event has contact only.
				if ( ! $location && $contact ) {
					printf(
						'<div class="travel-news">We are currently at %s, with %s. The meetings began on %s and are scheduled to finish on %s.</div>',
						$title,
						$contact,
						ecrm_get_event_start( 'F jS' ),
						ecrm_get_event_end( 'l, F jS' )
					);
				}

				// Event has neither location or contact.
				if ( ! $location && ! $contact ) {
					printf(
						'<div class="travel-news">We are currently at %s. The meetings began on %s and is scheduled to finish on %s.</div>',
						$title,
						ecrm_get_event_start( 'F jS' ),
						ecrm_get_event_end( 'l, F jS' )
					);
				}
			}

			// Output for future event spanning single day.
			if ( ecrm_get_event_start( 'Y-m-d' ) > date( 'Y-m-d' ) && ecrm_get_event_end( 'Y-m-d' ) == ecrm_get_event_start( 'Y-m-d' ) ) {

				// Event has location and contact.
				if ( $location && $contact ) {
					printf(
						'<div class="travel-news">We will be at %s in %s, with %s. The meeting is for %s only.</div>',
						$title,
						$location,
						$contact,
						ecrm_get_event_start( 'l, F jS' )
					);
				}

				// Event has location only.
				if ( $location && ! $contact ) {
					printf(
						'<div class="travel-news">We will be at %s in %s. The meeting is for %s only.</div>',
						$title,
						$location,
						ecrm_get_event_start( 'l, F jS' )
					);
				}

				// Event has contact only.
				if ( ! $location && $contact ) {
					printf(
						'<div class="travel-news">We will be at %s, with %s. The meeting is for %s only.</div>',
						$title,
						$contact,
						ecrm_get_event_start( 'l, F jS' )
					);
				}

				// Event has neither location or contact.
				if ( ! $location && ! $contact ) {
					printf(
						'<div class="travel-news">We will be at %s. The meeting is for %s only.</div>',
						$title,
						ecrm_get_event_start( 'l, F jS' )
					);
				}
			}

			// Output for future event spanning multiple days.
			if ( ecrm_get_event_start( 'Y-m-d' ) > date( 'Y-m-d' ) && ecrm_get_event_end( 'Y-m-d' ) != ecrm_get_event_start( 'Y-m-d' ) ) {

				// Event has location and contact.
				if ( $location && $contact ) {
					printf(
						'<div class="travel-news">We will be at %s in %s, with %s. The meeting begins on %s and is scheduled to finish on %s.</div>',
						$title,
						$location,
						$contact,
						ecrm_get_event_start( 'F jS' ),
						ecrm_get_event_end( 'l, F jS' )
					);
				}

				// Event has location only.
				if ( $location && ! $contact ) {
					printf(
						'<div class="travel-news">We will be at %s in %s. The meeting begins on %s and is scheduled to finish on %s.</div>',
						$title,
						$location,
						ecrm_get_event_start( 'F jS' ),
						ecrm_get_event_end( 'l, F jS' )
					);
				}

				// Event has contact only.
				if ( ! $location && $contact ) {
					printf(
						'<div class="travel-news">We will be at %s, with %s. The meeting begins on %s and is scheduled to finish on %s.</div>',
						$title,
						$contact,
						ecrm_get_event_start( 'F jS' ),
						ecrm_get_event_end( 'l, F jS' )
					);
				}

				// Event has neither location or contact.
				if ( ! $location && ! $contact ) {
					printf(
						'<div class="travel-news">We will be at %s. The meeting begins on %s and is scheduled to finish on %s.</div>',
						$title,
						ecrm_get_event_start( 'F jS' ),
						ecrm_get_event_end( 'l, F jS' )
					);
				}
			}

			do_action( 'ecrm_after_travel_news_content' );

		}
		wp_reset_postdata();

		do_action( 'ecrm_after_travel_news_while' );
	} else {
		echo 'There are no upcoming meetings to display.';
	}
}

/**
 * Gets the location / address information. For use in the Travel News loop.
 *
 * @return $location
 */
function ecrm_get_travel_news_location() {
	if ( ! get_field( 'physical_address', get_field( 'event_organization' ) ) ) {
		$location = false;
	} else {
		$location = ecrm_get_short_address(
			get_field(
				'physical_address',
				get_field( 'event_organization' )
			),
			false
		);
	}
	return $location;
}

/**
 * Gets the contact information. For use in the Travel News loop.
 *
 * @return $contact
 */
function ecrm_get_travel_news_contact() {
	if ( ! get_field( 'primary_event_contact' ) ) {
		$contact = false;
	} else {
		$contact = get_field( 'primary_event_contact' );
		$contact = ecrm_get_full_title_contact( $contact->ID, 1 );
	}
	return $contact;
}
