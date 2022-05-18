<?php
/**
 * File for the Site controller
 *
 * Logic that is targetted for the front end can be placed here
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Controller
 *
 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
 * @since 1.0.0
 * @version 1.0.2
 */

namespace Rich4rdMuvirimi\WooCustomGateway\Controller;

use Rich4rdMuvirimi\WooCustomGateway\Helpers\Functions;

/**
 * Site side controller
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Controller
 *
 * @author Richard Muvirimi <erich4rdmuvirimi@gmail.com>
 * @since 1.0.0
 * @version 1.0.2
 */
class Site extends BaseController {

	/**
	 * Add content to the WC emails.
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.2.0
	 * @param WC_Order $order
	 * @param bool     $sent_to_admin
	 * @param bool     $plain_text
	 */
	public function gateway_email_instructions( $order, $sent_to_admin, $plain_text = false ) {

		$method = $order->get_payment_method();

		$gateway = Functions::gateway_instance( $method );

		if ( $gateway ) {

			$instructions = $gateway->get_option( 'email' );
			if ( ! empty( $instructions ) && ! $sent_to_admin ) {
				if ( $plain_text ) {
					echo wptexturize( $instructions . PHP_EOL );
				} else {
					echo wp_kses_post( wpautop( wptexturize( $instructions ) ) );
				}
			}
		}
	}

			/**
	 * Output for the order received page.
	 *
	 * @param int $order
	 *
	 * @since 1.0.0
	 * @version 1.3.0
	 */
	public function woocommerce_thankyou( $order ) {

		$order = wc_get_order($order);
		
		if ($order){
			
			$method = $order->get_payment_method() ;
			
			$gateway = Functions::gateway_instance($method );
		
				if ( $gateway ) {
					echo wp_kses_post( wpautop( wptexturize( $gateway->get_option( 'instructions' ) ) ) );
				}
			}
		}

}
