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
class I18n {


	/**
	 * Load the plugin translation files
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( WOO_CUSTOM_GATEWAY_SLUG, false, plugin_dir_path( WOO_CUSTOM_GATEWAY_FILE ) . 'languages' );
	}
}