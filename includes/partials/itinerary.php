<?php
/**
 * Builds an Itinerary Loop.
 *
 * @package      BrubakerDesignServices\EvangelistsCRM
 * @author       Dan Brubaker
 * @since        1.0.0
 **/

/**
 * Outputs the itinerary loop.
 *
 * Groups events by year and outputs them for displaying as a table.
 */
function ecrm_add_itinerary_loop() {

	// Prepare for building year groups later.
	$year = false;

	$args = array(
		'post_type'      => 'event',
		'posts_per_page' => -1,
		'orderby' => 'meta_value',
		'meta_key' => 'event_dates_end_date',
		'order' => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => 'event_dates_end_date',
				'value'   => date( 'Ymd' ),
				'type'    => 'DATE',
				'compare' => '>=',
			),
		),
	);

	$events = new \WP_Query( $args );

	if ( $events->have_posts() ) {

		echo '<div class="itinerary-table-wrap">';

		while ( $events->have_posts() ) {
			$events->the_post();

			/**
			 * Output the header row for a new year group
			 *
			 * Checks if the event's year is the same as $year. If it is, skip this conditional. If it is not, process this conditional.
			 */
			if ( $year !== ecrm_get_event_start( 'Y' ) ) {

				/**
				 * Close out the current year group.
				 *
				 * This is skipped at the first encounter because $year is still "false" and processed on the last encounter because $year is no longer "false".
				 */
				if ( $year ) {
					echo '</div>';
				}

				// Output a new year group.
				?>
					<h2 class="year-title"><?php echo ecrm_get_event_start( 'Y' ); ?></h2>
					<div class="table">
						<div class="header-row">
							<div class="cell column-1 date">Date</div>
							<div class="cell column-2 name">Name</div>
							<div class="cell column-3 location">Location</div>
							<div class="cell column-4 contact">Contact</div>
							<div class="cell column-5 type">Type</div>
						</div>
					<?php

					// Set year to current group so it can trigger next group when it's time.
					$year = ecrm_get_event_start( 'Y' );
			}

			// Get Location.
			if ( get_field( 'event_organization' ) ) {
				$location = ecrm_get_short_address(
					get_field(
						'physical_address',
						get_field( 'event_organization' )
					),
					false
				);
			} else {
				$location = '–';
			}

			// Get Contact.
			if ( get_field( 'primary_event_contact' ) ) {
				$contact = ecrm_get_full_title_contact( get_field( 'primary_event_contact' ), 1 );
			} else {
				$contact = '–';
			}

			// Get Terms.
			$terms = ecrm_get_terms_list( $post_id, 'event_category' );

			if ( ! $terms ) {
				$terms = '–';
			}

			// Pending event output.
			if ( get_field( 'event_display_settings' ) === 'pending' ) {

				?>
				<span class="row has-special-status">
					<div class="cell column-1 date"><?php echo ecrm_get_event_dates( 'M j' ); ?></div>
					<div class="cell column-2 name">PENDING</div>
					<div class="cell column-3 location"><?php echo esc_html( $location ); ?></div>
					<div class="cell column-4 contact">–</div>
					<div class="cell column-5 type">–</div>
				</span>
					<?php

					// Personal event output.
			} elseif ( get_field( 'event_display_settings' ) === 'personal' ) {

				?>
				<span class="row has-special-status">
					<div class="cell column-1 date"><?php echo ecrm_get_event_dates( 'M j' ); ?></div>
					<div class="cell column-2 name">UNAVAILABLE</div>
					<div class="cell column-3 location">–</div>
					<div class="cell column-4 contact">–</div>
					<div class="cell column-5 type">–</div>
				</span>
					<?php

					// Regular event output.
			} else {
				?>
				<a class="row" href="<?php the_permalink(); ?>" alt="<?php the_title_attribute(); ?>">
					<div class="cell column-1 date"><?php echo ecrm_get_event_dates( 'M j' ); ?></div>
					<div class="cell column-2 name"><?php the_title(); ?></div>
					<div class="cell column-3 location"><?php echo esc_html( $location ); ?></div>
					<div class="cell column-4 contact"><?php echo esc_html( $contact ); ?></div>
					<div class="cell column-5 type"><?php echo esc_html( $terms ); ?></div>
				</a>
					<?php
			}
		}
		echo '</div>';

	} else {
		echo '<div class="no-content">Sorry, there are no events to show...</div>';
	}
}
