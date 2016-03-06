<?php
/**
 * Plugin Name: WooCommerce Checkout Fields
 * Plugin URI: https://github.com/fernandoalls/woocommerce-checkout-fields
 * Description: Easily manipulate checkout fields for WooCommerce
 * Author: Fernando Alves
 * Author URI: https://github.com/fernandoalls/woocommerce-checkout-fields
 * Version: 1.1
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WC_CF' ) ) :

define('PLUGIN_URL', plugin_dir_url( __FILE__ ));
/**
 * WooCommerce Checkout Fields main class.
 */
class WC_CF {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = '1.1';

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin public actions.
	 */
	private function __construct() {

		// Checks with WooCommerce is installed.
		if ( class_exists( 'WooCommerce' ) ) {
			$this->includes();
		} else {
			add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );
		}
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Call core classes
	 */
	private function includes() {

		include_once 'includes/wc-core.php';
	}

	/**
	 * WooCommerce fallback notice.
	 *
	 * @return string
	 */
	public function woocommerce_missing_notice() {
		echo '<div class="error"><p><strong>Woocommerce Campos do Checkout</strong> ' . sprintf( __( 'depends on the last version of %s to work!', 'woocommerce-bcash' ), '<a href="http://wordpress.org/plugins/woocommerce/">' . __( 'WooCommerce', 'woocommerce-bcash' ) . '</a>' ) . '</p></div>';
	}

}

add_action( 'plugins_loaded', array( 'WC_CF', 'get_instance' ) );

endif;
