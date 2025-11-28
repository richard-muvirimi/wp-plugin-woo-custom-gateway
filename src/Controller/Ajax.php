<?php

/**
 * File for the Ajax controller
 *
 * All ajax functionality to be handled in one place
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Controller
 *
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.0.0
 * @version 1.0.0
 */

namespace RichardMuvirimi\WooCustomGateway\Controller;

use RichardMuvirimi\WooCustomGateway\Helpers\Functions;
use RichardMuvirimi\WooCustomGateway\Helpers\Logger;

/**
 * Ajax side controller
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Controller
 *
 * @author Richard <erichard@tyganeutronics.com>
 * @since 1.0.0
 * @version 1.0.0
 */
class Ajax extends BaseController
{

    /**
     * Set reminder for half a year and send redirect link
     *
     * @return void
     * @since 1.0.2
     *
     */
    public function ajaxDoRate(): void
    {

        if (check_ajax_referer(Functions::get_plugin_slug('-rate-enable'), '_ajax_nonce', false) !== false) {

            // remind again in three months
            set_transient(Functions::get_plugin_slug('-rate'), true, YEAR_IN_SECONDS / 2);

            Logger::logEvent("rate_plugin_accepted");

            wp_send_json(
                array(
                    'redirect' => sprintf('https://wordpress.org/support/plugin/%s/reviews/', Functions::get_plugin_slug()),
                ),
                200
            );
        }
    }

    /**
     * Remind again in a week
     *
     * @return void
     * @since 1.0.2
     *
     */
    public function ajaxDoRemindRate(): void
    {

        if (check_ajax_referer(Functions::get_plugin_slug('-rate-remind'), '_ajax_nonce', false) !== false) {

            // remind after a week
            set_transient(Functions::get_plugin_slug('-rate'), true, WEEK_IN_SECONDS);

            Logger::logEvent("rate_plugin_remind");

            wp_send_json(
                array(
                    'success' => true,
                ),
                200
            );
        }
    }

    /**
     * Remind in a year
     *
     * @return void
     * @since 1.0.2
     *
     */
    public function ajaxDoCancelRate(): void
    {
        if (check_ajax_referer(Functions::get_plugin_slug('-rate-cancel'), '_ajax_nonce', false) !== false) {

            set_transient(Functions::get_plugin_slug('-rate'), true, YEAR_IN_SECONDS);

            Logger::logEvent("rate_plugin_declined");

            wp_send_json(
                array(
                    'success' => true,
                ),
                200
            );
        }
    }

    /**
     * Set reminder for half a year and send redirect link
     *
     * @return void
     * @since 1.0.2
     *
     */
    public function ajaxDoAnalytics(): void
    {

        if (check_ajax_referer(Functions::get_plugin_slug('-analytics-enable'), '_ajax_nonce', false) !== false) {

            // remind again in three months
            set_transient(Functions::get_plugin_slug('-analytics'), true, YEAR_IN_SECONDS / 2);

            Logger::logEvent("analytics_plugin_accepted");

            wp_send_json(
                array(
                    'redirect' => add_query_arg(["page" => Functions::get_plugin_slug("-about")], admin_url("admin.php")),
                ),
                200
            );
        }
    }

    /**
     * Remind again in a week
     *
     * @return void
     * @since 1.0.2
     *
     */
    public function ajaxDoRemindAnalytics(): void
    {

        if (check_ajax_referer(Functions::get_plugin_slug('-analytics-remind'), '_ajax_nonce', false) !== false) {

            // remind after a week
            set_transient(Functions::get_plugin_slug('-analytics'), true, WEEK_IN_SECONDS);

            Logger::logEvent("analytics_plugin_remind");

            wp_send_json(
                array(
                    'success' => true,
                ),
                200
            );
        }
    }

    /**
     * Remind in a year
     *
     * @return void
     * @since 1.0.2
     *
     */
    public function ajaxDoCancelAnalytics(): void
    {
        if (check_ajax_referer(Functions::get_plugin_slug('-analytics-cancel'), '_ajax_nonce', false) !== false) {

            set_transient(Functions::get_plugin_slug('-analytics'), true, YEAR_IN_SECONDS);

            Logger::logEvent("analytics_plugin_declined");

            wp_send_json(
                array(
                    'success' => true,
                ),
                200
            );
        }
    }

}
