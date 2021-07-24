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
 * @since 1.1.0
 * @version 1.1.1
 */; ?>

<div class="<?php esc_attr_e($this->plugin_name) ?> notice notice-info is-dismissible">
    <div>
        <div class="<?php esc_attr_e($this->plugin_name) ?>-prompt">
            <?php printf(__('Please consider rating %s as it will encourage others to install it too.', $this->plugin_name), __("Woo Custom Gateway", $this->plugin_name)); ?>
        </div>
        <div class="<?php esc_attr_e($this->plugin_name) ?>-button">
            <a class="button btn-rate" href="#" data-nonce="<?php esc_attr_e(wp_create_nonce("wcg-rate")) ?>"
                data-action="<?php esc_attr_e("rate") ?>">
                <?php _e('Rate ', $this->plugin_name); ?>
                <span style="color:#ffb900;">&starf;&starf;&starf;&starf;&starf;</span>
            </a>
            <a class="button btn-remind" href="#" data-nonce="<?php esc_attr_e(wp_create_nonce("wcg-remind")) ?>"
                data-action="<?php esc_attr_e("remind") ?>">
                <span><?php _e('Remind me later', $this->plugin_name); ?></span>
            </a>
            <a class="button btn-cancel" href="#" data-nonce="<?php esc_attr_e(wp_create_nonce("wcg-cancel")) ?>"
                data-action="<?php esc_attr_e("cancel") ?>">
                <span><?php _e('Never', $this->plugin_name); ?></span>
            </a>
        </div>
    </div>
</div>