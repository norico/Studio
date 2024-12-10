<?php
if (!defined('ABSPATH'))
	exit;

add_action('init', 'intranet_remove_unnecessary_wp_head_links');
add_action('wp_enqueue_scripts', 'intranet_enqueue_scripts');
add_action('after_setup_theme', 'intranet_after_setup_theme');

function intranet_remove_unnecessary_wp_head_links() {
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wp_resource_hints', 2);
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'xfn_link', 2);
	add_filter('xmlrpc_enabled', '__return_false');
}

function intranet_enqueue_scripts() {
	$theme_slug = strtolower(wp_get_theme()->get( 'Name' ));
	$theme_version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style($theme_slug, get_stylesheet_directory_uri().'/assets/css/style.css', [], $theme_version, 'screen');
}

function intranet_after_setup_theme(): void {
	add_theme_support("title-tag");
	add_theme_support("post-thumbnails");
	add_theme_support('html5', array( 'search-form', 'style', 'script') );
}
