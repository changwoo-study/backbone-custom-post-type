<?php
/**
 * Plugin Name: Backbone and Custom Post Type
 * Description: A Study plugin for WP REST API & backbone.js
 * Author:      남창우
 * Author URI:  mailto://cs.chwnam@gmail.com
 * Textdomain:  bbcpt
 * Version:     1.0
 */

require_once( __DIR__ . '/functions.php' );

add_action( 'init', 'bbcpt_custom_post' );

function bbcpt_custom_post() {
	$args = array(
		'label'        => 'bbcpt',
		'public'       => TRUE,
		'show_in_rest' => TRUE,
	);
	register_post_type( 'bbcpt', $args );
}

register_activation_hook( __FILE__, 'bbcpt_activation' );

function bbcpt_activation() {
	bbcpt_custom_post();
	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'bbcpt_deactivation' );

function bbcpt_deactivation() {
	flush_rewrite_rules();
}

add_action( 'admin_menu', 'bbcpt_admin_menu' );

function bbcpt_admin_menu() {
	$hook = add_menu_page( __( 'bbcpt-admin', 'bbcpt' ), __( 'bbcpt-admin', 'bbcpt' ), 'read', 'bbcpt-admin', 'bbcpt_output_menu' );
	add_action( "load-{$hook}", 'bbcpt_load_admin_menu' );
	bbcpt_memory_set( 'menu_hook', $hook );
}

function bbcpt_load_admin_menu() {
}

function bbcpt_output_menu() {
	bbcpt_template( 'admin-menu.php' );
}

add_action( 'admin_enqueue_scripts', 'bbcpt_enqueue_scripts' );

function bbcpt_enqueue_scripts( $hook ) {
	if ( $hook === bbcpt_memory_get( 'menu_hook' ) ) {
		wp_enqueue_style( 'bbcpt-admin-menu', plugin_dir_url( __FILE__ ) . 'assets/css/admin-menu.css' );

		wp_register_script(
			'bbcpt-admin-menu',
			plugin_dir_url( __FILE__ ) . 'assets/js/admin-menu.js',
			array(
				'wp-api',
				'wp-util',
			),
			'1.0.0',
			TRUE
		);

		wp_localize_script(
			'bbcpt-admin-menu',
			'bbcpt',
			array()
		);

//		wp_enqueue_script( 'backbone' );
//		wp_enqueue_script( 'underscore' );
//		wp_enqueue_script( 'wp-api' );
//		wp_enqueue_script( 'wp-util' );
		wp_enqueue_script( 'bbcpt-admin-menu' );
	}
}
