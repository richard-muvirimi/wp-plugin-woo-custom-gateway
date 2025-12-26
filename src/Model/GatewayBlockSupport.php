<?php
/**
 * Gateway Block Support file
 *
 * Handles WooCommerce Blocks integration for custom payment gateways
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Model
 *
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.6.4
 * @version 1.6.4
 */

namespace RichardMuvirimi\WooCustomGateway\Model;

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;
use RichardMuvirimi\WooCustomGateway\Helpers\Functions;
use RichardMuvirimi\WooCustomGateway\Helpers\Template;
use RichardMuvirimi\WooCustomGateway\WooCustomGateway;

/**
 * Gateway Block Support Class
 *
 * Integrates custom payment gateways with WooCommerce block-based checkout
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Model
 *
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.6.4
 */
class GatewayBlockSupport extends AbstractPaymentMethodType
{
    /**
     * Gateway instance
     *
     * @var Gateway
     *
     * @since 1.6.4
     * @version 1.6.4
     */
    private $gateway;

    /**
     * Gateway post ID
     *
     * @var int
     *
     * @since 1.6.4
     * @version 1.6.4
     */
    private $gateway_id;

    /**
     * Constructor
     *
     * @param int $gateway_id Gateway post ID
     *
     * @since 1.6.4
     * @version 1.6.4
     */
    public function __construct(int $gateway_id)
    {
        $this->gateway_id = $gateway_id;
        $this->gateway = new Gateway($gateway_id);
        $this->name = $this->gateway->id;
    }

    /**
     * Initialize the block support
     *
     * @return void
     *
     * @since 1.6.4
     * @version 1.6.4
     */
    public function initialize(): void
    {
        $this->settings = $this->gateway->settings;
        
        // Hook into Store API payment processing
        $loader = WooCustomGateway::instance();
        $loader->add_action('woocommerce_rest_checkout_process_payment_with_context', $this, 'process_payment_with_context', 10, 2);
    }

    /**
     * Process payment with Store API context
     *
     * Handles payment data from block checkout via Store API
     *
     * @param \Automattic\WooCommerce\StoreApi\Utilities\PaymentContext $context Payment context
     * @param \Automattic\WooCommerce\StoreApi\Utilities\PaymentResult  $result  Payment result
     *
     * @return void
     *
     * @since 1.6.4
     * @version 1.6.4
     */
    public function process_payment_with_context($context, &$result): void
    {
        // Only process for this specific payment method
        if ($context->payment_method !== $this->name) {
            return;
        }

        // Get the payment note from payment_data
        $field_name = Functions::get_payment_note_field_name($this->gateway->id);
        
        if (isset($context->payment_data[$field_name]) && $context->order) {
            Functions::save_payment_note_to_order($context->order, $context->payment_data[$field_name]);
        }
    }

    /**
     * Check if the payment method is active
     *
     * @return bool
     *
     * @since 1.6.4
     * @version 1.6.4
     */
    public function is_active(): bool
    {
        return $this->gateway->is_available();
    }

    /**
     * Get the payment method script handles
     *
     * @return array
     *
     * @since 1.6.4
     * @version 1.6.4
     */
    public function get_payment_method_script_handles(): array
    {
        $script_path = 'dist/blocks/payment-gateway/index.js';
        $script_asset_path = Template::get_script_path('dist/blocks/payment-gateway/index.asset.php');
        $script_url = Template::get_script_url($script_path);

        $script_asset = Template::get_script_dependencies($script_asset_path);

        $handle = Functions::get_plugin_slug('-blocks-' . $this->gateway_id);

        wp_register_script(
            $handle,
            $script_url,
            $script_asset['dependencies'],
            $script_asset['version'],
            true
        );

        wp_set_script_translations(
            $handle,
            Functions::get_plugin_slug(),
            plugin_dir_path(WOO_CUSTOM_GATEWAY_FILE) . 'languages'
        );

        return [$handle];
    }

    /**
     * Get payment method data for the frontend
     *
     * @return array
     *
     * @since 1.6.4
     * @version 1.6.4
     */
    public function get_payment_method_data(): array
    {
        return [
            'title' => $this->gateway->title,
            'description' => $this->gateway->description,
            'icon' => $this->gateway->icon,
            'supports' => array_filter($this->gateway->supports, [$this->gateway, 'supports']),
            'hasFields' => $this->gateway->has_fields,
            'gatewayId' => $this->gateway->id,
            'postId' => $this->gateway_id,
        ];
    }
}
