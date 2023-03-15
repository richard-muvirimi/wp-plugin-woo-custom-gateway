<?php
/**
 * Translations loader for Woo Custom Gateway
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Locale
 *
 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Rich4rdMuvirimi\WooCustomGateway\Locale;

use Rich4rdMuvirimi\WooCustomGateway\Helpers\Functions;

/**
 * Class to handle plugin translations
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Locale
 *
 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
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
     * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
     */
    public function load_plugin_textdomain(): void
    {
        load_plugin_textdomain(Functions::get_plugin_slug(), false, plugin_dir_path(WOO_CUSTOM_GATEWAY_FILE) . 'languages');
    }
}
