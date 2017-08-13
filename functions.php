<?php

function &bbcpt_memory() {
	static $memory = array();

	return $memory;
}

function bbcpt_memory_set( $key, $value ) {
	$memory         = &bbcpt_memory();
	$memory[ $key ] = $value;
}

function bbcpt_memory_get( $key, $default = FALSE ) {
	$memory = &bbcpt_memory();

	return isset( $memory[ $key ] ) ? $memory[ $key ] : $default;
}

function bbcpt_template( $template_name, $context = array() ) {
	$template = __DIR__ . '/templates/' . trim( $template_name, '/' );
	if ( ! realpath( $template ) ) {
		wp_die( sprintf( __( 'template \'%s\' does not exist.', 'bbcpt' ), $template ) );
	}
	if ( $context ) {
		extract( $context );
	}
	/** @noinspection PhpIncludeInspection */
	include( $template );
}
