<?php
/**
 * Translations loader for Custom Payment Gateways for WooCommerce
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Locale
 *
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.0.0
 * @version 1.0.0
 */

namespace RichardMuvirimi\WooCustomGateway\Locale;

use RichardMuvirimi\WooCustomGateway\Helpers\Functions;

/**
 * Class to handle plugin translations
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Locale
 *
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.0.0
 * @version 1.0.0
 */
class I18n
{


    /**
     * Load the plugin translation files
     *
     * @return void
     * @version 1.0.0
     * @since 1.0.0
     *
     * @author Richard Muvirimi <richard@tyganeutronics.com>
     */
    public function load_plugin_textdomain(): void
    {
        load_plugin_textdomain(Functions::get_plugin_slug(), false, plugin_dir_path(WOO_CUSTOM_GATEWAY_FILE) . 'languages');
    }
}
