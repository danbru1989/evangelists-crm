<?php
/**
 * Contact Columns.
 *
 * @package      BrubakerDesignServices\EvangelistsCRM
 * @author       Dan Brubaker
 * @since        1.0.0
 **/

add_filter( 'manage_contact_posts_columns', 'ecrm_change_contact_columns_header' );
/**
 * Set Contacts column headers
 *
 * @param array $columns The default columns to filter.
 * @return $columns
 */
function ecrm_change_contact_columns_header( $columns ) {

	$columns = array(
		'cb'            => $columns['cb'],
		'title'         => 'Full Name',
		'category'      => 'Categories',
		'organization'  => 'Organization',
		'contact-info'  => 'Contact Info',
		'communication' => 'Last Communication',
	);

	return $columns;
}

add_action( 'manage_contact_posts_custom_column', 'ecrm_change_contact_columns_content', 10, 2 );
/**
 * Add Contacts column content.
 *
 * @param array   $column The column.
 * @param integer $post_id The current post's ID.
 * @return void
 */
function ecrm_change_contact_columns_content( $column, $post_id ) {

	// Category Column.
	if ( 'category' === $column ) {
		if ( ! has_term( '', 'contact_category' ) ) {
			echo '—';
		} else {
			$terms = ecrm_get_terms_list( $post_id, 'contact_category' );
			echo '<strong>' . $terms . '</strong>';
		}
	}

	// Organization Column.
	if ( 'organization' === $column ) {
		if ( ! get_field( 'organization' ) ) {
			echo '—';
		} else {
			$organization = get_field( 'organization' );
			$address      = ecrm_get_short_address( get_field( 'physical_address', $organization->ID ) );

			printf(
				'<a href="/wp-admin/post.php?post=%s&action=edit" alt="Edit Record"><strong>%s</strong></a><br/><strong>%s</strong>',
				$organization->ID,
				$organization->post_title,
				$address
			);

			if ( $organization->website ) {
				echo '<div>– <a href="' . $organization->website . '" target="_blank">View Website</a></div>';
			}
		}
	}

	// Contact Info Column.
	if ( 'contact-info' === $column ) {
		$phone = get_field( 'preferred_phone' );
		$email = get_field( 'preferred_email' );

		if ( ! $phone && ! $email ) {
			echo '—';
		} else {

			if ( $phone ) {
				printf( '<div><a href="tel:+1%1$s">%1$s</a></div>', $phone );
			}

			if ( $email ) {
				printf( '<div><a href="mailto:%1$s">%1$s</a></div>', $email );
			}
		}
	}

	// Last Communication Column.
	if ( 'communication' === $column ) {

		$communication = ecrm_add_communications_query( $post_id, 'communication_group_communication_contact', 1 );

		if ( $communication->have_posts() ) {

			while ( $communication->have_posts() ) {
				$communication->the_post();
				printf(
					'<a href="/wp-admin/post.php?post=%s&action=edit" target="_blank" alt="Edit Communication Entry"><strong>%s</strong></a>',
					get_the_ID(),
					get_field( 'communication_group_communication_date' )
				);
			}

			wp_reset_postdata();

		} else {
			echo '—';
		}
	}
}

// add_filter( 'manage_edit-contact_sortable_columns', 'ecrm_contacts_sortable_columns' );
/**
 * Set Sortable Columns
 *
 * @param array $columns The columns available.
 * @return $columns
 */
function ecrm_contacts_sortable_columns( $columns ) {
	$columns['category']      = 'category';
	$columns['communication'] = 'communication';
	return $columns;
}

// add_action( 'pre_get_posts', 'ecrm_contacts_columns_sort_query', 1 );
/**
 * Set a custom query to handle sorting by Latest Communication
 *
 * @param object $query WP_Query.
 * @return void
 */
function ecrm_contacts_columns_sort_query( $query ) {

	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( 'communication' === $query->get( 'orderby' ) ) {
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_key', 'communication_group_communication_date' );
			$query->set( 'meta_type', 'date' );
	}
}
