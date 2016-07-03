<?php
/**
 * Mtoll Theme Settings
 * @version 1.0.0
 * @package Mtoll
 */

class M_Theme_Settings {
	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since 1.0.0
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 *
	 * @since  1.0.0
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
	 * @since  1.0.0
	 * @return void
	 */
	public function hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wpguru4u_google_fonts' ) );
	//	add_filter( 'wc_memberships_members_area_my-memberships_actions', array( $this, 'sv_edit_my_memberships_actions' ) );

	}

	public function wpguru4u_google_fonts() {
		$query_args = array(
			'family' => 'Stalemate:400|Alegreya:400|Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic',
		//	'family' => 'Stalemate:400|Niconne:400|Mrs+Saint+Delafield:400|Allura:400|Qwigley:400|Alex+Brush:400|Alegreya:400|Lustria:400',
			'subset' => 'latin,latin-ext',
		);
		wp_register_style( 'maia_fonts', add_query_arg( $query_args, 'https://fonts.googleapis.com/css' ), array(), null );
		wp_enqueue_style( 'maia_fonts' );
	}

	public function sv_edit_my_memberships_actions( $actions ) {
		// remove the "Cancel" action for members
		unset( $actions['cancel'] );
		return $actions;
	}

}
