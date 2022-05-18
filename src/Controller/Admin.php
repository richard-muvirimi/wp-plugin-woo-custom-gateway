<?php
/**
 * File for the Admin controller
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Controller
 *
 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Rich4rdMuvirimi\WooCustomGateway\Controller;

use Rich4rdMuvirimi\WooCustomGateway\Helpers\Functions;
use Rich4rdMuvirimi\WooCustomGateway\Model\Gateway;
use Rich4rdMuvirimi\WooCustomGateway\WooCustomGateway;

/**
 * Admin side controller
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Controller
 *
 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
 * @since 1.0.0
 * @version 1.0.1
 */
class Admin extends BaseController {

	/**
	 * Register gateways in Woocommerce
	 *
	 * @since 1.0.0
	 * @param array $gateways
	 * @return void
	 */
	public function payment_gateways( $gateways ) {

		$args = array(
			'post_type'     => Functions::gateway_slug(),
			'fields'        => 'ids',
			'no_found_rows' => true,
			'post_status'   => 'publish',
			'numberposts'   => -1,
		);

		$posts = get_posts( $args );

		foreach ( $posts as $id ) {

			array_push( $gateways,  new Gateway( $id ));
		}

		return $gateways;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		wp_register_style( WOO_CUSTOM_GATEWAY_SLUG, Functions::get_style_path( 'admin-rating.css' ), array(), WOO_CUSTOM_GATEWAY_VERSION, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script( WOO_CUSTOM_GATEWAY_SLUG, Functions::get_script_path( 'admin-rating.js' ), array( 'jquery' ), WOO_CUSTOM_GATEWAY_VERSION, false );
		wp_localize_script(
			WOO_CUSTOM_GATEWAY_SLUG,
			'wcg',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'name'     => WOO_CUSTOM_GATEWAY_SLUG,
			)
		);
	}

	/**
	 * Add setings page link
	 *
	 * @since 1.0.0
	 * @param array $links
	 * @return array
	 */
	public function plugins_list_options_link( $links ) {

		$args = array( 'post_type' => Functions::gateway_slug() );

		$link = add_query_arg( $args, admin_url( 'edit.php' ) );
		$link = sprintf( '<a href="%s">%s</a>', $link, __( 'Payment Methods', WOO_CUSTOM_GATEWAY_SLUG ) );

		array_push( $links, $link );

		return $links;
	}

	/**
	 * Register custom post typre
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {

		register_post_type(
			Functions::gateway_slug(),
			array(
				'show_ui'              => true,
				'show_in_menu'         => current_user_can( 'manage_woocommerce' ) ? 'woocommerce' : true,
				'description'          => Functions::gateway_slug(),
				// 'has_archive' => true,
				'exclude_from_search'  => true,
				// 'map_meta_cap' => true,
				'hierarchical'         => false,
				'labels'               => array(
					'name'                  => __( 'Payment Methods', WOO_CUSTOM_GATEWAY_SLUG ),
					'singular_name'         => __( 'Payment Method', WOO_CUSTOM_GATEWAY_SLUG ),
					'add_new'               => __( 'Add New', WOO_CUSTOM_GATEWAY_SLUG ),
					'add_new_item'          => __( 'Add New Payment Method', WOO_CUSTOM_GATEWAY_SLUG ),
					'edit_item'             => __( 'Edit Payment Method', WOO_CUSTOM_GATEWAY_SLUG ),
					'new_item'              => __( 'New Payment Method', WOO_CUSTOM_GATEWAY_SLUG ),
					'view_item'             => __( 'View Payment Method', WOO_CUSTOM_GATEWAY_SLUG ),
					'search_items'          => __( 'Search Payment Methods', WOO_CUSTOM_GATEWAY_SLUG ),
					'not_found'             => __( 'No Payment Methods Found', WOO_CUSTOM_GATEWAY_SLUG ),
					'not_found_in_trash'    => __( 'No Payment Methods found in trash', WOO_CUSTOM_GATEWAY_SLUG ),
					'parent_item_colon'     => __( 'Parent Payment Method:', WOO_CUSTOM_GATEWAY_SLUG ),
					'all_items'             => __( 'Payment Methods', WOO_CUSTOM_GATEWAY_SLUG ),
					'archives'              => __( 'Payment Method archives', WOO_CUSTOM_GATEWAY_SLUG ),
					'insert_into_item'      => __( 'Insert into Payment Method profile', WOO_CUSTOM_GATEWAY_SLUG ),
					'uploaded_to_this_item' => __( 'Uploaded to Payment Method profile', WOO_CUSTOM_GATEWAY_SLUG ),
					'menu_name'             => __( 'Payment Methods', WOO_CUSTOM_GATEWAY_SLUG ),
					'name_admin_bar'        => __( 'Payment Methods', WOO_CUSTOM_GATEWAY_SLUG ),
				),
				'rewrite'              => array(
					'slug' => 'custom-gateway',
					WOO_CUSTOM_GATEWAY_SLUG,
				),
				'supports'             => array( 'thumbnail', 'title', WOO_CUSTOM_GATEWAY_SLUG ),
				'delete_with_user'     => false,
				'register_meta_box_cb' => array( $this, 'addMetaBoxs' ),
			)
		);
	}

	/**
	 *
	 * @since 1.0.0
	 * @version 1.2.3
	 * @param int      $post_id
	 * @param \WP_Post $post
	 * @param boolean  $update
	 */
	public function save_post( $post_id, $post, $update ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		switch ( get_post_type( $post_id ) ) {
			case Functions::gateway_slug():
				$nonce = filter_input( INPUT_POST, Functions::get_plugin_slug( '-nonce' ) );
				if ( $nonce && wp_verify_nonce( $nonce, WOO_CUSTOM_GATEWAY_SLUG ) ) {

					if ( isset( $_POST['woocg-description'] ) ) {
						$description = sanitize_text_field( filter_input( INPUT_POST, 'woocg-description' ) );

						if ( $description ) {
							update_post_meta( $post_id, 'woocg-desciption', $description ); // ignore typo
						}
					}
				}
				break;
		}
	}

	/**
	 * Show rating request
	 *
	 * @since 1.1.0
	 * @version 1.1.1
	 * @return void
	 */
	public function show_rating() {
		/**
		 * Request Rating
		 */
		// if has at least two custom gateways and has never been shown
		$args = array(
			'post_type'     => Functions::gateway_slug(),
			'fields'        => 'ids',
			'no_found_rows' => true,
		);

		if ( boolval( get_transient( Functions::get_plugin_slug( '-rate' ) ) ) === false && count( get_posts( $args ) ) >= 2 ) {
			wp_enqueue_script( WOO_CUSTOM_GATEWAY_SLUG );
			wp_enqueue_style( WOO_CUSTOM_GATEWAY_SLUG );

			echo Functions::get_template( WOO_CUSTOM_GATEWAY_SLUG . '-admin-rating', array(), 'admin-rating.php' );

		}
	}

	/**
	 *
	 * @since 1.0.0
	 * @param \WP_Post $post
	 */
	public function addMetaBoxs( $post ) {

		add_meta_box( 'woocg-post-description', __( 'Payment Method Description', WOO_CUSTOM_GATEWAY_SLUG ), array( $this, 'descriptionMetaBox' ), Functions::gateway_slug(), 'normal', 'high' );
	}

	/**
	 *
	 * @since 1.0.0
	 * @version 1.2.3
	 * @param \WP_Post $post
	 * @param array    $args
	 */
	public function descriptionMetaBox( $post, $args ) {
		echo Functions::get_template( WOO_CUSTOM_GATEWAY_SLUG . '-admin-edit-post', compact( 'post', 'args' ), 'admin-edit-post.php' );
	}

	/**
	 *
	 * @since 1.0.0
	 * @version 1.3.0
	 * @param  string $content
	 * @param  int    $post
	 * @param  int    $thumbnail_id
	 * @return string
	 */
	public function filter_featured_image_admin_text( $content, $post, $thumbnail_id ) {

		if ( get_post_type( $post ) === Functions::gateway_slug() && $thumbnail_id == null ) {
			$content .= wpautop( __( 'If you want to show an image next to the gateway\'s name on the frontend, select an image.', WOO_CUSTOM_GATEWAY_SLUG ) );
		}

		return $content;
	}

	/**
	 * Change the enter tilte here text
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 * @param  string   $input
	 * @param  \WP_Post $post
	 * @return string
	 */
	public function custom_enter_title( $input, $post ) {

		if ( Functions::gateway_slug() === get_post_type( $post ) ) {
			$input = __( 'Payment Method Name', WOO_CUSTOM_GATEWAY_SLUG );
		}

		return $input;
	}

	/**
	 * Output the column data
	 *
	 * @since 1.0.0
	 * @version 1.3.0
	 * @param $column
	 * @param $post
	 */
	public function add_column_data( $column, $post ) {

		if ( Functions::gateway_slug() === get_post_type( $post ) ) {
			switch ( $column ) {
				case 'thumbnail':
					echo the_post_thumbnail( 'thumb' );
					break;
			}
		}
	}

	/**
	 * Add custom post thumbnail column
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 * @param  $columns
	 * @return mixed
	 */
	public function add_columns( $columns ) {

		$columns['thumbnail'] = __( 'Thumbnail', WOO_CUSTOM_GATEWAY_SLUG );

		return $columns;
	}

	/**
	 *
	 * Add quick link to the plugin settings on the plugins page
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 * @param  array    $actions
	 * @param  \WP_Post $post
	 * @return string
	 */
	public function post_row_actions( $actions, $post ) {

		if ( get_post_type( $post ) === Functions::gateway_slug() ) {

			$args = array(
				'page'    => 'wc-settings',
				'tab'     => 'checkout',
				'section' => Functions::gateway_id( $post->ID ),
			);

			$link = add_query_arg( $args, admin_url( 'admin.php' ) );

			$actions['settings'] = sprintf( '<a href="%s">%s</a>', $link, __( 'Settings', WOO_CUSTOM_GATEWAY_SLUG ) );
		}

		return $actions;
	}

	/**
	 * On delete custom post type
	 *
	 * @since 1.0.0
	 * @version 1.3.0
	 * @param int $post
	 */
	public function on_delete_method( $post ) {

		if ( get_post_type( $post ) === Functions::gateway_slug() ) {
			$method = new Gateway( $post );
			delete_option( $method->get_option_key() );
		}
	}

}
