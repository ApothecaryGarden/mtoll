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
		add_filter( 'woocommerce_payment_complete_order_status', 	array( $this, 'autocomplete_virtual_orders', 10, 2 ) );
		add_filter( 'wc_get_template', 								array( $this, 'order_review_template' ), 10, 5 );

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

	public function autocomplete_virtual_orders( $order_status, $order_id ) {
		$order = new WC_Order( $order_id );
		if ( 'processing' == $order_status && ( 'on-hold' == $order->status || 'pending' == $order->status || 'failed' == $order->status ) ) {
			$virtual_order = null;
			if ( count( $order->get_items()) > 0 ) {
				foreach ( $order->get_items() as $item ) {
					if ( 'line_item' == $item['type'] ) {
						$_product = $order->get_product_from_item( $item );
						if ( !$_product->is_virtual() ) {
							$virtual_order = false;
							break;
						} else {
							$virtual_order = true;
						}
					}
				}
			}
			if ($virtual_order) {
				return 'completed';
			}
		}
		return $order_status;
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
}
