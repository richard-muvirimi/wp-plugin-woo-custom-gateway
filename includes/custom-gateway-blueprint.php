<?php

if (!defined('WPINC')) {
    die(); // Exit if accessed directly.
}

/**
 * Cassava Remit Payment Gateway
 * Provides a blue print class for a Payment Gateway.
 *
 * @class         Woo_Custom_Gateway
 * @extends        WC_Payment_Gateway
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/public
 *
 * @author https://tyganeutronics.com <tygalive@gmail.com>
 * @since 1.0.0
 * 
 */
class WC_Woo_Custom_Gateway extends WC_Payment_Gateway
{

    /**
     *
     * @param int $id
     * 
     * @version 1.1.0
     * @since 1.0.0
     */
    public function __construct($id)
    {

        $this->id = get_post_field('post_type', $id) . '-' . $id;

        $thumbId = get_post_thumbnail_id($id);

        if ($thumbId) {
            $this->icon = wp_get_attachment_image_url($thumbId, 'full');
        }

        $this->method_title       = get_post_field('post_title', $id);
        $this->method_description = get_post_meta($id, 'woocg-desciption', true); // ignore typo

        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables
        $this->has_fields   = $this->get_option('note') == "yes";
        $this->title        = $this->get_option('title');
        $this->description  = $this->get_option('description');
        $this->instructions = $this->get_option('instructions');

        //append prefix if not present for compatibility with 1.0.7
        $status = $this->get_option('order_stat');
        $this->order_stat   = 'wc-' === substr($status, 0, 3) ? $status  : 'wc-' . $status;

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));

        // Customer Emails
        add_action('woocommerce_email_before_order_table', array($this, 'email_instructions'), 10, 3);
    }

    /**
     * Output for the order received page.
     *
     * @param int $orderId
     * @since 1.0.0
     * @version 1.2.0
     */
    public function thankyou_page($orderId)
    {

        if ($this->instructions) {
            echo wp_kses_post(wpautop(wptexturize($this->instructions)));
        }
    }

    /**
     * Validate payment proof field
     *
     * @return bool
     * @version 1.2.0
     * @since 1.2.0
     */
    public function validate_fields()
    {
        if ($this->has_fields) {
            $note = filter_input(INPUT_POST, WOO_CUSTOM_GATEWAY_SLUG . "-note", FILTER_SANITIZE_STRING);

            $note = sanitize_textarea_field($note);
            if (strlen($note) == 0) {
                wc_add_notice(__('Please complete the payment information.', WOO_CUSTOM_GATEWAY_SLUG), 'error');
                return false;
            }
        }
        return parent::validate_fields();
    }

    /**
     * show payment proof field
     *
     * @return void
     * @version 1.2.0
     * @since 1.2.0
     */
    public function payment_fields()
    {
        if ($this->has_fields) {
            include plugin_dir_path(__DIR__) . "public/partials/woo-custom-gateway-public-display.php";
        } else {
            parent::payment_fields();
        }
    }

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
    public function email_instructions($order, $sent_to_admin, $plain_text = false)
    {
        // Go ahead only if the order was created by us.
        if ($this->instructions && !$sent_to_admin && $this->id === $order->get_payment_method()) {
            if ($plain_text) {
                echo wptexturize($this->instructions . PHP_EOL);
            } else {
                echo wp_kses_post(wpautop(wptexturize($this->instructions)));
            }
        }
    }

    /**
     * Initialise Gateway Settings Form Fields
     * 
     * @version 1.2.0
     * @since 1.0.0
     */
    public function init_form_fields()
    {

        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', WOO_CUSTOM_GATEWAY_SLUG),
                'type' => 'checkbox',
                'label' => __(sprintf('Enable %s?', $this->method_title), WOO_CUSTOM_GATEWAY_SLUG),
                'default' => 'yes'
            ),
            'order_stat' => array(
                'title' => __('Order Status', WOO_CUSTOM_GATEWAY_SLUG),
                'type' => 'select',
                'description' => __('Default order status when placed.', WOO_CUSTOM_GATEWAY_SLUG),
                'default' => wc_get_is_pending_statuses()[0] ?: wc_get_order_statuses()[0],
                'desc_tip' => false,
                'options' => wc_get_order_statuses()
            ),
            'title' => array(
                'title' => __('Title', WOO_CUSTOM_GATEWAY_SLUG),
                'type' => 'text',
                'description' => __('The payment gateway title displayed during checkout.', WOO_CUSTOM_GATEWAY_SLUG),
                'default' => __($this->method_title, WOO_CUSTOM_GATEWAY_SLUG),
                'desc_tip' => false
            ),
            'description' => array(
                'title' => __('Description', WOO_CUSTOM_GATEWAY_SLUG),
                'type' => 'textarea',
                'description' => __('The payment gateway description displayed during checkout.', WOO_CUSTOM_GATEWAY_SLUG),
                'default' => __('', WOO_CUSTOM_GATEWAY_SLUG),
                'desc_tip' => false
            ),
            'note' => array(
                'title' => __('Payment Proof', WOO_CUSTOM_GATEWAY_SLUG),
                'type' => 'checkbox',
                'label' => __('Allow users to provide payment proof when creating the order.', WOO_CUSTOM_GATEWAY_SLUG),
                'default' => 'no'
            ),
            'instructions' => array(
                'title' => __('Instructions', WOO_CUSTOM_GATEWAY_SLUG),
                'type' => 'textarea',
                'description' => __('Instructions that will be added to the thank you page and emails.', WOO_CUSTOM_GATEWAY_SLUG),
                'default' => '',
                'desc_tip' => false
            )
        );
    }

    /**
     * Process the payment and return the result
     *
     * @param  int     $order_id
     * @since 1.0.0
     * @version 1.2.1
     * @return array
     */
    public function process_payment($order_id)
    {

        $order = wc_get_order($order_id);

        // Mark as set order status (we're awaiting the payment)
        $order->update_status($this->order_stat, sprintf(__('Awaiting %s payment.', WOO_CUSTOM_GATEWAY_SLUG), $this->method_title));

        // Reduce stock levels
        wc_reduce_stock_levels($order_id);

        if ($this->has_fields) {
            $note = filter_input(INPUT_POST, WOO_CUSTOM_GATEWAY_SLUG . "-note", FILTER_SANITIZE_STRING);

            $note = sanitize_textarea_field($note);
            if (strlen($note) != 0) {
                $order->add_order_note(esc_html($note), 1, true);
            }
        }

        // Remove cart
        WC()->cart->empty_cart();

        // Return thankyou redirect

        return array('result' => 'success', 'redirect' => $this->get_return_url($order));
    }
}