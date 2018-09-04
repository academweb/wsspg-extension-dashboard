<?php
/**
 * @package UberNavigation
 */
 namespace Inc;

class Activate
{
	public static function activate() {
		if ( !is_plugin_active( 'wsspg-woocommerce-stripe-subscription-payment-gateway/wsspg.php' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( 'You have to activate the WooCommerce Stripe Subscription Payment Gateway plugin!!!' );
		}	
	}
}
