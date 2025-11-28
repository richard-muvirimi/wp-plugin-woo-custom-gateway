<?php
/**
 * File for the Site controller
 *
 * Logic that is targetted for the front end can be placed here
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Controller
 *
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.0.0
 * @version 1.0.2
 */

namespace RichardMuvirimi\WooCustomGateway\Controller;

use RichardMuvirimi\WooCustomGateway\Helpers\Functions;
use WC_Order;

/**
 * Site side controller
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Controller
 *
 * @author Richard Muvirimi <erichard@tyganeutronics.com>
 * @since 1.0.0
 * @version 1.6.0
 */
class Site extends BaseController
{

    /**
     * Add content to the WC emails.
     *
     * @access public
     * @param WC_Order $order
     * @param bool $sent_to_admin
     * @param bool $plain_text
     * @version 1.6.0
     * @since 1.0.0
     */
    public function gateway_email_instructions(WC_Order $order, bool $sent_to_admin, bool $plain_text = false): void
    {

        $method = $order->get_payment_method();

        $gateway = Functions::gateway_instance($method);

        if ($gateway !== null) {

            $email_statuses = (array) $gateway->get_option("email_order_stat", array($gateway->get_default_order_status()));

            if (in_array(Functions::prefix_order_status($order->get_status()), $email_statuses)){

                $instructions = $gateway->get_option('email');
                if (!empty($instructions) && !$sent_to_admin) {
                    if ($plain_text) {
                        echo wptexturize(wp_strip_all_tags($instructions) . PHP_EOL);
                    } else {
                        echo wp_kses_post(wpautop(wptexturize($instructions)));
                    }
                }
            }
        }
    }

    /**
     * Output for the order received page.
     *
     * @param int|WC_Order $order
     *
     * @since 1.0.0
     * @version 1.6.0
     */
    public function woocommerce_thankyou($order): void
    {

        $order = wc_get_order($order);

        if ($order) {

            $method = $order->get_payment_method();

            $gateway = Functions::gateway_instance($method);

            if ($gateway !== null) {
                echo wp_kses_post(wpautop(wptexturize($gateway->get_option('instructions'))));
            }
        }
    }

}
