<?php
/**
 * Helper Functions.
 *
 * @package      BrubakerDesignServices\EvangelistsCRM
 * @author       Dan Brubaker
 * @since        1.0.0
 **/

/**
 * Formats a full address and adds internationalization if needed.
 *
 * @param array   $address The array of the address.
 * @param boolean $string Display the output on a single line (true).
 * @return $address_formatted
 */
function ecrm_get_long_address( $address, $string = false ) {

	if ( ! $address ) {
		return;
	}

	if ( ! $address['country'] ) {

		if ( $address['street'] && ! $string ) {
			$address_formatted = sprintf(
				'%s<br/>%s, %s %s',
				$address['street'],
				$address['city'],
				$address['state'],
				$address['zip_code']
			);
		} elseif ( $address['street'] && $string ) {
			$address_formatted = sprintf(
				'%s, %s, %s %s',
				$address['street'],
				$address['city'],
				$address['state'],
				$address['zip_code']
			);
		} else {
			$address_formatted = sprintf(
				'%s, %s',
				$address['city'],
				$address['state']
			);
		}
	} else {

		if ( $address['street'] && ! $string ) {
			$address_formatted = sprintf(
				'%s<br/>%s, %s %s<br/>%s',
				$address['street'],
				$address['city'],
				$address['state_province'],
				$address['zip_code'],
				$address['country']
			);
		}

		if ( $address['street'] && $string ) {
			$address_formatted = sprintf(
				'%s, %s, %s, %s, %s',
				$address['street'],
				$address['city'],
				$address['state_province'],
				$address['zip_code'],
				$address['country']
			);
		}

		if ( ! $address['street'] && ! $string ) {
			$address_formatted = sprintf(
				'%s, %s<br/>%s',
				$address['city'],
				$address['state_province'],
				$address['country']
			);
		}

		if ( ! $address['street'] && $string ) {
			$address_formatted = sprintf(
				'%s, %s, %s',
				$address['city'],
				$address['state_province'],
				$address['country']
			);
		}
	}
	return $address_formatted;
}

/**
 * Formats a city and state address and adds internationalization if needed.
 *
 * @param array   $address The array of the address.
 * @param boolean $state Display the city and state (true) or city and country (false).
 * @param boolean $string Display the output on a single line (true).
 * @return $address_formatted
 */
function ecrm_get_short_address( $address, $state = true, $string = false ) {

	if ( ! $address ) {
		return;
	}

	// No internationalization needed.
	if ( ! $address['country'] ) {
		$address_formatted = sprintf(
			'%s, %s',
			$address['city'],
			$address['state']
		);

		// Needs internationalization.
	} else {

		if ( $state && ! $string ) {
			$address_formatted = sprintf(
				'%s, %s<br/>%s',
				$address['city'],
				$address['state_province'],
				$address['country']
			);
		}

		if ( $state && $string ) {
			$address_formatted = sprintf(
				'%s, %s, %s',
				$address['city'],
				$address['state_province'],
				$address['country']
			);
		}

		if ( ! $state ) {
			$address_formatted = sprintf(
				'%s, %s',
				$address['city'],
				$address['country']
			);
		}
	}
	return $address_formatted;
}

/**
 * Formats a Contact's title to include the terms before the name. Returns full title of the contact.
 *
 * @param integer $post_id The ID or WP_Post object of the Contact title to format.
 * @param integer $terms_count The number of terms to return.
 * @return $title
 */
function ecrm_get_full_title_contact( $post_id, $terms_count = 0 ) {

	$post_id = ! $post_id ? get_the_ID() : $post_id;

	$categories = ecrm_get_terms_list( $post_id, 'contact_category', ' / ', $terms_count );

	$title = $categories . ' ' . get_the_title( $post_id );

	return $title;
}

/**
 * Formats an unlinked list of terms into a string.
 *
 * @param integer $post_id The ID or WP_Post object to get the terms from.
 * @param string  $taxonomy The taxonomy to query.
 * @param string  $separator The separator between each term.
 * @param integer $terms_count The number of terms to return.
 * @param mixed   $field The key to pluck.
 * @return $terms_string
 */
function ecrm_get_terms_list( $post_id, $taxonomy, $separator = ', ', $terms_count = 0, $field = 'name' ) {

	$post_id = ! $post_id ? get_the_ID() : $post_id;
	$terms   = get_the_terms( $post_id, $taxonomy );

	if ( ! $terms ) {
		return;
	}

	if ( ! $terms_count > 0 ) {
		$terms_string = join( $separator, wp_list_pluck( $terms, $field ) );
	} else {
		$terms_string = join( $separator, wp_list_pluck( array_splice( $terms, 0, $terms_count ), $field ) );
	}
	return $terms_string;
}

/**
 * Query the Communications CPT. Pass in the $compare_id to compare against the ID of the $meta_query_key.
 *
 * @param integer $compare_id The ID of the Contact to compare.
 * @param string  $meta_query_key The slug for the custom field to query.
 * @param integer $query_count The number of posts to retrieve.
 *
 * @return $communications
 */
function ecrm_add_communications_query( $compare_id, $meta_query_key, $query_count = 10 ) {

	$args = array(
		'post_type'      => 'communication',
		'post_status'    => 'publish',
		'posts_per_page' => $query_count,
		'meta_query'     => array(
			array(
				'key'     => $meta_query_key,
				'value'   => $compare_id,
				'compare' => '=',
			),
		),
	);

	$communications = new \WP_Query( $args );

	return $communications;
}

/**
 * Loop through the communications query and output the posts.
 *
 * @param object  $communications The communications query object.
 * @param integer $word_count The number of words to allow in the Notes column.
 *
 * @return void
 */
function ecrm_add_communications_loop( $communications, $word_count = 10 ) {

	echo '<div class="ecrm-new-entry-wrap"><a class="button button-primary" href="/wp-admin/post-new.php?post_type=communication" target="_blank" alt="Add a new Communication Entry">Add New Entry</a></div>';

	if ( $communications->have_posts() ) {

		echo '<table class="ecrm-communications-table"><thead><th class="date-column">Date</th><th class="type-column">Type</th><th class="notes-column">Notes</th><th class="edit-column">Edit</th></thead><tbody>';

		while ( $communications->have_posts() ) {
			$communications->the_post();

			$communication = get_field( 'communication_group' );

			$date    = $communication['communication_date'];
			$contact = get_the_title( $communication['communication_contact'] );
			$type    = $communication['communication_type'];

			$type = ecrm_format_communication_type( $type );

			if ( ! get_field( 'communication_notes' ) ) {
				$notes = '—';
			} else {
				$notes = wp_strip_all_tags( get_field( 'communication_notes' ) );
				$notes = wp_trim_words( $notes, $word_count );
			}

			echo '<tr>';

			printf(
				'<td class="date date-column">%s</td><td class="type-column">%s</td><td class="notes-column">%s</td><td class="edit-column"><a href="/wp-admin/post.php?post=%s&action=edit" alt="Edit communication record" target="_blank"><span class="dashicons dashicons-admin-tools"></span></a></td>',
				$date,
				$type,
				$notes,
				get_the_ID()
			);

			echo '</tr>';

		}

		echo '</tbody></table>';

		wp_reset_postdata();

	} else {
		echo '<div class="ecrm-notice">There are no communication records to display.</div>';
	}
}

/**
 * Adds an icon to the communication type output
 *
 * @param string $type The type of communication.
 * @return $type_formatted
 */
function ecrm_format_communication_type( $type ) {

	if ( $type === 'phone' ) {
		$type_formatted = '<span class="ecrm-column-icons dashicons dashicons-phone"></span>Phone';

	} elseif ( $type === 'email' ) {
		$type_formatted = '<span class="ecrm-column-icons dashicons dashicons-email"></span>Email';

	} elseif ( $type === 'letter' ) {
		$type_formatted = '<span class="ecrm-column-icons dashicons dashicons-welcome-write-blog"></span>Letter';

	} elseif ( $type === 'thank_you' ) {
		$type_formatted = '<span class="ecrm-column-icons dashicons dashicons-heart"></span>Thank You Note';

	} elseif ( $type === 'in_person' ) {
		$type_formatted = '<span class="ecrm-column-icons dashicons dashicons-businessman"></span>In Person';

	} else {
		$type_formatted = '<span class="ecrm-column-icons dashicons dashicons-format-chat"></span>' . $type;
	}

	return $type_formatted;
}

/**
 * Gets the event's start date and returns it as the given format.
 *
 * @param string  $format The desired date format.
 * @param integer $post_id The desired post ID. Default is current post.
 * @return $date
 */
function ecrm_get_event_start( $format = 0, $post_id = 0 ) {

	$post_id = ! $post_id ? get_the_ID() : $post_id;
	$date    = get_field( 'event_dates_start_date', $post_id );

	if ( $format ) {

		$date = date( $format, strtotime( $date ) );

	}

	return $date;
}

/**
 * Gets the event's end date and returns it as the given format.
 *
 * @param string  $format The desired date format.
 * @param integer $post_id The desired post ID. Default is current post.
 * @return $date
 */
function ecrm_get_event_end( $format = 0, $post_id = 0 ) {

	$post_id = ! $post_id ? get_the_ID() : $post_id;
	$date    = get_field( 'event_dates_end_date', $post_id );

	if ( $format ) {

		$date = date( $format, strtotime( $date ) );

	}

	return $date;
}

/**
 * Get the event's dates and intelligently formats them.
 *
 * @param string $format The PHP date format.
 * @param string $separator The separator to intelligently divide dates with.
 * @param int    $post_id Add an optional post ID. Default is current post.
 *
 * return @dates
 */
function ecrm_get_event_dates( $format = 'F j, Y', $separator = ' – ', $post_id = 0 ) {

	if ( $format == 'M j, Y' ) {

		// Will eventually be deprecated.
		return ecrm_get_event_short_month_dates( $format = 'M j, Y', $separator, $post_id );
	}

	if ( $format == 'M j' ) {

		// Will eventually be deprecated.
		return ecrm_get_event_no_year_dates( $format = 'M j', $separator, $post_id );
	}

	if ( $format == 'l' ) {

		// Will eventually be deprecated.
		return ecrm_get_event_day_dates( $format = 'l', $separator, $post_id );
	}

	// Single Day Event.
	if ( ecrm_get_event_start() == ecrm_get_event_end() ) {

		$dates = ecrm_get_event_start( $format, $post_id );

		return $dates;
	}

	// Different Year.
	if ( ecrm_get_event_start( 'Y' ) != ecrm_get_event_end( 'Y' ) ) {

		$dates = ecrm_get_event_start( 'F j, Y', $post_id ) . $separator . ecrm_get_event_end( 'F j, Y', $post_id );

		return $dates;

	}

	// Different Months.
	if ( ecrm_get_event_start( 'F' ) != ecrm_get_event_end( 'F' ) ) {

		$dates = ecrm_get_event_start( 'F j', $post_id ) . $separator . ecrm_get_event_end( 'F j, Y', $post_id );

		return $dates;
	}

	// Different Days.
	if ( ecrm_get_event_start( 'j' ) != ecrm_get_event_end( 'j' ) ) {

		$dates = ecrm_get_event_start( 'F j', $post_id ) . $separator . ecrm_get_event_end( 'j, Y', $post_id );

		return $dates;
	}
}

/**
 * Get the event's dates and intelligently formats them. THIS WILL BE DEPRECATED!!
 *
 * @param string $format The PHP date format.
 * @param string $separator The separator to intelligently divide dates with.
 * @param int    $post_id Add an optional post ID. Default is current post.
 *
 * Íreturn @dates
 */
function ecrm_get_event_short_month_dates( $format = 'M j, Y', $separator = ' – ', $post_id = 0 ) {

	// Single Day Event.
	if ( ecrm_get_event_start() == ecrm_get_event_end() ) {

		$dates = ecrm_get_event_start( $format, $post_id );

		return $dates;
	}

	// Different Year.
	if ( ecrm_get_event_start( 'Y' ) != ecrm_get_event_end( 'Y' ) ) {

		$dates = ecrm_get_event_start( 'M j, Y', $post_id ) . $separator . ecrm_get_event_end( 'M j, Y', $post_id );

		return $dates;

	}

	// Different Months.
	if ( ecrm_get_event_start( 'M' ) != ecrm_get_event_end( 'M' ) ) {

		$dates = ecrm_get_event_start( 'M j', $post_id ) . $separator . ecrm_get_event_end( 'M j, Y', $post_id );

		return $dates;
	}

	// Different Days.
	if ( ecrm_get_event_start( 'j' ) != ecrm_get_event_end( 'j' ) ) {

		$dates = ecrm_get_event_start( 'M j', $post_id ) . $separator . ecrm_get_event_end( 'j, Y', $post_id );

		return $dates;
	}
}

/**
 * Get the event's dates and intelligently formats them. THIS WILL BE DEPRECATED!!
 *
 * @param string $format The PHP date format.
 * @param string $separator The separator to intelligently divide dates with.
 * @param int    $post_id Add an optional post ID. Default is current post.
 *
 *  return @dates
 */
function ecrm_get_event_no_year_dates( $format = 'M j', $separator = ' – ', $post_id = 0 ) {

	// Single Day Event.
	if ( ecrm_get_event_start() == ecrm_get_event_end() ) {

		$dates = ecrm_get_event_start( $format, $post_id );

		return $dates;
	}

	// Different Months.
	if ( ecrm_get_event_start( 'M' ) != ecrm_get_event_end( 'M' ) ) {

		$dates = ecrm_get_event_start( 'M j', $post_id ) . $separator . ecrm_get_event_end( 'M j', $post_id );

		return $dates;
	}

	// Different Days.
	if ( ecrm_get_event_start( 'j' ) != ecrm_get_event_end( 'j' ) ) {

		$dates = ecrm_get_event_start( 'M j', $post_id ) . $separator . ecrm_get_event_end( 'j', $post_id );

		return $dates;
	}
}

/**
 * Get the event's dates and intelligently formats them. THIS WILL BE DEPRECATED!!
 *
 * @param string $format The PHP date format.
 * @param string $separator The separator to intelligently divide dates with.
 * @param int    $post_id Add an optional post ID. Default is current post.
 *
 * Íreturn @dates
 */
function ecrm_get_event_day_dates( $format = 'l', $separator = ' – ', $post_id = 0 ) {

	// Single Day Event.
	if ( ecrm_get_event_start() == ecrm_get_event_end() ) {

		$dates = ecrm_get_event_start( $format, $post_id );

		return $dates;
	}

	// Different Days.
	if ( ecrm_get_event_start( 'l' ) != ecrm_get_event_end( 'l' ) ) {

		$dates = ecrm_get_event_start( 'l', $post_id ) . $separator . ecrm_get_event_end( 'l', $post_id );

		return $dates;
	}
}