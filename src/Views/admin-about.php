<?php

namespace RichardMuvirimi\WooCustomGateway\Views;

use RichardMuvirimi\WooCustomGateway\Helpers\Functions;
use RichardMuvirimi\WooCustomGateway\Helpers\Template;

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
 * @var array $plugin
 */

?>

<div class="<?php echo Functions::get_plugin_slug() ?>">
    <div>
        <div class="center">
            <img width="128" src="<?php echo Template::get_image_url("logo.svg") ?>"
                 alt="<?php echo $plugin['Name']; ?>">
        </div>
        <h1 class="center">
            <a href="<?php echo $plugin['PluginURI']; ?>">
                <?php echo $plugin['Name']; ?>
            </a>
        </h1>
        <h5 class="center">
            <?php echo $plugin['Version']; ?>
        </h5>
        <h4 class="center">
            <?php echo $plugin['Description']; ?>
        </h4>

    </div>

    <div class="wrap">

        <div>
            <small><?php _e("Sponsored", Functions::get_plugin_slug()) ?></small>
        </div>

        <div style="margin: 15px 0;">
            <?php echo Template::get_template(Functions::get_plugin_slug("-about-ad"), [], "about-ad.php") ?>

            <?php echo Template::get_template(Functions::get_plugin_slug("-about-ad"), [], "about-ad.php") ?>

            <?php echo Template::get_template(Functions::get_plugin_slug("-about-ad"), [], "about-ad.php") ?>
        </div>

        <div>
            <?php echo Template::get_template(Functions::get_plugin_slug("-about-ads-disclaimer"), [], "about-ads-disclaimer.php") ?>
        </div>

    </div>

    <div class="wrap">

        <?php settings_errors(); ?>

        <form action="<?php echo admin_url("options.php") ?>" method="post">
            <?php

            settings_fields(Functions::get_plugin_slug("-about"));
            do_settings_sections(Functions::get_plugin_slug("-about"));

            submit_button(__('Save', Functions::get_plugin_slug()));
            ?>
        </form>
    </div>

</div>
