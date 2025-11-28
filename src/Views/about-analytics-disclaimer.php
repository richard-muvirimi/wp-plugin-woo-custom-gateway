<?php

namespace RichardMuvirimi\WooCustomGateway\Views;

use RichardMuvirimi\WooCustomGateway\Helpers\Functions;

if (!defined('WPINC')) {
    die(); // Exit if accessed directly.
}

/**
 * Provide an about area view for the plugin
 * This file is used to mark up the admin-facing aspects of the plugin.
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Views
 *
 * @link https://richard.co.zw
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.5.0
 * @version 1.5.0
 *
 */

?>
<span>
    <?php _e("Collecting usage data helps us understand how our users interact with our products and services, and enables us to improve and enhance our offerings to better meet their needs. This information is collected anonymously and is used solely for the purposes of product development and improvement. To learn more about our data collection practices, please review our", WOO_CUSTOM_GATEWAY_SLUG) ?>
    <a href="https://site.tyganeutronics.com/privacy-policy">
       <?php _e("privacy policy", Functions::get_plugin_slug()) ?>
    </a>.
</span>
