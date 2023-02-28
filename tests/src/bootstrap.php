<?php
/**
 * Phpunit bootstrap file for running tests
 *
 * phpcs:disable WordPress.VIP.RestrictedFunctions.file_get_contents_file_get_contents
 * phpcs:disable WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
 * phpcs:disable WordPress.WP.AlternativeFunctions.file_system_read_file_get_contents
 */

$root = __DIR__;

do {
    $root = dirname($root);

    // break if we don't find the file
    if (strrpos($root, DIRECTORY_SEPARATOR, intval(strpos($root, PATH_SEPARATOR))) < 3) {
        define('WOO_CUSTOM_GATEWAY_SLUG', 'woo-custom-gateway');
        break;
    }
} while (!file_exists($root . '/woo-custom-gateway.php'));

/**
 * WP loaded check constant
 */
const WPINC = 'wp-includes';

/**
 * Reference to this file
 */
const WOO_CUSTOM_GATEWAY_FILE = __FILE__;

/**
 * Method stubs
 */
if (!function_exists('register_activation_hook')) {
    /**
     * Register activation hook stub
     *
     * @param string $file
     * @param callable $callable
     * @return void
     */
    function register_activation_hook(string $file, callable $callable): void
    {
    }
}
if (!function_exists('register_deactivation_hook')) {
    /**
     * Register deactivation hook stub
     *
     * @param string $file
     * @param callable $callable
     * @return void
     */
    function register_deactivation_hook(string $file, callable $callable): void
    {
    }
}
if (!function_exists('register_uninstall_hook')) {
    /**
     * Register uninstall hook stub
     *
     * @param string $file
     * @param callable $callable
     * @return void
     */
    function register_uninstall_hook(string $file, callable $callable): void
    {
    }
}

/**
 * Load constants, no need for the actual value
 */
$content = file_get_contents($root . '/woo-custom-gateway.php');

preg_match_all('/define\("(.*?)",\s?"?(.*?)"?\);/m', $content, $matches);

$matches = array_combine($matches[1], $matches[2]);

foreach ($matches as $constant => $value) {
    if (!defined($constant)) {
        define($constant, $value);
    }
}
