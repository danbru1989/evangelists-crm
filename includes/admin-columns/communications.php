<?php
/**
 * Communication Columns.
 *
 * @package      BrubakerDesignServices\EvangelistsCRM
 * @author       Dan Brubaker
 * @since        1.0.0
 **/

add_filter( 'manage_communication_posts_columns', 'ecrm_change_communication_columns_header' );

/**
 * Set Communication column headers
 *
 * @param array $columns The default columns to filter.
 * @return $columns
 */
function ecrm_change_communication_columns_header( $columns ) {

	$columns = array(
		'cb'        => $columns['cb'],
		'timestamp' => 'Date',
		'contact'   => 'Contact',
		'type'      => 'Type',
		'summary'   => 'Summary',
	);

	return $columns;
}

add_action( 'manage_communication_posts_custom_column', 'ecrm_change_communication_columns_content', 10, 2 );
/**
 * Add Communications column content.
 *
 * @param array   $column The column.
 * @param integer $post_id The current post's ID.
 * @return void
 */
function ecrm_change_communication_columns_content( $column, $post_id ) {

	$communication_group = get_field( 'communication_group' );

	// Date Column.
	if ( 'timestamp' === $column ) {
		echo '<a href="/wp-admin/post.php?post=' . $post_id . '&action=edit" alt="Edit Record"><strong>' . $communication_group['communication_date'] . '</strong></a>';
	}

	// Contact Column.
	if ( 'contact' === $column ) {
		$contact = ecrm_get_full_title_contact( $communication_group['communication_contact'] );

		printf(
			'<a href="/wp-admin/post.php?post=%s&action=edit" alt="Edit Record"><strong>%s</strong></a>',
			$communication_group['communication_contact'],
			$contact
		);
	}

	// Type Column.
	if ( 'type' === $column ) {
		$type = $communication_group['communication_type'];

		$type = ecrm_format_communication_type( $type );

		echo $type;
	}

	// Summary Column.
	if ( 'summary' === $column ) {

		if ( ! get_field( 'communication_notes' ) ) {
			echo '—';
		} else {
			$notes         = wp_strip_all_tags( get_field( 'communication_notes' ) );
			$notes_trimmed = wp_trim_words( $notes, 15 );

			echo $notes_trimmed;
		}
	}
}

add_filter( 'manage_edit-communication_sortable_columns', 'ecrm_communications_sortable_columns' );
/**
 * Set Sortable Columns
 *
 * @param array $columns The columns available.
 * @return $columns
 */
function ecrm_communications_sortable_columns( $columns ) {
	$columns['timestamp'] = 'date';
	$columns['contact']   = 'contact';
	return $columns;
}

add_action( 'pre_get_posts', 'ecrm_communications_columns_sort_query', 1 );
/**
 * Set a custom query to handle sorting by Date
 *
 * @param object $query WP_Query.
 * @return void
 */
function ecrm_communications_columns_sort_query( $query ) {

	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( 'date' === $query->get( 'orderby' ) ) {
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_key', 'communication_group_communication_date' );
			$query->set( 'meta_type', 'date' );
	}
}
