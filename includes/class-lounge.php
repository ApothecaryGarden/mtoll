<?php
/**
 * Mtoll Lounge
 *
 * @version 1.0.0
 * @package Mtoll
 */

require_once dirname(__FILE__) . '/../vendor/cpt-core/CPT_Core.php';
require_once dirname(__FILE__) . '/../vendor/cmb2/init.php';

class M_Lounge extends CPT_Core {
	/**
	 * Parent plugin class
	 *
	 * @var class
	 * @since  1.0.0
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 * Register Custom Post Types. See documentation in CPT_Core, and in wp-includes/post.php
	 *
	 * @since  1.0.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();

		// Register this cpt
		// First parameter should be an array with Singular, Plural, and Registered name.
		parent::__construct(
			array( __( 'Lounge', 'mtoll' ), __( 'Lounges', 'mtoll' ), 'lounge' ),
			array( 'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'post-formats', 'comments' ) ),
			array( 'has_archive' => false )
		);
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function hooks() {
		add_action( 'cmb2_init', array( $this, 'fields' ) );
	}

	/**
	 * Add custom fields to the CPT
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function fields() {
		$prefix = 'm_lounge_';

		$cmb = new_cmb2_box( array(
			'id'            => $prefix . 'metabox',
			'title'         => __( 'Lounge Meta Box', 'mtoll' ),
			'object_types'  => array( 'lounge' ),
		) );

		$cmb->add_field( array(
			'name'    => 'Title',
			'id'      => $prefix . 'title',
			'type'    => 'text'
		) );

		$cmb->add_field( array(
			'name'        => __( 'Award for commenting on this lounge' ),
			'id'          => $prefix . 'point_for_comment',
			'type'        => 'post_search_text', // This field type
			// post type also as array
			'post_type'   => 'point',
			// Default is 'checkbox', used in the modal view to select the post type
			'select_type' => 'radio',
			// Will replace any selection with selection from modal. Default is 'add'
			'select_behavior' => 'replace',
		) );

		$cmb->add_field( array(
			'name'        => __( 'Award for attending this lounge' ),
			'id'          => $prefix . 'point_for_attend',
			'type'        => 'post_search_text', // This field type
			// post type also as array
			'post_type'   => 'point',
			// Default is 'checkbox', used in the modal view to select the post type
			'select_type' => 'radio',
			// Will replace any selection with selection from modal. Default is 'add'
			'select_behavior' => 'replace',
		) );

	}

	/**
	 * Registers admin columns to display. Hooked in via CPT_Core.
	 *
	 * @since  1.0.0
	 * @param  array $columns Array of registered column names/labels.
	 * @return array          Modified array
	 */
	public function columns( $columns ) {
		$new_column = array();
		return array_merge( $new_column, $columns );
	}

	/**
	 * Handles admin column display. Hooked in via CPT_Core.
	 *
	 * @since  1.0.0
	 * @param array $column  Column currently being rendered.
	 * @param int   $post_id ID of post to display column for.
	 */
	public function columns_display( $column, $post_id ) {
		switch ( $column ) {
		}
	}
}
