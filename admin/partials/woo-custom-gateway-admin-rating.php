<?php

if (!defined('WPINC')) {
    die(); // Exit if accessed directly.
}

/**
 * Provide a admin area view for the plugin
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 *
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/admin/partials
 *
 * @link https://tyganeutronics.com
 * @since 1.0.0
 */; ?>

<div class="notice notice-info is-dismissible">
    <div style="margin: 20px 5px;">
        <span
            class="float: left;"><?php printf(__('Please consider rating %s as it will encourage others to install it too.', 'woo-custom-gateway'), __("Woo Custom Gateway", 'woo-custom-gateway')); ?></span>
        <a href="https://wordpress.org/support/plugin/woo-custom-gateway/reviews/" target="_blank"
            style="float: right;">
            <span><?php _e('Rate', 'woo-custom-gateway'); ?></span>
            <div class="wporg-ratings" style="color:#ffb900; display:inline-block;">
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
            </div>
        </a>
    </div>
</div>