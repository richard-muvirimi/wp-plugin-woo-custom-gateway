<?php

if (!defined('WPINC')) {
    die(); // Exit if accessed directly.
}

/**
 * Provide a public-facing view for the plugin
 * This file is used to markup the public-facing aspects of the plugin.
 *
 *
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/public/partials
 *
 * @link https://tyganeutronics.com
 * @since 1.0.0
 */ ?>

<fieldset>
    <p class="form-row form-row-wide">
        <label
            for="<?php esc_attr_e(WOO_CUSTOM_GATEWAY_SLUG) ?>-note"><?php echo esc_html($this->description) ?: __("Please provide proof of payment.", WOO_CUSTOM_GATEWAY_SLUG) ?>
            <span class="required">*</span></label>

        <textarea id="<?php esc_attr_e(WOO_CUSTOM_GATEWAY_SLUG) ?>-note" class="input-text"
            name="<?php esc_attr_e(WOO_CUSTOM_GATEWAY_SLUG) ?>-note"></textarea>

    </p>
    <div class="clear"></div>
</fieldset>