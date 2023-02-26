<?php
/**
 * File for the plugin Specific functions
 *
 * All plugin specific functions are handled in one place
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Controller
 *
 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Rich4rdMuvirimi\WooCustomGateway\Controller;

use Rich4rdMuvirimi\WooCustomGateway\Helpers\Functions;
use Rich4rdMuvirimi\WooCustomGateway\Helpers\Logger;

/**
 * Plugin controller
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Controller
 *
 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
 * @since 1.0.0
 * @version 1.0.0
 */
class Plugin extends BaseController
{


    /**
     * On plugin activation
     *
     * @return void
     * @version 1.0.0
     * @since 1.0.0
     */
    public static function on_activation(): void
    {

        // insert sample posts
        $args = array(
            'post_type' => Functions::gateway_slug(),
            'fields' => 'ids',
            'no_found_rows' => true,
        );

        if (empty(get_posts($args))) {

            $id = wp_insert_post(
                array(
                    'post_status' => 'publish',
                    'post_type' => Functions::gateway_slug(),
                    'post_title' => __('Sample Custom Gateway', WOO_CUSTOM_GATEWAY_SLUG),
                    'meta_input' => array(
                        'woocg-desciption' => __('Sample payment gateway to just show off. ;)', WOO_CUSTOM_GATEWAY_SLUG), // ignore typo
                    ),
                )
            );
        }

        if (boolval(get_transient(Functions::get_plugin_slug('-rate'))) === false) {
            set_transient(Functions::get_plugin_slug('-rate'), true, YEAR_IN_SECONDS / 4);
        }

        Logger::logEvent("activate_plugin");

    }

    /**
     * On plugin deactivation
     *
     * @return void
     * @version 1.0.0
     * @since 1.0.0
     */
    public static function on_deactivation(): void
    {
        Logger::logEvent("deactivate_plugin");
    }

    /**
     * On plugin uninstall
     *
     * @return void
     * @version 1.0.0
     * @since 1.0.0
     */
    public static function on_uninstall(): void
    {

        $args = array(
            'post_type' => Functions::gateway_slug(),
            'fields' => 'ids',
            'no_found_rows' => true,
            'numberposts' => -1,
        );

        $posts = get_posts($args);

        foreach ($posts as $id) {

            wp_delete_post($id, true);
        }

        Logger::logEvent("uninstall_plugin");

    }
}
