<?php
/**
 * Mtoll Points
 * @version 1.0.1
 * @package Mtoll
 */

class M_Points {
	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since 1.0.1
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 *
	 * @since  1.0.1
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  1.0.1
	 * @return void
	 */
	public function hooks() {
		add_action( 	'template_redirect', 	array( $this, 'maybe_give_points_at_lounge' ), 10 );
		add_action( 	'wp_insert_comment', 	array( $this, 'maybe_give_point_for_comment' ), 0, 2 );
		add_action( 	'template_redirect', 	array( $this, 'award_butterfly_badge' ) );
		add_shortcode( 	'mtoll_user_points', 	array( $this, 'mtoll_user_points_func' ) );
		add_filter( 	'the_title', 			array( $this, 'lounge_title' ), 10, 2 );
	}

	public function maybe_give_points_at_lounge() {
		global $post;

		if ( 'lounge' != get_post_type( $post->ID ) ) {
			return;
		}

		if ( get_current_user_id() ) {
			$user = get_current_user_id();
		} else {
			return;
		}

		$award = get_post_meta( $post->ID, 'm_lounge_point_for_attend', true );
		if ( ! empty( $award ) &&
			 ! badgeos_get_user_achievements(
			 	array(
			 		'achievement_id' => absint( $award ),
			 		'user_id' => $user
			 		)
				)
			)
		{
			badgeos_award_achievement_to_user( $award, $user );
		}
	}

	function award_butterfly_badge(){
		global $post;
		if ( '14875' == $post->ID &&
			 ! badgeos_get_user_achievements(
			 	array(
			 		'achievement_id' => absint( '13738' ),
			 		'user_id' => $user
			 		)
				)
			)
		{
			badgeos_award_achievement_to_user( '13738', get_current_user_id() );
		}
	}

	/**
	 * Check conditions
	 * @param  [type] $comment_ID [description]
	 * @param  [type] $comment    [description]
	 * @return [type]             [description]
	 */
	function maybe_give_point_for_comment( $comment_ID, $comment ) {
		// Enforce array for both hooks (wp_insert_comment uses object, comment_{status}_comment uses array)
		if ( is_object( $comment ) ) {
			$comment = get_object_vars( $comment );
		}

		// Check if comment is approved
		if ( 1 != (int) $comment[ 'comment_approved' ] ) {
			return;
		}

		// Check if user is a Premium member
		if ( ! wc_memberships_is_user_active_member( $comment['user_id'], '13845' ) ) {
			return;
		}
		$args = array(
			'post_id'	=> $comment['comment_post_ID'],
			'user_id'	=> $comment['user_id']
			);
		$comments_query = new WP_Comment_Query;
		$comments = $comments_query->query( $args );

		$total = count($comments);

		if ( '1' == $total ) {

			// Check if comment is on a blog post
			if ( 'post' === get_post_type( $comment['comment_post_ID'] ) ) {
				$this->point_for_comment( $comment );
			} else if ( 'lounge' === get_post_type( $comment['comment_post_ID'] ) ) {
				$this->point_for_lounge_comment( $comment );
			}
		}
	}

	/**
	 * Shortcode for the current user's total points
	 * @param  [type] $atts [description]
	 * @return string       [description]
	 */
	public function mtoll_user_points_func( $atts ) {
		return badgeos_get_users_points( get_current_user_id() );
	}

	public function lounge_title( $title, $id ) {
		if ( 'lounge' === get_post_type( $id ) ) {
			$new_title = get_post_meta( $id, 'm_lounge_title', true );
			if ( $new_title ) {
				$title = $new_title;
			}
		}
		return $title;
	}

	/**
	 * Updates points for blog post comments, but only one per post per user
	 * @param  [type] $comment    [description]
	 * @return [type]             [description]
	 */
	function point_for_comment( $comment ) {

		badgeos_award_achievement_to_user( '14810', $comment['user_id'] );
	}

	function point_for_lounge_comment( $comment ) {

		$award = get_post_meta( $comment['comment_post_ID'], 'm_lounge_point_for_comment', true );

		if ( ! empty( $award ) &&
			 ! badgeos_get_user_achievements(
			 	array(
			 		'achievement_id' => absint( $award ),
			 		'user_id' => $comment['user_id']
			 		)
			 	)
			)
		{
			$user = $comment['user_id'];
			badgeos_award_achievement_to_user( $award, $user );
		}
	}

}
