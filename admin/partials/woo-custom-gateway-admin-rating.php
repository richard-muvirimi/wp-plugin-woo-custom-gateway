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

<div class="notice notice-info is-dismissible">
    <div style="margin: 20px 5px;">
        <span
            class="float: left;"><?php printf(__('Please consider rating %s as it will encourage others to install it too.', $this->plugin_name), __("Woo Custom Gateway", $this->plugin_name)); ?></span>
        <span style="float: right;">
            <a class="button"
                href="<?php echo $this->url_targetting_self(array($this->plugin_name . "-target" => "rate")) ?>"
                target="_blank">
                <?php _e('Rate ', $this->plugin_name); ?>
                <span style="color:#ffb900;">&starf;&starf;&starf;&starf;&starf;</span>
            </a>
            <a class="button"
                href="<?php echo $this->url_targetting_self(array($this->plugin_name . "-target" => "later")) ?>">
                <span><?php _e('Remind me later', $this->plugin_name); ?></span>
            </a>
            <a class="button"
                href="<?php echo $this->url_targetting_self(array($this->plugin_name . "-target" => "never")) ?>">
                <span><?php _e('Never', $this->plugin_name); ?></span>
            </a>
        </span>
    </div>
</div>