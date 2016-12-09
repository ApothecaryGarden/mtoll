<?php
/**
 * Plugin Name: Mtoll
 * Plugin URI:  https://github.com/ApothecaryGarden/mtoll/
 * Description: A little bit of Awesomeness for Maia
 * Version:     1.0.2
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
require_once dirname(__FILE__) . '/vendor/cpt-core/CPT_Core.php';
require_once dirname(__FILE__) . '/vendor/cmb2/init.php';

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

		// post types
		$this->flower_oracle = new M_Flower_Oracle( $this );

		// options panel
		require( self::dir( 'includes/admin.php' ) );

		// should probably be in the theme
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

add_action( 'init', 'update_my_custom_type', 99 );
/**
 * update_my_custom_type
 *
 * @author  Joe Sexton <joe@webtipblog.com>
 */
function update_my_custom_type() {
	global $wp_post_types;

	if ( post_type_exists( 'point' ) ) {
		// exclude from search results
		$wp_post_types['point']->exclude_from_search = true;
		$wp_post_types['badges']->exclude_from_search = true;
	}
}

add_action( 'wp_head', 'mtoll_fb_pixel_wp_head' );
function mtoll_fb_pixel_wp_head() {
	?>
	<!-- Facebook Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
	n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
	document,'script','https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '367652926691662');
	fbq('track', "PageView");</script>
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=367652926691662&ev=PageView&noscript=1"
	/></noscript>
	<!-- End Facebook Pixel Code -->
	<?php
}
