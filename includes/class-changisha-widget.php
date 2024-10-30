<?php

/**
 * Changisha Widget Class.
 *
 * @package Changisha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Changisha Widget Class.
 *
 * @class Changisha_Widget
 */
class Changisha_Widget extends WP_Widget {

	public function __construct() {

		parent::__construct(
			'changisha_widgets',
			__( 'Changisha', 'changisha' ),
			array ( 'description' => __( 'MPESA donation widget.', 'changisha' ), )
		);

	}

	public function widget( $args, $instance ) {

		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		$description = apply_filters( 'widget_description', $instance[ 'description' ] );
		
		_e( $args[ 'before_widget' ], 'changisha' );

		if ( ! empty( $title ) ) {
			_e( $args[ 'before_title' ] . $title . $args[ 'after_title' ], 'changisha' );
		}

		if ( ! empty( $description ) ) {
			_e( '<p>' . $description . '</p>', 'changisha' );
		}

		_e( '<div class="changisha-form-box"><form id="changishaForm" action="' . site_url() . '/index.php?changisha_donation_action=1" method="post">', 'changisha' );
		_e( '<div class="changisha-form-text"><input type="text" id="changishaName" name="changishaName" value="" placeholder="Name" style="width: 100%;" required /></div>', 'changisha' );
		_e( '<div class="changisha-form-text"><input type="number" id="changishaPhoneNumber" name="changishaPhoneNumber" value="" placeholder="Phone Number" style="width: 100%;" required /></div>', 'changisha' );
		_e( '<div class="changisha-form-text"><input type="number" id="changishaAmount" name="changishaAmount" value="" placeholder="Amount" style="width: 100%;" required /></div>', 'changisha' );
		_e( '<p id="alertBox" class="changisha-form-alert hide"></p>', 'changisha' );
		_e( '<div><button type="submit">Donate</button></div>', 'changisha' );
		_e( '</form></div>', 'changisha' );

		_e( $args[ 'after_widget' ], 'changisha' );
	}

	public function form( $instance ) {
		
		$title = ( ! empty( $instance[ 'title' ] ) )? $instance[ 'title' ] : __( 'Donate', 'changisha' );
		$description = ( ! empty( $instance[ 'description' ] ) )? $instance[ 'description' ] : __( 'Hello, make a small contribution via Lipa na MPesa aimed at making us the best software solutions website.', 'changisha' );
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'changisha' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description:', 'changisha' ); ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"><?php echo esc_attr( $description ); ?></textarea>
		</p>
<?php

	}

	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
		
		$instance[ 'title' ] = ( ! empty( $new_instance[ 'title' ] ) ) ? strip_tags( $new_instance[ 'title' ] ) : '';
		$instance[ 'description' ] = ( ! empty( $new_instance[ 'description' ] ) ) ? strip_tags( $new_instance[ 'description' ] ) : '';

		return $instance;
	
	}

}
