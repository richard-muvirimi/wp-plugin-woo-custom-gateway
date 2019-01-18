<?php
if ( ! defined('WPINC') ) {
	die(); // Exit if accessed directly.
}

/**
 * Cassava Remit Payment Gateway
 * Provides a blue print class for a Payment Gateway.
 *
 * @class 		Woo_Custom_Gateway
 * @extends		WC_Payment_Gateway
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/public
 * @author Tyganeutronics <tygalive@gmail.com>
 */
class WC_Woo_Custom_Gateway extends WC_Payment_Gateway {

	/**
	 *
	 * @param int $id
	 */
	public function __construct( $id ) {

		$this->id = get_post_field('post_type', $id) . '-' . $id;

		$thumbId = get_post_thumbnail_id($id);
		if ( $thumbId ) {
			$this->icon = wp_get_attachment_image_url($thumbId, 'full');
		}

		$this->has_fields = false;
		$this->method_title = get_post_field('post_title', $id);
		$this->method_description = get_post_meta($id, 'woocg-desciption', true);

		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->title = $this->get_option('title');
		$this->description = $this->get_option('description');
		$this->instructions = $this->get_option('instructions', $this->description);
		$this->order_stat = $this->get_option('order_stat');

		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options'));
		add_action('woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page'));

		// Customer Emails
		add_action('woocommerce_email_before_order_table', array( $this, 'email_instructions'), 10, 3);

	}

	/**
	 * Output for the order received page.
	 *
	 * @param int $orderId
	 */
	public function thankyou_page( $orderId ) {

		if ( $this->instructions ) {
			echo wpautop(wptexturize($this->instructions));
		}

	}

	/**
	 * Add content to the WC emails.
	 *
	 * @access public
	 * @param WC_Order $order
	 * @param bool $sent_to_admin
	 * @param bool $plain_text
	 */
	public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {

		if ( $this->instructions && ! $sent_to_admin && $this->id === $order->get_payment_method() ) {

			// Go ahead only if the order has one of our statusses.
			if ( $order->has_status('on-hold') || $order->has_status('processing') ) {

				echo wpautop(wptexturize($this->instructions)) . PHP_EOL;
			}
		}

	}

	/**
	 * Initialise Gateway Settings Form Fields
	 */
	public function init_form_fields() {

		$this->form_fields = array( 'enabled' => array( 'title' => __('Enable/Disable', 'woocommerce'), 'type' => 'checkbox', 'label' => __(sprintf('Enable %s?', $this->method_title), 'woocommerce'), 'default' => 'yes'),
									'order_stat' => array( 'title' => __('Order status', 'woocommerce'),
														'type' => 'select',
														'description' => __('The setting controls the status that\'s being displayed on the order when it\'s placed.', 'woocommerce'),
														'default' => 'on-hold',
														'desc_tip' => false,
														'options' => array( 'on-hold' => __('On Hold', 'woocommerce'), 'processing' => __('Processing', 'woocommerce'))),
									'title' => array( 'title' => __('Title', 'woocommerce'), 'type' => 'text', 'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'), 'default' => __($this->method_title, 'woocommerce'), 'desc_tip' => false),
									'description' => array( 'title' => __('Description', 'woocommerce'), 'type' => 'textarea', 'description' => __('Payment method description that the customer will see on your checkout.', 'woocommerce'), 'default' => __('', 'woocommerce'), 'desc_tip' => false),
									'instructions' => array( 'title' => __('Instructions', 'woocommerce'), 'type' => 'textarea', 'description' => __('Instructions that will be added to the thank you page and emails.', 'woocommerce'), 'default' => '', 'desc_tip' => false));

	}

	/**
	 * Process the payment and return the result
	 *
	 * @param int $order_id
	 * @return array
	 */
	public function process_payment( $order_id ) {

		$order = wc_get_order($order_id);

		// Mark as on-hold (we're awaiting the payment)
		$order->update_status($this->order_stat, sprintf(__('Awaiting %s payment.', 'woo-custom-gateway'), $this->method_title));

		// Reduce stock levels
		// $order->reduce_order_stock();
		wc_reduce_stock_levels($order_id);

		// Remove cart
		WC()->cart->empty_cart();

		// Return thankyou redirect
		return array( 'result' => 'success', 'redirect' => $this->get_return_url($order));

	}
}
