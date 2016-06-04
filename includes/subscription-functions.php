<?php
if ( ! function_exists( 'woocommerce_order_again_button' ) ) {
	function woocommerce_order_again_button( $order ) {
		if ( ! $order || ! $order->has_status( 'completed' ) || ! is_user_logged_in() ) {
			return;
		}
		$items = $order->get_items();
		$exc = maiatoll_get_option( 'maiatoll_remove_order_again' );
		$exc = explode( ',', $exc );
		foreach ( $items as $item ) {
			if ( in_array($item['product_id'], $exc )  ) {
				return;
			}
		}
		wc_get_template( 'order/order-again.php', array(
			'order' => $order
		) );
	//	return;
	}
}

/**
 * Remove the "Change Payment Method" button from the My Subscriptions table.
 *
 * This isn't actually necessary because @see eg_subscription_payment_method_cannot_be_changed()
 * will prevent the button being displayed, however, it is included here as an example of how to
 * remove just the button but allow the change payment method process.
 *
 * @link( https://gist.github.com/thenbrent/8851287 )
 */
function eg_remove_my_subscriptions_button( $actions, $subscription ) {
	foreach ( $actions as $action_key => $action ) {
		switch ( $action_key ) {
			case 'change_payment_method':	// Hide "Change Payment Method" button?
//			case 'change_address':		// Hide "Change Address" button?
//			case 'switch':			// Hide "Switch Subscription" button?
			case 'resubscribe':		// Hide "Resubscribe" button from an expired or cancelled subscription?
			case 'pay':			// Hide "Pay" button on subscriptions that are "on-hold" as they require payment?
			case 'reactivate':		// Hide "Reactive" button on subscriptions that are "on-hold"?
//			case 'cancel':			// Hide "Cancel" button on subscriptions that are "active" or "on-hold"?
				unset( $actions[ $action_key ] );
				break;
			default:
				error_log( '-- $action = ' . print_r( $action, true ) );
				break;
		}
	}
	return $actions;
}
add_filter( 'wcs_view_subscription_actions', 'eg_remove_my_subscriptions_button', 100, 2 );

/**
 * Do not allow a customer to resubscribe to an expired or cancelled subscription.
 */
// Just in case removing the button isn't enough
// https://gist.github.com/thenbrent/8851189
add_filter( 'wcs_can_user_resubscribe_to_subscription', '__return_false', 100 );
