<?php

namespace RichardMuvirimi\WooCustomGateway\Views;

use RichardMuvirimi\WooCustomGateway\Helpers\Functions;

if (!defined('WPINC')) {
    die(); // Exit if accessed directly.
}

/**
 * Provide payment proof field
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Views
 *
 * @link https://richard.co.zw
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.0.0
 * @version 1.0.0
 *
 * @var string $description
 * @var string $id
 */
?>

<fieldset>
    <p class="form-row form-row-wide">
        <label for="<?php esc_attr_e(Functions::get_plugin_slug("-note-" . $id)); ?>">
            <?php if (empty($description)) : _e('Payment Details.', Functions::get_plugin_slug()); endif; ?>
        </label>

        <textarea id="<?php esc_attr_e(Functions::get_plugin_slug("-note-" . $id)); ?>" class="input-text"
                  name="<?php esc_attr_e(Functions::get_plugin_slug("-note-" . $id)); ?>">
        </textarea>

    </p>
    <div class="clear"></div>
</fieldset>
