<?php
if (!defined('ABSPATH'))
	exit;

add_action('wp_enqueue_scripts', 'intranet_enqueue_scripts');


function intranet_enqueue_scripts() {
	wp_enqueue_style('intranet-style', get_stylesheet_directory_uri().'/assets/css/style.css', [], '1.0.0', 'screen');
}
