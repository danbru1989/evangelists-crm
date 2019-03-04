<?php
/**
 * Organization Columns.
 *
 * @package      BrubakerDesignServices\EvangelistsCRM
 * @author       Dan Brubaker
 * @since        1.0.0
 **/

add_filter( 'manage_organization_posts_columns', 'ecrm_change_organization_columns_header' );
/**
 * Set Organizations column headers
 *
 * @param array $columns The default columns to filter.
 * @return $columns
 */
function ecrm_change_organization_columns_header( $columns ) {

	$columns = array(
		'cb'       => $columns['cb'],
		'title'    => 'Name',
		'location' => 'Location',
		'contact'  => 'Primary Contact',
		'lodging'  => 'Lodging',
		'category' => 'Categories',
	);

	return $columns;
}

add_action( 'manage_organization_posts_custom_column', 'ecrm_change_organization_columns_content', 10, 2 );
/**
 * Add organizations column content.
 *
 * @param array   $column The column.
 * @param integer $post_id The current post's ID.
 * @return void
 */
function ecrm_change_organization_columns_content( $column, $post_id ) {

	// Location Column.
	if ( 'location' === $column ) {
		if ( ! get_field( 'physical_address' ) ) {
			echo '—';
		} else {
			$address      = ecrm_get_short_address( get_field( 'physical_address' ) );
			$address_full = ecrm_get_long_address( get_field( 'physical_address' ) );

			echo '<strong>' . $address . '</strong>';

			echo '<div>– <a href="https://www.google.com/maps/dir/Current+Location/' . $address_full . '" target="_blank">Get Directions</a></div>';

			if ( get_field( 'website' ) ) {
				echo '<div>– <a href="' . esc_url( get_field( 'website' ) ) . '" target="_blank">View Website</a></div>';
			}
		}
	}

	// Primary Contact Column.
	if ( 'contact' === $column ) {
		if ( ! get_field( 'primary_organization_contact' ) ) {
			echo '—';
		} else {
			$contact = get_field( 'primary_organization_contact' );

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

	// Lodging Column.
	if ( 'lodging' === $column ) {
		$lodgings = get_field( 'facilities' );

		if ( ! get_field( 'facilities' ) ) {
			echo '—';
		} else {
			foreach ( $lodgings as $lodging ) {

				if ( $lodging === 'RV' && get_field( 'hookups' ) ) {
					$hookups = ' – ' . join( ', ', get_field( 'hookups' ) );
					echo '<div><strong>' . $lodging . '</strong>' . $hookups . '</div>';
				} else {
					echo '<div><strong>' . $lodging . '</strong></div>';
				}
			}
		}
	}

	// Category Column.
	if ( 'category' === $column ) {
		if ( ! has_term( '', 'organization_category' ) ) {
			echo '—';
		} else {
			$terms = ecrm_get_terms_list( $post_id, 'organization_category' );
			echo '<strong>' . $terms . '</strong>';
		}
	}
}

// add_filter( 'manage_edit-organization_sortable_columns', 'ecrm_organizations_sortable_columns' );
/**
 * Set Sortable Columns
 *
 * @param array $columns The columns available.
 * @return $columns
 */
function ecrm_organizations_sortable_columns( $columns ) {
	$columns['category']      = 'title';
	$columns['communication'] = 'communcation';
	return $columns;
}
