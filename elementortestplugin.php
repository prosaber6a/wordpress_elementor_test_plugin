<?php

/**
 * Plugin Name: Elementor Test Plugin
 * Plugin URI: http://saberhr.com/
 * Description: Description will be go here...
 * Version: 1.0.0
 * Author: Saber Hossen Rabbani
 * Author URI: http://saberhr.com/
 * License: GPLv2 or later
 * Text Domain: elementortestplugin
 * Domain Path: /languages/
 *
 */

use \Elementor\Plugin as Plugin;


if ( ! defined( 'ABSPATH' ) ) {
	die(__('Direct Access is not allowed', 'elementortestplugin'));
}

final class ElementorTestExtension {

	const VERSION = "1.0.0";
	const MINIMUM_ELEMENTOR_VERSION = "2.0.0";
	const MINIMUM_PHP_VERSION = "7.0";

	private static $_instance = null;
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;

	}

	public function __construct() {

		add_action( 'plugins_loaded', [ $this, 'init' ] );

	}


	public function init() {

		// Load Plugin Text Domain
		load_plugin_textdomain('elementortestplugin');

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}


		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}


		// Add Plugin actions
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

		// Add New Category
		add_action( 'elementor/elements/categories_registered', [ $this, "register_new_category" ] );

	}

	public function admin_notice_missing_main_plugin () {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementortestplugin' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementortestplugin' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementortestplugin' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}


	public function  admin_notice_minimum_elementor_version () {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementortestplugin' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementortestplugin' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementortestplugin' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function admin_notice_minimum_php_version () {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
		/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementortestplugin' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementortestplugin' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elementortestplugin' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}


	public function init_widgets () {
		// Include Widget files
		require_once( __DIR__ . '/widgets/test-widget.php' );

		// Register widget
		Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_Test_Widget() );
	}



	public function register_new_category ($elements_manager ) {
		$elements_manager->add_category(
			'testCategory',
			[
				'title' => __( 'Test Category', 'elementortestplugin' ),
				'icon' => 'fa fa-plug',
			]
		);
	}


	public function includes() {}

}


ElementorTestExtension::instance();