<?php
/**
 * Plugin Name: Backbone and Custom Post Type
 * Description: A Study plugin for WP REST API & backbone.js
 * Author:      남창우
 * Author URI:  mailto://cs.chwnam@gmail.com
 * Textdomain:  bb-cpt
 * Version:     1.1.0
 */

require_once( __DIR__ . '/functions.php' );

define( 'BB_CPT_VERSION', '1.1.0' );

/**
 * register bb-cpt custom post type
 */
add_action( 'init', 'bb_cpt_custom_post' );

function bb_cpt_custom_post() {
	$args = array(
		'label'        => 'bb-cpt',
		'public'       => TRUE,
		'supports'     => array(
			'title',          // title, editor 두 필드는 거의 필수적으로 들어가야 함.
			'editor',         // 그렇지 않으면 제목과 본문을 알 수 없음.
			'author',         // REST API 결과에서도 author 필드가 보이려면 여기 적어야 함.
//			'custom-fields',  // register_meta() 함수가 제대로 동작한다면 이렇게 썼을 것임.
		),
		'show_in_rest' => TRUE,
	);
	register_post_type( 'bb-cpt', $args );
}

/**
 * register activation hook for rewriting.
 */
register_activation_hook( __FILE__, 'bb_cpt_activation' );

function bb_cpt_activation() {
	bb_cpt_custom_post();
	flush_rewrite_rules();
}

/**
 * register deactivation hook for rewriting.
 */
register_deactivation_hook( __FILE__, 'bb_cpt_deactivation' );

function bb_cpt_deactivation() {
	flush_rewrite_rules();
}

/**
 * add admin menu
 */
add_action( 'admin_menu', 'bb_cpt_admin_menu' );

function bb_cpt_admin_menu() {
	$hook = add_menu_page( __( 'bb-cpt-admin', 'bb-cpt' ), __( 'bb-cpt-admin', 'bb-cpt' ), 'read', 'bb-cpt-admin', 'bb_cpt_output_menu' );

	/**
	 * load-{$hook} for extra stuffs, if needed.
	 */
	add_action( "load-{$hook}", 'bb_cpt_load_admin_menu' );

	// I hate to use global variables
	bb_cpt_memory_set( 'menu_hook', $hook );
}

function bb_cpt_load_admin_menu() {
}

/**
 * Callback of add_menu_page
 */
function bb_cpt_output_menu() {
	// HTML tags in the function body, mixed with echos and quotations... a chaos that I never want to see.
	bb_cpt_template( 'admin-menu.php' );
}

/**
 * Script enqueuing
 */
add_action( 'admin_enqueue_scripts', 'bb_cpt_enqueue_scripts' );

function bb_cpt_enqueue_scripts( $hook ) {
	// I said I hate to use global variables.
	if ( $hook === bb_cpt_memory_get( 'menu_hook' ) ) {
		wp_enqueue_style( 'bb-cpt-admin-menu', plugin_dir_url( __FILE__ ) . 'assets/css/admin-menu.css' );
		wp_register_script(
			'bb-cpt-admin-menu',
			plugin_dir_url( __FILE__ ) . 'assets/js/admin-menu.js',
			array(
				'wp-api',
				'wp-util',
			),
			BB_CPT_VERSION,
			TRUE
		);
		wp_localize_script(
			'bb-cpt-admin-menu',
			'bb-cpt',
			array()
		);
		wp_enqueue_script( 'bb-cpt-admin-menu' );
	}
}

/**
 * Language translation
 */
add_action( 'plugins_loaded', 'bb_cpt_translation' );

function bb_cpt_translation() {
	load_plugin_textdomain( 'bb-cpt', FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
}

/**
 * Adding custom fields
 *
 * @link https://developer.wordpress.org/rest-api/extending-the-rest-api/modifying-responses/#adding-custom-fields-to-api-responses
 */
add_action( 'rest_api_init', 'bb_cpt_api_init' );

function bb_cpt_api_init() {
//  이렇게는 제대로 동작하지 않는다.
//  4.8.1 현재 'post' 타입에만 등록 가능하며, post 에 등록되 메타 필드들은 모든 포스트 타입에 보인다.
//  그러므로 아래 register_rest_field 를 사용하여 별도의 필드를 만들어 주었다.
//	register_meta(
//		'bb-cpt',
//		'bb_cpt_tel',
//		array(
//			'type'              => 'string',
//			'description'       => 'telephone field',
//			'single'            => TRUE,
//			'show_in_rest'      => TRUE,
//			'sanitize_callback' => 'bb_cpt_sanitize_tel',
//		)
//	);
	register_rest_field(
		'bb-cpt',
		'tel',
		array(
			'get_callback'    => 'bb_cpt_tel_get_callback',
			'update_callback' => 'bb_cpt_tel_update_callback',
			'schema'          => array(
				'description' => esc_html__( 'Just a telephone number', 'bb-cpt' ),
				'type'        => 'string'
			),
		)
	);
}

/**
 * Callback of 'tel' field get
 *
 * @param array           $object      포스트 내용
 * @param string          $field_name  필드 이름. 여기서는 'tel'
 * @param WP_REST_Request $request
 * @param string          $object_type 포스트 타입
 *
 * @return string
 */
function bb_cpt_tel_get_callback( $object, $field_name, $request, $object_type ) {

	return get_post_meta( $object['id'], 'bb_cpt_tel', TRUE );
}

/**
 * Callback of 'tel' field update
 *
 * @param mixed           $field_value
 * @param WP_Post         $object
 * @param string          $field_name
 * @param WP_REST_Request $request
 * @param string          $object_type
 *
 * @return bool|WP_Error
 */
function bb_cpt_tel_update_callback( $field_value, $object, $field_name, $request, $object_type ) {

	if ( $field_name !== 'tel' || $object_type !== 'bb-cpt' ) {
		return new WP_Error(
			'rest_tel_update_failure',
			__( 'Failed to update tel.', 'bb-cpt' ),
			array( 'status' => 500 )
		);
	}

	return update_post_meta( $object->ID, 'bb_cpt_tel', sanitize_text_field( $field_value ) );
}
