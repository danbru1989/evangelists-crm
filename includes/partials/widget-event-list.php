<?php
/**
 * Adds a custom widget to display the upcoming events in a list.
 *
 * @package BrubakerDesignServices\EvangelistsCRM
 * @author  Dan Brubaker
 * @since   1.0.0
 */

add_action(
	'widgets_init',
	function() {
		register_widget( 'ECRM_Events_List_Widget' );
	}
);
/**
 * Creates the Events List widget.
 */
class ECRM_Events_List_Widget extends \WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'events-list-widget',
			'description' => 'Displays the upcoming events from the itinerary.',
		);

		parent::__construct( 'events-list-widget', 'Events List', $widget_ops );
	}


	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {

			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		// Events Query
		$args = array(
			'post_type'      => 'event',
			'posts_per_page' => 5,
			'orderby'        => 'meta_value',
			'meta_key'       => 'event_dates_end_date',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'event_dates_end_date',
					'value'   => date( 'Ymd' ),
					'type'    => 'DATE',
					'compare' => '>=',
				),
				array(
					'key'   => 'event_display_settings',
					'value' => 'public',
				),
			),
		);

		$events = new WP_Query( $args );

		if ( $events->have_posts() ) {

			echo '<ul class="ecrm-events-list" >';

			while ( $events->have_posts() ) {
				$events->the_post();

				$location         = get_field( 'event_organization' );
				$location_address = ecrm_get_short_address( get_field( 'physical_address', $location->ID ), false );

				if ( $location_address ) {
					$location_address = '<span class="location">' . $location_address . '</span>';
				}

				echo '<li>';

				echo '<a class="event-title" href="' . get_permalink() . '">' . get_the_title() . '</a><span class="datetime">' . ecrm_get_event_dates() . '</span>' . $location_address;

				echo '</li>';

			}
			wp_reset_postdata();

			echo '</ul>';
		}

		echo $args['after_widget'];
	}


	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Upcoming Meetings', ECRM_PLUGIN_TEXT_DOMAIN );
		?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
		<?php
	}


	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		foreach ( $new_instance as $key => $value ) {
			$updated_instance[ $key ] = sanitize_text_field( $value );
		}

		return $updated_instance;
	}
}
