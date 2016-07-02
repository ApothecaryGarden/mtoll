<?php
/**
 * Mtoll Luna Woofunnels
 * @version 1.0.0
 * @package Mtoll
 */

class M_Luna_Woofunnels {
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
	public function __construct() {
	//	$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function hooks() {
		add_filter( 'woocommerce_default_address_fields' , 			array( $this, 'override_default_address_fields' ) );
		add_filter( 'woocommerce_checkout_fields' , 				array( $this, 'override_checkout_fields' ) );
		add_filter( 'wc_get_template', 								array( $this, 'order_review_template' ), 10, 5 );
		add_filter( 'woocommerce_nyp_error_message_templates', 		array( $this, 'mtoll_woocommerce_nyp_error_message_templates' ) );
		add_action( 'wp_print_scripts', array( $this, 'wc_ninja_remove_password_strength' ), 100 );
	}

	public function override_checkout_fields( $fields ) {
		unset($fields['order']['order_comments']);
		unset($fields['billing']['billing_company']);
		unset($fields['billing']['billing_address_1']);
		unset($fields['billing']['billing_address_2']);
		unset($fields['billing']['billing_city']);
		unset($fields['billing']['billing_state']);
		unset($fields['billing']['billing_phone']);
		$fields['billing']['billing_email']['class'] = array('form-row-wide');
		$fields['billing']['billing_postcode']['class'] = array('form-row-wide');
		return $fields;
	}

	// Our hooked in function - $address_fields is passed via the filter!
	public function override_default_address_fields( $address_fields ) {
		$address_fields['company']['required'] = false;
		$address_fields['address_1']['required'] = false;
		$address_fields['address_2']['required'] = false;
		$address_fields['city']['required'] = false;
		$address_fields['state']['required'] = false;
		$address_fields['phone']['required'] = false;
		return $address_fields;
	}

	/**
	 * Hook to wc_get_template() and override the checkout template used on WooFunnels pages and when updating the order review fields
	 * via WC_Ajax::update_order_review()
	 *
	 * @return string
	 */
	public function order_review_template( $located, $template_name, $args, $template_path, $default_path ) {

		if ( 'checkout/review-order.php' == $template_name
			&& $default_path !== WooFunnels::dir( 'templates/' )
			&& is_woofunnels() ) {
			$located = wc_locate_template( 'woofunnels-checkout-form/review-order.php', '', WooFunnels::dir( 'templates/' ) );
		}

		return $located;
	}

	public function mtoll_woocommerce_nyp_error_message_templates( $message ) {
		$message['minimum_js'] = __( 'Hmmm... are you sure you\'re into this? Money is energy and it doesn\'t look like Witch Camp is where your energy is right now. I\'ll still love you if you treat yourself to a venti latte instead.', 'wc_name_your_price' );
		return $message;
	}

	/**
	 * Remove password strength meter
	 * @link( https://nicolamustone.com/2016/01/27/remove-the-password-strength-meter-on-the-checkout-page/, link)
	 * @return [type] [description]
	 */
	public function wc_ninja_remove_password_strength() {
		if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
			wp_dequeue_script( 'wc-password-strength-meter' );
		}
	}

}
