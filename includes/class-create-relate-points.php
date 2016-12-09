<?php
/**
 * Mtoll Create Relate Points
 * @version 1.0.0
 * @package Mtoll
 */

class M_Create_Relate_Points {
	/**
	 * Holds an instance of the object
	 *
	 * @var MaiaToll_Admin
	 **/
	private static $instance = null;

	private static $build = 0;
	private static $lounge_id = 0;
	private static $point_for_attend_ID = 0;
	private static $point_for_comment_ID = 0;

	/**
	 * Returns the running object
	 *
	 * @return M_Create_Relate_Points
	 **/
	public static function get_instance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->hooks();
		}
		return self::$instance;
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function hooks() {
		add_action( 'pending_lounge', array( __CLASS__, 'get_started' ), 10 );
		add_action( 'wp_insert_post', array( __CLASS__, 'update_the_things' ), 10 );
	}

	function get_started( $id ) {
		// Have we built the points?
		$built = get_post_meta( $id, '_m_lounge_points_built', true );
		// if so, exit
		if ( $built ){
			return;
		}
		// Are we on a lounge?
		if ( 'lounge' === get_post_type( $id ) ) {
			// store the post id
			self::$lounge_id = $id;
			self::only_once();
			self::create_attend_point();
			self::create_comment_point();
			self::$build = 1;
		}
	}

	static function create_attend_point() {
		// Create post object
		$point_for_attend = array(
			'post_title'    => 'Attend ' . self::$lounge_id,
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_type'		=> 'point',
		);

		// Insert the post into the database, store our ID
		self::$point_for_attend_ID = wp_insert_post( $point_for_attend );
	}

	static function create_comment_point() {
		// Create post object
		$point_for_comment = array(
			'post_title'    => 'Comment on ' . self::$lounge_id,
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_type'		=> 'point',
		);

		// Insert the post into the database, store our ID
		self::$point_for_comment_ID = wp_insert_post( $point_for_comment );
	}

	static function only_once(){
		update_post_meta( self::$lounge_id, '_m_lounge_points_built', 1 );
	}

	function update_the_things( $id ){

		if ( 0 != self::$lounge_id && $id == self::$lounge_id && 1 == self::$build ) {

			$var = get_post_meta( $id, '_m_lounge_points_built', true );
			if ( 2 != $var ) {

				$title = get_post_meta( $id, 'm_lounge_title', true );

				if ( self::$point_for_attend_ID ) {

					$a = self::$point_for_attend_ID;
					// Update Lounge UI with Point for attend post relationship
					update_post_meta( $id, 'm_lounge_point_for_attend', $a );
					// One point for attending, hidden from user
					update_post_meta( $a, '_badgeos_points', '1' );
					update_post_meta( $a, '_badgeos_hidden', 'hidden' );

					// Update title of Point for attend post
					$update = array(
						'ID'           => $a,
						'post_title'   => 'Attend ' . $title,
					);
					wp_update_post( $update, true );
				}

				if ( self::$point_for_comment_ID ) {

					$c = self::$point_for_comment_ID;
					// Update Lounge UI with Point for comment post relationship
					update_post_meta( $id, 'm_lounge_point_for_comment', $c );
					// Two points for commenting, hidden from user
					update_post_meta( $c, '_badgeos_points', '2' );
					update_post_meta( $c, '_badgeos_hidden', 'hidden' );

					// Update title of Point for comment post
					$update = array(
						'ID'           => $c,
						'post_title'   => 'Comment on ' . $title,
					);
					wp_update_post( $update, true );
				}

				update_post_meta( $id, '_m_lounge_points_built', 2 );

			}
		}
	}
}
