<?php
/**
 * Plugin Name: Mtoll
 * Plugin URI:  http://wpguru4u.com
 * Description: A little bit of Awesomeness for Maia
 * Version:     1.0.0
 * Author:      wpguru4u
 * Author URI:  http://wpguru4u.com
 * Donate link: http://wpguru4u.com
 * License:     GPLv2
 * Text Domain: mtoll
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2016 wpguru4u (email : wpguru4u@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using generator-plugin-wp
 */


/**
 * Autoloads files with classes when needed
 *
 * @since  1.0.0
 * @param  string $class_name Name of the class being requested.
 * @return void
 */
function mtoll_autoload_classes( $class_name ) {
	if ( 0 !== strpos( $class_name, 'M_' ) ) {
		return;
	}

	$filename = strtolower( str_replace(
		'_', '-',
		substr( $class_name, strlen( 'M_' ) )
	) );

	Mtoll::include_file( $filename );
}
spl_autoload_register( 'mtoll_autoload_classes' );


/**
 * Main initiation class
 *
 * @since  1.0.0
 * @var  string $version  Plugin version
 * @var  string $basename Plugin basename
 * @var  string $url      Plugin URL
 * @var  string $path     Plugin Path
 */
class Mtoll {

	/**
	 * Current version
	 *
	 * @var  string
	 * @since  1.0.0
	 */
	const VERSION = '1.0.0';

	/**
	 * URL of plugin directory
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $url = '';

	/**
	 * Path of plugin directory
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $path = '';

	/**
	 * Plugin basename
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $basename = '';

	/**
	 * Singleton instance of plugin
	 *
	 * @var Mtoll
	 * @since  1.0.0
	 */
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  1.0.0
	 * @return Mtoll A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin
	 *
	 * @since  1.0.0
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );

		$this->plugin_classes();
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function plugin_classes() {
		// Attach other plugin classes to the base plugin class.

	//	$this->maia_admin = new M_Maia_Admin( $this );
		$this->lounge = new M_Lounge( $this );
		$this->premium = new M_Premium( $this );
		require( self::dir( 'includes/class-badge143.php' ) );
		require( self::dir( 'includes/class-dynamic-lounge-image.php' ) );
		require( self::dir( 'includes/admin.php' ) );
		require( self::dir( 'includes/subscription-functions.php' ) );
	//	require( self::dir( 'includes/subscription-functions.php' ) );
	//	$this->luna_woofunnels = new M_Luna_Woofunnels( $this );
		$this->flower_oracle = new M_Flower_Oracle( $this );
		$this->theme_settings = new M_Theme_Settings( $this );
	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function hooks() {
		register_activation_hook( __FILE__, array( $this, '_activate' ) );
		register_deactivation_hook( __FILE__, array( $this, '_deactivate' ) );

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Activate the plugin
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function _activate() {
		// Make sure any rewrite functionality has been loaded.
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function _deactivate() {}

	/**
	 * Init hooks
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function init() {
		if ( $this->check_requirements() ) {
			load_plugin_textdomain( 'mtoll', false, dirname( $this->basename ) . '/languages/' );
		}
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  1.0.0
	 * @return boolean result of meets_requirements
	 */
	public function check_requirements() {
		if ( ! $this->meets_requirements() ) {

			// Add a dashboard notice.
			add_action( 'all_admin_notices', array( $this, 'requirements_not_met_notice' ) );

			// Deactivate our plugin.
			add_action( 'admin_init', array( $this, 'deactivate_me' ) );

			return false;
		}

		return true;
	}

	/**
	 * Deactivates this plugin, hook this function on admin_init.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function deactivate_me() {
		deactivate_plugins( $this->basename );
	}

	/**
	 * Check that all plugin requirements are met
	 *
	 * @since  1.0.0
	 * @return boolean True if requirements are met.
	 */
	public static function meets_requirements() {
		// Do checks for required classes / functions
		// function_exists('') & class_exists('').
		// We have met all requirements.
		return true;
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function requirements_not_met_notice() {
		// Output our error.
		echo '<div id="message" class="error">';
		echo '<p>' . sprintf( __( 'Mtoll is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'mtoll' ), admin_url( 'plugins.php' ) ) . '</p>';
		echo '</div>';
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  1.0.0
	 * @param string $field Field to get.
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
			case 'maia_admin':
			case 'lounge':
			case 'premium':
			case 'luna_woofunnels':
			case 'flower_oracle':
			case 'theme_settings':
				return $this->$field;
			default:
				throw new Exception( 'Invalid '. __CLASS__ .' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory
	 *
	 * @since  1.0.0
	 * @param  string $filename Name of the file to be included.
	 * @return bool   Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( 'includes/class-'. $filename .'.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}
		return false;
	}

	/**
	 * This plugin's directory
	 *
	 * @since  1.0.0
	 * @param  string $path (optional) appended path.
	 * @return string       Directory and path
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );
		return $dir . $path;
	}

	/**
	 * This plugin's url
	 *
	 * @since  1.0.0
	 * @param  string $path (optional) appended path.
	 * @return string       URL and path
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );
		return $url . $path;
	}
}

/**
 * Grab the Mtoll object and return it.
 * Wrapper for Mtoll::get_instance()
 *
 * @since  1.0.0
 * @return Mtoll  Singleton instance of plugin class.
 */
function mtoll() {
	return Mtoll::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( mtoll(), 'hooks' ) );


add_filter( 'woofunnels_checkout_page_templates', 'mllp_checkout_page_template' );
function mllp_checkout_page_template( $templates ) {
		$plugin_path  = plugin_dir_path( __FILE__ ) . 'templates/';
		$templates['maia-lunar-lounge'] = array(
		'label'       => __( 'Maia Lunar Lounge Signup', 'maiatoll' ),
			'description' => __( 'Signup Page', 'maiatoll' ),
			'path' => $plugin_path,
			'callback'		=> 'woofunnels_maia_lunar_lounge',
		);
		$templates['first-promo'] = array(
		'label'       => __( 'First Promo', 'maiatoll' ),
			'description' => __( 'Signup for the first promo', 'maiatoll' ),
			'path' => $plugin_path,
			'callback'		=> 'woofunnels_maia_lunar_lounge',
		);
		return $templates;
}

function woofunnels_maia_lunar_lounge() {
	new M_Luna_Woofunnels();
}


/**
 *
 */
add_filter( 'woofunnels_checkout_form_templates', 'mllp_checkout_form_template' );
function mllp_checkout_form_template( $templates ) {
		$plugin_path  = plugin_dir_path( __FILE__ ) . 'templates/';
		$templates['maia-lunar-lounge'] = array(
			'label'       => __( 'Lunar Lounge Form', 'maiatoll' ),
			'description' => __( 'for Maia Lunar Lounge Signup page template', 'maiatoll' ),
			'path' => $plugin_path,
			'callback'		=> 'woofunnels_maia_lunar_lounge_checkout_form',
		);
		$templates['first-promo'] = array(
			'label'       => __( 'First Promo Form', 'maiatoll' ),
			'description' => __( 'for the first promo', 'maiatoll' ),
			'path' => $plugin_path,
			'callback'		=> 'woofunnels_maia_lunar_lounge_checkout_form',
		);
		return $templates;
}

function woofunnels_maia_lunar_lounge_checkout_form(){
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
}

/**
 *
 */
add_filter( 'woofunnels_product_block_templates', 'mllp_product_block_template' );
function mllp_product_block_template( $templates ) {
	$plugin_path  = plugin_dir_path( __FILE__ ) . 'templates/';
	$templates['maia-lunar-lounge-table'] = array(
		'label'       => __( 'Lunar Lounge Form', 'maiatoll' ),
		'description' => __( 'for Maia Lunar Lounge Signup page template', 'maiatoll' ),
		'path' => $plugin_path,
	);
	$templates['first-promo-block'] = array(
		'label'       => __( 'First Promo Block', 'maiatoll' ),
		'description' => __( '', 'maiatoll' ),
		'path' => $plugin_path,
	);
	return $templates;
}

/**
 *
 */


function maia_login_redirect_url() {
		$id  = get_queried_object_id();
		$url = wc_get_page_permalink( 'myaccount' );

		if ( is_singular() ) {
			$redirect_to = 'post';
		} elseif ( isset( get_queried_object()->term_id ) ) {
			$redirect_to = get_queried_object()->taxonomy;
		} else {
			$redirect_to = '';
		}

		if ( ! empty( $redirect_to ) ) {

			$url = add_query_arg( array(
				'wcm_redirect_to' => $redirect_to,
				'wcm_redirect_id' => $id,
			), $url );
		}

		return esc_url( $url );
}

add_filter( 'wc_memberships_content_restricted_message', 'mtoll_master_membership_filter', 10, 3 );
function mtoll_master_membership_filter( $message, $post_id, $access_time ) {
	if ( 'lounge' === get_post_type( $post_id )
		|| 'premium' === get_post_type( $post_id ) ) {

	//	$message = 'To access this content, you must <a href="http://staging.bizarre-cord.flywheelsites.com/woofunnels_checkout/lunar-lounge-signup/?empty-cart&add-to-cart=13594">signup for a free membership</a>, or <a href="' . maia_login_redirect_url() . '">log in</a> if you are a member.';
		$message = 'To access this content, you must <a href="' . esc_url( get_permalink( maiatoll_get_option( 'maiatoll_witchcamp_signup_page' ) ) ) . '">sign up</a>, or <a href="' . maia_login_redirect_url() . '">log in</a> if you are a member.';

	}
	return $message;
}

// add_filter( 'wc_memberships_content_restricted_message', 'sv_filter_content_delayed_message1', 10, 3 );
function sv_filter_content_delayed_message1( $message, $post_id, $access_time ) {
	if ( 'premium' === get_post_type( $post_id ) ) {

		$message = 'To access this content, you must <a href="http://staging.bizarre-cord.flywheelsites.com/woofunnels_checkout/lunar-lounge-signup/?empty-cart&add-to-cart=13593">purchase a premium membership</a>, or <a href="' . maia_login_redirect_url() . '">log in</a> if you are a member.';

	}
	return $message;
}

add_action('template_redirect', 'lounge_closed');
function lounge_closed(){
// echo maiatoll_get_option( 'radio' );
	if ( is_singular( 'lounge' ) && 'closed' === maiatoll_get_option( 'maiatoll_luna_lounge_open' ) ) {
		wp_redirect( esc_url( get_permalink( maiatoll_get_option( 'maiatoll_luna_lounge_closed_redirect' ) ) ) );
		exit;
	}
//	if ( ( is_singular( 'premium' ) || is_post_type_archive( 'premium' ) ) && 'closed' === maiatoll_get_option( 'maiatoll_luna_lounge_premium_open' ) ) {
//		wp_redirect( esc_url( get_permalink( maiatoll_get_option( 'maiatoll_luna_lounge_premium_closed_post' ) ) ) );
//		exit;
//	}
}


add_action( 'cmb2_admin_init', 'maia_landing_page_metabox' );
function maia_landing_page_metabox() {

	$prefix = '_maialpt_';

	$cmb = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'Landing Page Options', 'mtoll' ),
		'object_types' => array( 'page' ),
		'context'      => 'normal',
		'priority'     => 'default',
	) );

	$cmb->add_field( array(
		'name' => __( 'Header', 'mtoll' ),
		'id' => $prefix . 'header',
		'type' => 'radio',
		'options' => array(
			'header_with_menu' => __( 'Default Menu', 'mtoll' ),
			'header_no_menu' => __( 'No menu', 'mtoll' ),
		),
	) );

	$cmb->add_field( array(
		'name' => __( 'Footer', 'mtoll' ),
		'id' => $prefix . 'footer',
		'type' => 'radio',
		'options' => array(
			'footer_ig_soc_bot' => __( 'IG, Social, Bottom', 'mtoll' ),
			'footer_soc_bot' => __( 'Social, Bottom', 'mtoll' ),
			'footer_bot' => __( 'Bottom', 'mtoll' ),
		),
	) );

}

function no_self_ping( &$links ) {
	$home = get_option( 'home' );
	foreach ( $links as $l => $link )
		if ( 0 === strpos( $link, $home ) )
			unset($links[$l]);
}

add_action( 'pre_ping', 'no_self_ping' );
