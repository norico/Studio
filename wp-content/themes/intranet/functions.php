<?php
if (!defined('ABSPATH'))
	exit;

add_action('wp_enqueue_scripts', 'intranet_enqueue_scripts');


function intranet_enqueue_scripts() {

	$theme_name = wp_get_theme()->get( 'Name' );
	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style($theme_name.'-style', get_stylesheet_directory_uri().'/assets/css/style.css', [], $theme_version, 'screen');
}
