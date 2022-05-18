<?php

/**
 * Gateway Blue print file
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Model
 *
 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Rich4rdMuvirimi\WooCustomGateway\Model;

use Rich4rdMuvirimi\WooCustomGateway\Helpers\Functions;
use Rich4rdMuvirimi\WooCustomGateway\WooCustomGateway;
use WC_Payment_Gateway;

/**
 * Custom Payment Gateway
 * Provides a blue print class for a Payment Gateway.
 *
 * @class         Gateway
 * @extends        WC_Payment_Gateway
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Model
 *
 * @author https://tyganeutronics.com <rich4rdmuvirimi@gmail.com>
 * @since 1.0.0
 */
class Gateway extends WC_Payment_Gateway {

	/**
	 * Init payment gateway
	 *
	 * @param int $id
	 *
	 * @version 1.3.0
	 * @since 1.0.0
	 */
	public function __construct( $id ) {

		$this->id = Functions::gateway_id( $id );

		$thumbId = get_post_thumbnail_id( $id );

		if ( $thumbId ) {
			$this->icon = wp_get_attachment_image_url( $thumbId, 'full' );
		}

		$this->method_title       = get_post_field( 'post_title', $id );
		$this->method_description = get_post_meta( $id, 'woocg-desciption', true ); // ignore typo

		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->has_fields   = $this->get_option( 'note' ) == 'yes';
		$this->title        = $this->get_option( 'title' );
		$this->description  = $this->get_option( 'description' );
		$this->instructions = $this->get_option( 'instructions' );

		// append prefix if not present for compatibility with 1.0.7
		$status           = $this->get_option( 'order_stat' );
		$this->order_stat = str_starts_with( $status, 'wc-' ) ? $status : 'wc-' . $status;

		$this->register_hooks();

	}

	/**
	 * Register gateway hook
	 *
	 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return void
	 */
	public function register_hooks()
	{
		$loader = WooCustomGateway::instance();
		$loader->add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, $this, 'process_admin_options' );
	}

	/**
	 * show payment proof field
	 *
	 * @return void
	 * @version 1.3.0
	 * @since 1.2.0
	 */
	public function payment_fields() {
		if ( $this->has_fields ) {

			echo Functions::get_template( WOO_CUSTOM_GATEWAY_SLUG . '-proof-of-payment', array( 'description' => $this->description ), 'proof-of-payment.php' );

		} else {
			parent::payment_fields();
		}
	}

	/**
	 * Initialise Gateway Settings Form Fields
	 *
	 * @version 1.3.0
	 * @since 1.0.0
	 */
	public function init_form_fields() {

		$pending = wc_get_is_pending_statuses();

		$this->form_fields = array(
			'enabled'      => array(
				'title'   => __( 'Enable/Disable', WOO_CUSTOM_GATEWAY_SLUG ),
				'type'    => 'checkbox',
				'label'   => __( sprintf( 'Enable %s?', $this->method_title ), WOO_CUSTOM_GATEWAY_SLUG ),
				'default' => 'yes',
			),
			'order_stat'   => array(
				'title'       => __( 'Order Status', WOO_CUSTOM_GATEWAY_SLUG ),
				'type'        => 'select',
				'description' => __( 'Default order status when placed.', WOO_CUSTOM_GATEWAY_SLUG ),
				'default'     => empty( $pending ) ? current( wc_get_order_statuses() ) : current( $pending ),
				'desc_tip'    => false,
				'options'     => wc_get_order_statuses(),
			),
			'title'        => array(
				'title'       => __( 'Title', WOO_CUSTOM_GATEWAY_SLUG ),
				'type'        => 'text',
				'description' => __( 'The payment gateway title displayed during checkout.', WOO_CUSTOM_GATEWAY_SLUG ),
				'default'     => __( $this->method_title, WOO_CUSTOM_GATEWAY_SLUG ),
				'desc_tip'    => false,
			),
			'description'  => array(
				'title'       => __( 'Description', WOO_CUSTOM_GATEWAY_SLUG ),
				'type'        => 'textarea',
				'description' => __( 'The payment gateway description displayed during checkout.', WOO_CUSTOM_GATEWAY_SLUG ),
				'default'     => __( '', WOO_CUSTOM_GATEWAY_SLUG ),
				'desc_tip'    => false,
			),
			'note'         => array(
				'title'   => __( 'Payment Proof', WOO_CUSTOM_GATEWAY_SLUG ),
				'type'    => 'checkbox',
				'label'   => __( 'Allow users to provide payment proof when creating the order.', WOO_CUSTOM_GATEWAY_SLUG ),
				'default' => 'no',
			),
			'instructions' => array(
				'title'       => __( 'Thank you Instructions', WOO_CUSTOM_GATEWAY_SLUG ),
				'type'        => 'textarea',
				'description' => __( 'Instructions that will be added to the thank you page.', WOO_CUSTOM_GATEWAY_SLUG ),
				'default'     => '',
				'desc_tip'    => false,
			),
			'email'        => array(
				'title'       => __( 'Email Instructions', WOO_CUSTOM_GATEWAY_SLUG ),
				'type'        => 'textarea',
				'description' => __( 'Instructions that will be sent in order emails.', WOO_CUSTOM_GATEWAY_SLUG ),
				'default'     => '',
				'desc_tip'    => false,
			),
			'endpoints'    => array(
				'title'       => __( 'Endpoints', WOO_CUSTOM_GATEWAY_SLUG ),
				'type'        => 'textarea',
				'description' => __( 'Endpoints to ping after an order has been placed, each on a new line. (Only GET requests are supported at the moment)', WOO_CUSTOM_GATEWAY_SLUG ),
				'default'     => '',
				'placeholder' => site_url(),
				'desc_tip'    => false,
			),
		);
	}

	/**
	 * Process the payment and return the result
	 *
	 * @param  int $order_id
	 * @since 1.0.0
	 * @version 1.2.3
	 * @return array
	 */
	public function process_payment( $order_id ) {

		$order = wc_get_order( $order_id );

		// Mark as set order status (we're awaiting the payment)
		$order->update_status( $this->order_stat, sprintf( __( 'Awaiting %s payment.', WOO_CUSTOM_GATEWAY_SLUG ), $this->method_title ) );

		// Reduce stock levels
		wc_reduce_stock_levels( $order_id );

		if ( $this->has_fields ) {
			$note = filter_input( INPUT_POST, Functions::get_plugin_slug( '-note' ) );

			$note = sanitize_textarea_field( $note );
			if ( strlen( $note ) != 0 ) {
				$order->add_order_note( esc_html( $note ), 1, true );
			}
		}

		// Remove cart
		WC()->cart->empty_cart();

		// Ping urls
		$endpoints = wp_parse_list( $this->get_option( 'endpoints' ), array() );
		foreach ( $endpoints as $endpoint ) {

			if ( ! preg_match( '/(https?:\/\/)/', $endpoint ) ) {
				$endpoint = 'http://' . $endpoint;
			}

			$response = wp_remote_get( $endpoint );

			if ( ! is_wp_error( $response ) ) {
				$order->add_order_note( sprintf( __( 'Failed to ping %s', $endpoint ), WOO_CUSTOM_GATEWAY_SLUG ) );
			} else {
				$order->add_order_note( sprintf( __( 'Successfully pinged %s', $endpoint ), WOO_CUSTOM_GATEWAY_SLUG ) );
			}
		}

		// Return thankyou redirect
		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order ),
		);
	}
}
