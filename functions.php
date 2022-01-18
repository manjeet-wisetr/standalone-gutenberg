<?php
/**
 * Plugin Name: Standalone Gutenberg
 * Description: Standalone Gutenberg.
 * Version: 1.0.0.beta
 * Author: BuildWooFunnels
 * Author URI: https://buildwoofunnels.com
 * Domain Path: /languages
 * Requires at least: 5.6
 *
 * @package Standalone Gutenberg */

defined( 'ABSPATH' ) || exit;


if ( ! class_exists( 'StandaloneGutenberg' ) ) {

	/**
	 * StandaloneGutenberg
	 */
	class StandaloneGutenberg {

		/**
		 * __construct
		 *
		 * @return void
		 */
		public function __construct() {
			$this->define_constant();
			add_action( 'plugins_loaded', array( $this, 'plugin_loaded' ) );
		}

		/**
		 * Define Constant
		 *
		 * @return void
		 */
		public function define_constant() {
			defined( 'SG_PLUGIN_VERSION' ) || define( 'SG_PLUGIN_VERSION', time() );
			defined( 'SG_PLUGIN_FILE' ) || define( 'SG_PLUGIN_FILE', plugin_dir_path( __FILE__ ) );
			defined( 'SG_ABSPATH_URL' ) || define( 'SG_ABSPATH_URL', plugin_dir_url( __FILE__ ) );
		}

		/**
		 * Plugin Loaded
		 *
		 * @return void
		 */
		public function plugin_loaded() {
			if( is_admin() ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_block_editor_assets' ) );
                add_action( 'admin_menu', array($this, 'add_admin_menu_page' ), 20 );
				add_action( 'wp_ajax_save_block_data', array($this, 'save_block_data' ), 10);
			}
		}

        public function add_admin_menu_page() {
            add_menu_page( esc_html__( 'Standalone Gutenberg' ),
                esc_html__( 'Standalone Gutenberg' ),
                'edit_posts',
                'standalone-gutenberg', // hook/slug of page
                array( $this, 'render_app_root' ), // function to render page
                'dashicons-welcome-widgets-menus'
            );
        }

        public function render_app_root() {
			echo '<div id="standalone-gutenberg"></div>';
        }

		public function save_block_data() {
			
			if ( isset( $_POST['data'] ) ) {
				update_option( 'standalone-gutenberg', stripslashes( $_POST['data'] ) );
				wp_send_json_success( 'Data Successfully Updated' );
			}

			wp_send_json_error( 'Error' );
			die;
		}


		/**
		 * Enqueue assets for block.
		 */
		public function enqueue_block_editor_assets($hook) {
			global $current_screen;
			if ( 'toplevel_page_standalone-gutenberg' !== $hook ) {
				return;
			}

			$current_screen->is_block_editor( true );

			$app_name    = 'standalone-gutenberg';
			$editor_dir  = ( defined( 'SG_REACT_ENVIRONMENT' ) && 1 == SG_REACT_ENVIRONMENT ) ? 'http://localhost:9000' : SG_ABSPATH_URL . 'dist';
			$assets_path = defined( 'SG_REACT_ENVIRONMENT' ) && 1 === SG_REACT_ENVIRONMENT ? $editor_dir . "/$app_name.asset.php" : SG_ABSPATH_URL . "dist/$app_name.asset.php";
			$assets      = file_exists( $assets_path ) ? include $assets_path : array(
				'dependencies' => array(
					'wp-plugins',
					'wp-element',
					'wp-edit-post',
					'wp-i18n',
					'wp-api-request',
					'wp-data',
					'wp-hooks',
					'wp-plugins',
					'wp-components',
					'wp-blocks',
					'wp-editor',
					'wp-compose'
				),
				'version'      => time(),
			);

			$js_path    = "/$app_name.js";
			$style_path = "/$app_name.css";
			$deps       = ( isset( $assets['dependencies'] ) ? array_merge( $assets['dependencies'], array( 'jquery' ) ) : array( 'jquery' ) );
			$version    = $assets['version'];

			$script_deps = array_filter( $deps, function ( $dep ) {
				return false === strpos( $dep, 'css' );
			} );

			wp_enqueue_script( 'wp-format-library' );
			wp_enqueue_style( 'wp-format-library' );

			$script_handle = 'standalone-gutenberg';

			wp_enqueue_script( $script_handle, $editor_dir . $js_path, $script_deps, $version, true );

			wp_localize_script( $script_handle, 'sg', array(
				'ajax_url'  => admin_url( 'admin-ajax.php' ),
				'admin_url' => admin_url(),
				'blocks'    => get_option( 'standalone-gutenberg' ),
			) );

			// Enqueue our plugin Css.
			wp_enqueue_style( $script_handle, $editor_dir . $style_path, array( 'wp-edit-blocks' ), $version );

		}

	}

	new StandaloneGutenberg();

}
