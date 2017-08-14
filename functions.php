<?php

function &bb_cpt_memory() {
	static $memory = array();

	return $memory;
}

function bb_cpt_memory_set( $key, $value ) {
	$memory         = &bb_cpt_memory();
	$memory[ $key ] = $value;
}

function bb_cpt_memory_get( $key, $default = FALSE ) {
	$memory = &bb_cpt_memory();

	return isset( $memory[ $key ] ) ? $memory[ $key ] : $default;
}

function bb_cpt_template( $template_name, $context = array() ) {
	$template = __DIR__ . '/templates/' . trim( $template_name, '/' );
	if ( ! realpath( $template ) ) {
		wp_die( sprintf( __( 'template \'%s\' does not exist.', 'bb-cpt' ), $template ) );
	}
	if ( $context ) {
		extract( $context );
	}
	/** @noinspection PhpIncludeInspection */
	include( $template );
}
