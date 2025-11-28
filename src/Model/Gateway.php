<?php

/**
 * Gateway Blue print file
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Model
 *
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.0.0
 * @version 1.0.0
 */

namespace RichardMuvirimi\WooCustomGateway\Model;

use RichardMuvirimi\WooCustomGateway\Helpers\Functions;
use RichardMuvirimi\WooCustomGateway\Helpers\Logger;
use RichardMuvirimi\WooCustomGateway\Helpers\Template;
use RichardMuvirimi\WooCustomGateway\WooCustomGateway;
use WC_Order;
use WC_Payment_Gateway;
use function \current as array_first;

/**
 * Custom Payment Gateway
 * Provides a blueprint class for a Payment Gateway.
 *
 * @property string $order_stat
 * @property string $instructions
 * @class         Gateway
 * @extends        WC_Payment_Gateway
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Model
 *
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.0.0
 */
class Gateway extends WC_Payment_Gateway
{

    /**
     * Init payment gateway
     *
     * @param int $id
     *
     * @version 1.6.0
     * @since 1.0.0
     */
    public function __construct(int $id)
    {

        $this->id = Functions::gateway_id($id);

        $thumbId = get_post_thumbnail_id($id);

        if ($thumbId) {
            $this->icon = wp_get_attachment_image_url($thumbId, 'full');
        }

        $this->method_title = get_post_field('post_title', $id);
        $this->method_description = get_post_meta($id, 'woocg-desciption', true); // ignore typo

        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables
        $this->has_fields = $this->get_option('note') == 'yes';
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->instructions = $this->get_option('instructions');

        // append prefix if not present for compatibility with 1.0.7
        $this->order_stat = Functions::prefix_order_status($this->get_option('order_stat'));

        $this->register_hooks();

    }

    /**
     * Initialise Gateway Settings Form Fields
     *
     * @version 1.6.0
     * @since 1.0.0
     */
    public function init_form_fields(): void
    {

        $order_statuses = wc_get_order_statuses();
        $default_status = $this->get_default_order_status();

        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', Functions::get_plugin_slug()),
                'type' => 'checkbox',
                'label' => __(sprintf('Enable %s?', $this->method_title), Functions::get_plugin_slug()),
                'default' => 'yes',
            ),
            'order_stat' => array(
                'title' => __('Order Status', Functions::get_plugin_slug()),
                'type' => 'select',
                'description' => __('Default order status after customer places an order.', Functions::get_plugin_slug()),
                'default' => $default_status,
                'desc_tip' => false,
                'options' => $order_statuses,
            ),
            'title' => array(
                'title' => __('Title', Functions::get_plugin_slug()),
                'type' => 'text',
                'description' => __('The payment gateway title displayed during checkout.', Functions::get_plugin_slug()),
                'default' => __($this->method_title, Functions::get_plugin_slug()),
                'desc_tip' => false,
                "sanitize_callback" => "sanitize_text_field"
            ),
            'description' => array(
                'title' => __('Description', Functions::get_plugin_slug()),
                'type' => 'editor',
                'description' => __('The payment gateway description displayed during checkout. (Will be placed above the payment proof field if it is enabled.)', Functions::get_plugin_slug()),
                'default' => __('', Functions::get_plugin_slug()),
                'desc_tip' => false,
            ),
            'note' => array(
                'title' => __('Payment Proof', Functions::get_plugin_slug()),
                'type' => 'checkbox',
                'label' => __('Allow customers to provide payment proof when creating the order. Only text-based proof can be submitted by customers.', Functions::get_plugin_slug()),
                'default' => 'no',
            ),
            'instructions' => array(
                'title' => __('Thank you Instructions', Functions::get_plugin_slug()),
                'type' => 'editor',
                'description' => __('Instructions that will be shown on the thank you page.', Functions::get_plugin_slug()),
                'desc_tip' => false,
            ),
            'email' => array(
                'title' => __('Email Instructions', Functions::get_plugin_slug()),
                'type' => 'editor',
                'description' => __('Instructions that will be sent in order emails. For plain text emails, HTML tags will be automatically stripped.', Functions::get_plugin_slug()),
                'desc_tip' => false,
            ),
            'email_order_stat' => array(
                'title' => __('Email Order Status', Functions::get_plugin_slug()),
                'type' => 'multiselect',
                'description' => __('Order statuses for which an instructions email will be sent.', Functions::get_plugin_slug()),
                'default' => $default_status,
                'desc_tip' => false,
                'options' => $order_statuses,
                "class" => "wc-enhanced-select-nostd",
            ),
            'endpoints' => array(
                'title' => __('Endpoints', Functions::get_plugin_slug()),
                'type' => 'textarea',
                'description' => __('Endpoints to ping after an order has been placed, each on a new line. (Only GET requests are supported at the moment.)', Functions::get_plugin_slug()),
                'placeholder' => site_url(),
                'desc_tip' => false,
                "sanitize_callback" => "sanitize_trackback_urls"
            ),
        );
    }

    /**
     * Get the default order status
     *
     * @return string
     * @since 1.5.5
     * @version 1.6.0
     *
     * @author Richard Muvirimi <richard@tyganeutronics.com>
     */
    public function get_default_order_status(): string
    {
        $pending = array_map(array(Functions::class, "prefix_order_status"), wc_get_is_pending_statuses());

        return empty($pending) ? array_first(wc_get_order_statuses()) : array_first($pending);
    }

    /**
     * Register gateway hook
     *
     * @return void
     * @since 1.0.0
     * @version 1.0.0
     *
     * @author Richard Muvirimi <richard@tyganeutronics.com>
     */
    public function register_hooks(): void
    {
        $loader = WooCustomGateway::instance();
        $loader->add_action('woocommerce_update_options_payment_gateways_' . $this->id, $this, 'process_admin_options');
    }

    /**
     * show payment proof field
     *
     * @return void
     * @version 1.3.0
     * @since 1.2.0
     */
    public function payment_fields(): void
    {
        parent::payment_fields();

        if ($this->has_fields) {
            echo Template::get_template(Functions::get_plugin_slug( '-proof-of-payment'), array('description' => $this->description, "id" => $this->id), 'proof-of-payment.php');
        }

    }

    /**
     * Process the payment and return the result
     *
     * @param int|WC_Order $order_id
     * @return array
     * @version 1.2.3
     * @since 1.0.0
     */
    public function process_payment($order_id): array
    {

        $order = wc_get_order($order_id);

        // Mark as set order status (we're awaiting the payment).
        $order->update_status($this->order_stat, sprintf(__('Awaiting %s payment.', Functions::get_plugin_slug()), $this->method_title));

        // Reduce stock levels.
        wc_reduce_stock_levels($order_id);

        if ($this->has_fields) {
            $note = filter_input(INPUT_POST, Functions::get_plugin_slug('-note-' . $this->id));

            $note = sanitize_textarea_field($note);
            if (strlen($note) != 0) {
                $order->add_order_note(esc_html($note), 1, true);
            }
        }

        // Remove cart.
        WC()->cart->empty_cart();

        // Ping urls.
        $endpoints = wp_parse_list($this->get_option('endpoints', array()));

        if (!empty($endpoints)) {
            Logger::logEvent("ping_urls_activated");
        }

        foreach ($endpoints as $endpoint) {

            if (!preg_match('/(https?:\/\/)/', $endpoint)) {
                $endpoint = 'https://' . $endpoint;
            }

            $response = wp_remote_get($endpoint);

            if (!is_wp_error($response)) {
                $order->add_order_note(sprintf(__('Failed to ping %s', $endpoint), Functions::get_plugin_slug()));
            } else {
                $order->add_order_note(sprintf(__('Successfully pinged %s', $endpoint), Functions::get_plugin_slug()));
            }
        }

        Logger::logEvent("payment_gateway_process_payment");

        // Return thank-you redirect.
        return array(
            'result' => 'success',
            'redirect' => $this->get_return_url($order),
        );
    }

    /**
     * Generate Editor Textarea HTML.
     *
     * @param string $key Field key.
     * @param array $data Field data.
     * @return string
     * @since  1.0.0
     *
     * @author Richard Muvirimi <richard@tyganeutronics.com>
     */
    public function generate_editor_html(string $key, array $data): string
    {
        $field_key = $this->get_field_key($key);
        $defaults = array(
            'title' => '',
            'disabled' => false,
            'class' => '',
            'css' => '',
            'placeholder' => '',
            'type' => 'text',
            'desc_tip' => false,
            'description' => '',
            'custom_attributes' => array(),
        );

        $data = wp_parse_args($data, $defaults);

        $gateway = $this;

        $args = compact('field_key', 'data', 'key', 'gateway');

        return Template::get_template(Functions::get_plugin_slug( 'admin-gateway-editor'), $args, 'admin-gateway-editor.php');

    }

    /**
     * Validate order Status field
     *
     * @param string $key
     * @param string $value
     * @return string
     * @since  1.5.0
     * @version 1.5.0
     *
     * @author Richard Muvirimi <richard@tyganeutronics.com>
     */
    public function validate_order_stat_field(string $key, string $value): string
    {
        $value = parent::validate_select_field($key, $value);

        return in_array($value, array_keys(wc_get_order_statuses())) ? $value : $this->get_default_order_status();
    }

    /**
     * Validate order Status field
     *
     * @param string $key
     * @param array $value
     * @return array
     * @since  1.6.0
     * @version 1.6.0
     *
     * @author Richard Muvirimi <richard@tyganeutronics.com>
     */
    public function validate_email_order_stat_field(string $key, array $value): array
    {
        $value = parent::validate_multiselect_field($key, $value);

        return array_intersect($value, array_keys(wc_get_order_statuses())) ?: array($this->get_default_order_status());
    }
}
