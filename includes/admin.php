<?php
class MaiaToll_Admin {
	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key = 'maiatoll_options';
	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id = 'maiatoll_option_metabox';
	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = 'Maia\'s Options';
	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = 'Maia\'s Options';
	/**
	 * Holds an instance of the object
	 *
	 * @var MaiaToll_Admin
	 **/
	private static $instance = null;
	/**
	 * Constructor
	 * @since 0.1.0
	 */
	private function __construct() {
		// Set our title
		$this->title = __( 'Maia\'s Options', 'maiatoll' );
	}
	/**
	 * Returns the running object
	 *
	 * @return MaiaToll_Admin
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
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
	}
	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}
	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );
		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}
	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}
	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {
		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );
		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		$cmb->add_field( array(
			'name'             => 'Luna Lounge',
			'id'               => 'maiatoll_luna_lounge_open',
			'type'             => 'radio',
			'show_option_none' => false,
			'options'          => array(
				'open' => __( 'Open', 'cmb2' ),
				'closed'   => __( 'Closed', 'cmb2' ),
			),
		) );

		$cmb->add_field( array(
			'name'    => 'Lounge open image',
			'desc'    => '',
			'id'      => 'lounge_open_image',
			'type'    => 'file',
			// Optional:
			'options' => array(
				'url' => false, // Hide the text input for the url
			),
			'text'    => array(
				'add_upload_file_text' => 'Add Image' // Change upload button text. Default: "Add or Upload File"
			),
		) );

		$cmb->add_field( array(
			'name'    => 'Lounge closed image',
			'desc'    => '',
			'id'      => 'lounge_closed_image',
			'type'    => 'file',
			// Optional:
			'options' => array(
				'url' => false, // Hide the text input for the url
			),
			'text'    => array(
				'add_upload_file_text' => 'Add Image' // Change upload button text. Default: "Add or Upload File"
			),
		) );

		$cmb->add_field( array(
			'name'        => __( 'Link the Lounge Open image' ),
			'id'          => 'maiatoll_link_lounge_open_image',
			'type'        => 'post_search_text', // This field type
			// post type also as array
			'post_type'   => 'lounge',
			// Default is 'checkbox', used in the modal view to select the post type
			'select_type' => 'radio',
			// Will replace any selection with selection from modal. Default is 'add'
			'select_behavior' => 'replace',
		) );

		$cmb->add_field( array(
			'name'        => __( 'Witchcamp signup page - not the checkout funnel' ),
			'id'          => 'maiatoll_witchcamp_signup_page',
			'type'        => 'post_search_text', // This field type
			// post type also as array
			'post_type'   => 'page',
			// Default is 'checkbox', used in the modal view to select the post type
			'select_type' => 'radio',
			// Will replace any selection with selection from modal. Default is 'add'
			'select_behavior' => 'replace',
		) );

		$cmb->add_field( array(
			'name'        => __( 'Sign up/in content block page id' ),
			'id'          => 'maiatoll_witchcamp_sign_up_in',
			'type'        => 'post_search_text', // This field type
			// post type also as array
			'post_type'   => 'page',
			// Default is 'checkbox', used in the modal view to select the post type
			'select_type' => 'radio',
			// Will replace any selection with selection from modal. Default is 'add'
			'select_behavior' => 'replace',
		) );

		$cmb->add_field( array(
			'name'        => __( 'The Hub page' ),
			'id'          => 'maiatoll_hub_page',
			'type'        => 'post_search_text', // This field type
			// post type also as array
			'post_type'   => 'page',
			// Default is 'checkbox', used in the modal view to select the post type
			'select_type' => 'radio',
			// Will replace any selection with selection from modal. Default is 'add'
			'select_behavior' => 'replace',
		) );

		$cmb->add_field( array(
			'name'        => __( 'Redirect page if Lounge is closed' ),
			'id'          => 'maiatoll_luna_lounge_closed_redirect',
			'type'        => 'post_search_text', // This field type
			// post type also as array
			'post_type'   => 'page',
			// Default is 'checkbox', used in the modal view to select the post type
			'select_type' => 'radio',
			// Will replace any selection with selection from modal. Default is 'add'
			'select_behavior' => 'replace',
		) );

		$cmb->add_field( array(
			'name'        => __( 'Remove Order Again button' ),
			'desc'		  => 'helps with disallowing promos to be repurchased afterward',
			'id'          => 'maiatoll_remove_order_again',
			'type'        => 'post_search_text', // This field type
			// post type also as array
			'post_type'   => 'product',
			// Default is 'checkbox', used in the modal view to select the post type
			'select_type' => 'checkbox',
			// Will replace any selection with selection from modal. Default is 'add'
			'select_behavior' => 'replace',
		) );

		$cmb->add_field( array(
			'name'    => 'Home page flower oracle left box',
			'id'      => 'flower_oracle_home_left',
			'type'    => 'wysiwyg',
			'options' => array(
				'wpautop' => true, // use wpautop?
				'media_buttons' => false, // show insert/upload button(s)
				'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
				'teeny' => true, // output the minimal editor config used in Press This
				'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
				'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
			),
		) );

		$cmb->add_field( array(
			'name'    => 'Witch Camp left logo',
			'desc'    => '',
			'id'      => 'wc_left_logo',
			'type'    => 'file',
			// Optional:
			'options' => array(
				'url' => false, // Hide the text input for the url
			),
			'text'    => array(
				'add_upload_file_text' => 'Add Image' // Change upload button text. Default: "Add or Upload File"
			),
		) );

		$cmb->add_field( array(
			'name'    => 'Witch Camp right logo',
			'desc'    => '',
			'id'      => 'wc_right_logo',
			'type'    => 'file',
			// Optional:
			'options' => array(
				'url' => false, // Hide the text input for the url
			),
			'text'    => array(
				'add_upload_file_text' => 'Add Image' // Change upload button text. Default: "Add or Upload File"
			),
		) );

		$cmb->add_field( array(
			'name'    => 'Witch Camp mobile logo',
			'desc'    => '',
			'id'      => 'wc_mobile_logo',
			'type'    => 'file',
			// Optional:
			'options' => array(
				'url' => false, // Hide the text input for the url
			),
			'text'    => array(
				'add_upload_file_text' => 'Add Image' // Change upload button text. Default: "Add or Upload File"
			),
		) );

		$cmb->add_field( array(
			'name'    => 'Witch Camp sidebar logo',
			'desc'    => '',
			'id'      => 'wc_sidebar_logo',
			'type'    => 'file',
			// Optional:
			'options' => array(
				'url' => false, // Hide the text input for the url
			),
			'text'    => array(
				'add_upload_file_text' => 'Add Image' // Change upload button text. Default: "Add or Upload File"
			),
		) );

		$cmb->add_field( array(
			'name' => __( 'Where am I?', 'mtoll' ),
			'id' => $prefix . 'where_am_i',
			'type' => 'radio',
			'default' => 'maiatoll',
			'options' => array(
				'maiatoll' => __( 'maiatoll', 'mtoll' ),
				'witchcamp' => __( 'witchcamp', 'mtoll' ),
			),
		) );
	}
	/**
	 * Register settings notices for display
	 *
	 * @since  0.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}
		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'maiatoll' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}
	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}
		throw new Exception( 'Invalid property: ' . $field );
	}
}
/**
 * Helper function to get/return the MaiaToll_Admin object
 * @since  0.1.0
 * @return MaiaToll_Admin object
 */
function maiatoll_admin() {
	return MaiaToll_Admin::get_instance();
}
/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function maiatoll_get_option( $key = '' ) {
	return cmb2_get_option( maiatoll_admin()->key, $key );
}
// Get it started
maiatoll_admin();
