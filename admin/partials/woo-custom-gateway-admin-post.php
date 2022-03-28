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
 * @since 1.2.3
 * @version 1.2.3
 */

wp_nonce_field($this->plugin_name, $this->plugin_name . "-nonce", false);

$description = get_post_meta($post->ID, 'woocg-desciption', true); //ignore typo

?>

<div class="<?php esc_attr_e($this->plugin_name) ?>">
    <div style="margin: 10px 0;">
        <label for="woocg-description"><?php _e("Gateway Description", $this->plugin_name) ?></label>
    </div>
    <textarea rows="5" style="width: 100%;" name="woocg-description"
        placeholder="<?php _e("Gateway description", $this->plugin_name) ?>"
        id="woocg-description"><?php esc_html_e($description) ?></textarea>
    <small><?php _e('Description for the payment method shown on the admin page.', $this->plugin_name) ?></small>
</div>