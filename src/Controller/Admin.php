<?php
/**
 * File for the Admin controller
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
use RichardMuvirimi\WooCustomGateway\Helpers\Template;
use RichardMuvirimi\WooCustomGateway\Model\Gateway;
use WP_Post;

/**
 * Admin side controller
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Controller
 *
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.0.0
 * @version 1.0.1
 */
class Admin extends BaseController
{

    /**
     * Register gateways in Woocommerce
     *
     * @param array $gateways
     * @return array
     * @since 1.0.0
     */
    public function payment_gateways(array $gateways): array
    {

        $args = array(
            'post_type' => Functions::gateway_slug(),
            'fields' => 'ids',
            'no_found_rows' => true,
            'post_status' => 'publish',
            'numberposts' => -1,
        );

        $posts = get_posts($args);

        foreach ($posts as $id) {

            $gateways[] = new Gateway($id);
        }

        return $gateways;
    }

    /**
     * Register plugin options
     *
     * @return void
     * @author Richard Muvirimi <richard@tyganeutronics.com>
     * @since 1.5.0
     * @version 1.5.0
     */
    public function registerOptions(): void
    {

        register_setting(
            Functions::get_plugin_slug("-about"),
            Functions::get_plugin_slug("-analytics"),
            array("sanitize_callback" => "sanitize_text_field")
        );

        add_settings_section(
            Functions::get_plugin_slug("-settings"),
            __("Settings", Functions::get_plugin_slug()),
            array($this, "renderSectionHeader"),
            Functions::get_plugin_slug("-about")
        );

        add_settings_field(
            Functions::get_plugin_slug("-analytics"),
            __('Collect Anonymous Usage Data', Functions::get_plugin_slug()),
            array($this, 'renderInputField'),
            Functions::get_plugin_slug("-about"),
            Functions::get_plugin_slug("-settings"),
            array(
                'label_for' => Functions::get_plugin_slug("-analytics"),
                'class' => Functions::get_plugin_slug( '-row'),
                "value" => get_option(Functions::get_plugin_slug("-analytics"), "off"),
                'description' => Template::get_template(Functions::get_plugin_slug("-about-analytics-disclaimer"), [], "about-analytics-disclaimer.php"),
                "type" => "checkbox",
            )
        );
    }

    /**
     * Display the settings header
     *
     * @return void
     * @since 1.5.0
     * @version 1.5.0
     *
     * @author Richard Muvirimi <richard@tyganeutronics.com>
     */
    public function renderSectionHeader(): void
    {
        echo Template::get_template(Functions::get_plugin_slug("-about-section-header"), [], "about-section-header.php");
    }

    /**
     * Display input field
     *
     * @param array $args
     *
     * @return void
     * @since 1.0.0
     * @version 1.0.0
     *
     * @author Richard Muvirimi <richard@tyganeutronics.com>
     */
    public function renderInputField(array $args): void
    {
        echo Template::get_template(Functions::get_plugin_slug("-about-input-field"), $args, "about-input-field.php");
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @return void
     * @since 1.0.0
     */
    public function enqueue_styles(): void
    {

        wp_register_style(Functions::get_plugin_slug(), Template::get_style_url('admin-rating.css'), array(), WOO_CUSTOM_GATEWAY_VERSION);

        wp_register_style(Functions::get_plugin_slug("-about"), Template::get_style_url('admin-about.css'), array(), WOO_CUSTOM_GATEWAY_VERSION);
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @return void
     * @since 1.0.0
     */
    public function enqueue_scripts(): void
    {

        wp_register_script(Functions::get_plugin_slug(), Template::get_script_url('admin-rating.js'), array('jquery'), WOO_CUSTOM_GATEWAY_VERSION);
        wp_localize_script(
            Functions::get_plugin_slug(),
            'wooCustomGateway',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'name' => Functions::get_plugin_slug(),
            )
        );
    }

    /**
     * On create the about menu
     *
     * @since 1.0.0
     */
    public function on_admin_menu()
    {
        add_menu_page(
            __('Woo Custom Gateway', Functions::get_plugin_slug()),
            __('Woo Custom Gateway', Functions::get_plugin_slug()),
            'manage_options',
            Functions::get_plugin_slug(),
            "",
            Template::get_file_base64(Template::get_image_path('logo.svg'), "data:image/svg+xml;base64,"),
            59 // Right below WooCommerce
        );

        add_submenu_page(
            Functions::get_plugin_slug(),
            __('About', Functions::get_plugin_slug()),
            __('About', Functions::get_plugin_slug()),
            'manage_options',
            Functions::get_plugin_slug("-about"),
            [$this, 'renderAboutPage'],
        );
    }

    /**
     * Render the about page
     *
     * @return void
     * @since 1.5.0
     * @version 1.5.0
     *
     * @author Richard Muvirimi <richard@tyganeutronics.com>
     */
    public function renderAboutPage(): void
    {

        Logger::logEvent("view_about_page");

        wp_enqueue_style(Functions::get_plugin_slug("-about"));

        $plugin = get_plugin_data(
            WOO_CUSTOM_GATEWAY_FILE
        );

        echo Template::get_template(Functions::get_plugin_slug("admin-about"), compact("plugin"), "admin-about.php");
    }

    /**
     * Add settings page link
     *
     * @param array $links
     * @return array
     * @since 1.0.0
     *
     * @author Richard Muvirimi <richard@tyganeutronics.com>
     */
    public function plugins_list_options_link(array $links): array
    {

        $args = array('post_type' => Functions::gateway_slug());

        $link = add_query_arg($args, admin_url('edit.php'));
        $link = sprintf('<a href="%s">%s</a>', $link, __('Payment Methods', Functions::get_plugin_slug()));

        $links[] = $link;

        return $links;
    }

    /**
     * Register custom post typre
     *
     * @return void
     * @since 1.0.0
     */
    public function init(): void
    {

        register_post_type(
            Functions::gateway_slug(),
            array(
                'show_ui' => true,
                'show_in_menu' => current_user_can('manage_woocommerce') ? Functions::get_plugin_slug() : true,
                'description' => Functions::gateway_slug(),
                // 'has_archive' => true,
                'exclude_from_search' => true,
                // 'map_meta_cap' => true,
                'hierarchical' => false,
                'labels' => array(
                    'name' => __('Payment Methods', Functions::get_plugin_slug()),
                    'singular_name' => __('Payment Method', Functions::get_plugin_slug()),
                    'add_new' => __('Add New', Functions::get_plugin_slug()),
                    'add_new_item' => __('Add New Payment Method', Functions::get_plugin_slug()),
                    'edit_item' => __('Edit Payment Method', Functions::get_plugin_slug()),
                    'new_item' => __('New Payment Method', Functions::get_plugin_slug()),
                    'view_item' => __('View Payment Method', Functions::get_plugin_slug()),
                    'search_items' => __('Search Payment Methods', Functions::get_plugin_slug()),
                    'not_found' => __('No Payment Methods Found', Functions::get_plugin_slug()),
                    'not_found_in_trash' => __('No Payment Methods found in trash', Functions::get_plugin_slug()),
                    'parent_item_colon' => __('Parent Payment Method:', Functions::get_plugin_slug()),
                    'all_items' => __('Payment Methods', Functions::get_plugin_slug()),
                    'archives' => __('Payment Method archives', Functions::get_plugin_slug()),
                    'insert_into_item' => __('Insert into Payment Method profile', Functions::get_plugin_slug()),
                    'uploaded_to_this_item' => __('Uploaded to Payment Method profile', Functions::get_plugin_slug()),
                    'menu_name' => __('Payment Methods', Functions::get_plugin_slug()),
                    'name_admin_bar' => __('Payment Methods', Functions::get_plugin_slug()),
                ),
                'rewrite' => array(
                    'slug' => 'custom-gateway',
                    Functions::get_plugin_slug(),
                ),
                'supports' => array('thumbnail', 'title', Functions::get_plugin_slug()),
                'delete_with_user' => false,
                'register_meta_box_cb' => array($this, 'addMetaBoxes'),
            )
        );
    }

    /**
     * Save Post
     *
     * @param int $post_id
     * @param WP_Post $post
     * @param boolean $update
     * @version 1.2.3
     * @since 1.0.0
     */
    public function save_post(int $post_id, WP_Post $post, bool $update): void
    {

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        switch (get_post_type($post_id)) {
            case Functions::gateway_slug():
                $nonce = filter_input(INPUT_POST, Functions::get_plugin_slug('-nonce'));
                if ($nonce && wp_verify_nonce($nonce, Functions::get_plugin_slug())) {

                    if (isset($_POST['woo-cg-description'])) {
                        $description = sanitize_text_field(filter_input(INPUT_POST, 'woo-cg-description'));

                        if ($description) {
                            update_post_meta($post_id, 'woocg-desciption', $description); // ignore typo
                        }

                        Logger::logEvent("save_payment_gateway");
                    }
                }
                break;
        }
    }

    /**
     * Show rating request
     *
     * @return void
     * @version 1.5.0
     * @since 1.1.0
     *
     * @author Richard Muvirimi <richard@tyganeutronics.com>
     */
    public function showAdminNotices(): void
    {
        /**
         * Request Rating
         */
        // if it has at least two custom gateways and has never been shown
        $args = array(
            'post_type' => Functions::gateway_slug(),
            'fields' => 'ids',
            'no_found_rows' => true,
        );

        if (boolval(get_transient(Functions::get_plugin_slug('-rate'))) === false && count(get_posts($args)) >= 2) {
            wp_enqueue_script(Functions::get_plugin_slug());
            wp_enqueue_style(Functions::get_plugin_slug());

            echo Template::get_template(Functions::get_plugin_slug( '-admin-notice-rating'), array(), 'admin-notice-rating.php');

            Logger::logEvent("request_plugin_rating");
        }

        if (get_option(Functions::get_plugin_slug("-analytics"), "off") !== "on" && boolval(get_transient(Functions::get_plugin_slug('-analytics'))) === false) {
            wp_enqueue_script(Functions::get_plugin_slug());
            wp_enqueue_style(Functions::get_plugin_slug());

            echo Template::get_template(Functions::get_plugin_slug( '-admin-notice-analytics'), array(), 'admin-notice-analytics.php');

            Logger::logEvent("request_plugin_analytics");
        }
    }

    /**
     *
     * @param WP_Post|int $post
     * @since 1.0.0
     */
    public function addMetaBoxes($post): void
    {

        add_meta_box('woocg-post-description', __('Payment Method Description', Functions::get_plugin_slug()), array($this, 'descriptionMetaBox'), Functions::gateway_slug(), 'normal', 'high');
    }

    /**
     *
     * @param WP_Post|int $post
     * @param array $args
     * @since 1.0.0
     * @version 1.2.3
     */
    public function descriptionMetaBox($post, array $args): void
    {
        echo Template::get_template(Functions::get_plugin_slug( '-admin-edit-post'), compact('post', 'args'), 'admin-edit-post.php');

        Logger::logEvent("edit_payment_gateway");
    }

    /**
     *
     * @param string $content
     * @param WP_Post|int $post
     * @param int|string $thumbnail_id
     * @return string
     * @since 1.0.0
     * @version 1.3.0
     */
    public function filter_featured_image_admin_text(string $content, $post, $thumbnail_id): string
    {

        if (get_post_type($post) === Functions::gateway_slug() && $thumbnail_id == null) {
            $content .= wpautop(__('If you want to show an image next to the gateway\'s name on the frontend, select an image.', Functions::get_plugin_slug()));
        }

        return $content;
    }

    /**
     * Change enter title here text
     *
     * @param string $input
     * @param WP_Post|int $post
     * @return string
     * @version 1.0.0
     * @since 1.0.0
     */
    public function custom_enter_title(string $input, $post): string
    {

        if (Functions::gateway_slug() === get_post_type($post)) {
            $input = __('Payment Method Name', Functions::get_plugin_slug());
        }

        return $input;
    }

    /**
     * Output the column data
     *
     * @param string $column
     * @param WP_Post|int $post
     * @since 1.0.0
     * @version 1.3.0
     */
    public function add_column_data(string $column, $post): void
    {

        if (Functions::gateway_slug() === get_post_type($post)) {
            switch ($column) {
                case 'thumbnail':
                    the_post_thumbnail('thumb');
                    break;
            }
        }
    }

    /**
     * Add custom post thumbnail column
     *
     * @param array $columns
     * @return array
     * @since 1.0.0
     * @version 1.0.0
     */
    public function add_columns(array $columns): array
    {

        $columns['thumbnail'] = __('Thumbnail', Functions::get_plugin_slug());

        return $columns;
    }

    /**
     *
     * Add quick link to the plugin settings on the plugins page
     *
     * @param array $actions
     * @param WP_Post|int $post
     * @return array
     * @version 1.0.0
     * @since 1.0.0
     */
    public function post_row_actions(array $actions, $post): array
    {

        if (get_post_type($post) === Functions::gateway_slug()) {

            $args = array(
                'page' => 'wc-settings',
                'tab' => 'checkout',
                'section' => Functions::gateway_id($post->ID),
            );

            $link = add_query_arg($args, admin_url('admin.php'));

            $actions['settings'] = sprintf('<a href="%s">%s</a>', $link, __('Settings', Functions::get_plugin_slug()));
        }

        return $actions;
    }

    /**
     * On delete custom post type
     *
     * @param WP_Post|int $post
     * @version 1.3.0
     * @since 1.0.0
     */
    public function on_delete_method($post): void
    {

        if (get_post_type($post) === Functions::gateway_slug()) {
            $method = new Gateway($post);
            delete_option($method->get_option_key());

            Logger::logEvent("delete_payment_gateway");
        }
    }

}
