<?php
/**
 * Event Columns.
 *
 * @package      BrubakerDesignServices\EvangelistsCRM
 * @author       Dan Brubaker
 * @since        1.0.0
 **/

add_filter( 'manage_edit-event_columns', 'ecrm_change_event_columns_header', 15 );
/**
 * Set Events column headers. Must use "manage_edit-event_columns" and priority 15 to override the Event Organiser Plugin's columns.
 *
 * @param array $columns The default columns to filter.
 * @return $columns
 */
function ecrm_change_event_columns_header( $columns ) {

	$columns = array(
		'cb'         => $columns['cb'],
		'title'      => 'Title',
		'start_date' => 'Start Date',
		'end_date'   => 'End Date',
		'location'   => 'Location',
		'contact'    => 'Contact',
		'category'   => 'Categories',
	);

	return $columns;
}

add_action( 'manage_event_posts_custom_column', 'ecrm_change_event_columns_content', 10, 2 );
/**
 * Add Events column content.
 *
 * @param array   $column The column.
 * @param integer $post_id The current post's ID.
 * @return void
 */
function ecrm_change_event_columns_content( $column, $post_id ) {

	// Start Date Column.
	if ( 'start_date' === $column ) {
		echo '<strong>' . ecrm_get_event_start( 'M j, Y' ) . '</strong>';
	}

	// End Date Column.
	if ( 'end_date' === $column ) {
		echo '<strong>' . ecrm_get_event_end( 'M j, Y' ) . '</strong>';
	}

	// Location Column.
	if ( 'location' === $column ) {
		if ( ! get_field( 'event_organization' ) ) {
			echo '—';
		} else {
			$location     = get_field( 'event_organization' );
			$address      = ecrm_get_short_address( get_field( 'physical_address', $location->ID ), false );
			$address_full = ecrm_get_long_address( get_field( 'physical_address', $location->ID ) );

			printf(
				'<a href="/wp-admin/post.php?post=%s&action=edit" alt="Edit Record"><strong>%s</strong></a><br/><strong>%s</strong><br/>– <a href="https://www.google.com/maps/dir/Current+Location/' . $address_full . '" target="_blank">Get Directions</a>',
				$location->ID,
				$location->post_title,
				$address,
				$address_full
			);

			if ( $location->website ) {
				echo '<div>– <a href="' . esc_url( $location->website ) . '" target="_blank">View Website</div>';
			}
		}
	}

	// Contact Column.
	if ( 'contact' === $column ) {
		if ( ! get_field( 'primary_event_contact' ) ) {
			echo '—';
		} else {
			$contact = get_field( 'primary_event_contact' );

			printf(
				'<a href="/wp-admin/post.php?post=%s&action=edit" alt="Edit Record"><strong>%s</strong></a>',
				$contact->ID,
				ecrm_get_full_title_contact( $contact )
			);

			if ( $contact->preferred_phone ) {
				printf( '<div>– <a href="tel:+1%1$s">%1$s</a></div>', $contact->preferred_phone );
			}

			if ( $contact->preferred_email ) {
				printf( '<div>– <a href="mailto:%1$s">%1$s</a></div>', $contact->preferred_email );
			}
		}
	}

	// Category Column.
	if ( 'category' === $column ) {
		if ( ! has_term( '', 'event_category' ) ) {
			echo '—';
		} else {
			$terms = ecrm_get_terms_list( $post_id, 'event_category' );

			echo '<strong>' . $terms . '</strong>';
		}
	}
}

// add_filter( 'manage_edit-event_sortable_columns', 'ecrm_events_sortable_columns' );
/**
 * Set Sortable Columns
 *
 * @param array $columns The columns available.
 * @return $columns
 */
function ecrm_events_sortable_columns( $columns ) {
	$columns['start_date'] = 'start_date';
	$columns['end_date']   = 'end_date';
	$columns['category']   = 'category';
	return $columns;
}

// add_action( 'pre_get_posts', 'ecrm_events_columns_sort_query', 1 );
/**
 * Set a custom query to handle sorting by Start Date, End Date, and Categories
 *
 * @param object $query WP_Query.
 * @return void
 */
function ecrm_events_columns_sort_query( $query ) {

	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( 'date' === $query->get( 'orderby' ) ) {
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_key', 'communication_group_communication_date' );
			$query->set( 'meta_type', 'date' );
	}
}
