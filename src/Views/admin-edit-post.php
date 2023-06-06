<?php

namespace RichardMuvirimi\WooCustomGateway\Views;

use RichardMuvirimi\WooCustomGateway\Helpers\Functions;
use WP_Post;

if (!defined('WPINC')) {
    die(); // Exit if accessed directly.
}

/**
 * Provide a admin area view for the plugin
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Views
 *
 * @link https://richard.co.zw
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.2.3
 * @version 1.2.3
 *
 * @var WP_Post $post
 */

wp_nonce_field(Functions::get_plugin_slug(), Functions::get_plugin_slug( '-nonce'), false);

$description = get_post_meta($post->ID, 'woocg-desciption', true); // ignore typo

?>

<div class="<?php esc_attr_e(Functions::get_plugin_slug()); ?>">
    <div style="margin: 10px 0;">
        <label for="woo-cg-description"><?php esc_html_e('Gateway Description', Functions::get_plugin_slug()); ?></label>
    </div>
    <textarea rows="5" style="width: 100%;" name="woo-cg-description"
              placeholder="<?php esc_html_e('Gateway description', Functions::get_plugin_slug()); ?>"
              id="woo-cg-description"><?php esc_html_e($description); ?></textarea>
    <small><?php _e('Description for the payment method shown on the admin page.', Functions::get_plugin_slug()); ?></small>
</div>
