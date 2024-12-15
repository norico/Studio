<?php
if (!defined('ABSPATH'))
	exit;

// Dans votre fichier functions.php ou un fichier de plugin
require_once get_template_directory() . '/class-intranet.php';

// Si vous voulez instancier la classe manuellement plutôt qu'à la fin du fichier de classe
use ThemeIntranet\Intranet;
$theme = new Intranet();

add_action('wp_enqueue_scripts', [$theme, 'enqueue_scripts']);
add_action('enqueue_block_editor_assets', [$theme, 'enqueue_block_editor_assets'],20);


add_action('init', [$theme, 'remove_unnecessary_wp_actions']);
add_action('init', [$theme, 'remove_unnecessary_wp_filters']);
add_action('after_setup_theme', [$theme, 'after_setup_theme']);
add_action('after_switch_theme', [$theme, 'after_switch_theme']);
add_action('wp_head', [$theme, 'insert_custom_meta']);
add_action('admin_bar_menu', [$theme,'admin_bar'], 15);
add_filter('script_loader_src', [$theme, 'remove_version_script'], 15, 1 );
add_filter('style_loader_src', [$theme, 'remove_version_script'], 15, 1 );

add_action('post_image', [$theme, 'post_image'], 10, 3);
add_action('wp_footer', [$theme, 'side_modal']);

add_filter( 'allowed_block_types_all', [$theme,'disable_gutenberg_blocks'], 30, 2 );
add_filter( 'block_editor_settings_all', [$theme,'disable_openverse'] );


function the_post_image( int $post_id=null, bool $link=false, string $format=null ): void {
	$post_id = is_null($post_id) ? get_the_ID() : $post_id;
	$format  = is_null($format) ? 'thumbnail' : $format;
	do_action('post_image', $post_id, $link, $format);
}