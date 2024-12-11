<?php
namespace ThemeIntranet;

if (!defined('ABSPATH')) exit;

class Intranet {
	public function __construct() {
		add_action('init', [$this, 'remove_unnecessary_wp_head_links']);
		add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
		add_action('after_setup_theme', [$this, 'after_setup_theme']);
		add_action('after_switch_theme', [$this, 'after_switch_theme']);
		add_action('wp_head', [$this, 'insert_custom_meta']);
	}

	public function remove_unnecessary_wp_head_links() {
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'wp_resource_hints', 2);
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'xfn_link', 2);
		add_filter('xmlrpc_enabled', '__return_false');
	}

	public function enqueue_scripts() {
		$theme_slug = strtolower(wp_get_theme()->get('Name'));
		$theme_version = wp_get_theme()->get('Version');
		wp_enqueue_style($theme_slug, get_stylesheet_directory_uri().'/assets/css/style.css', [], $theme_version, 'screen');
	}

	public function after_setup_theme(): void {
		add_theme_support("title-tag");
		add_theme_support("post-thumbnails");
		add_theme_support('html5', array('search-form', 'style', 'script'));
		add_theme_support('custom-logo', array(
			'flex-height' => true,
			'flex-width'  => true,
		));
	}

	public function after_switch_theme(): void {
		global $wp_rewrite;

		$this->create_and_set_pages();

		update_option('posts_per_page', 12);
		update_option('timezone_string', 'Europe/Paris');
		update_option('date_format', 'j F Y');
		update_option('time_format', 'G\hi');
		update_option('thumbnail_size_w', 512);
		update_option('thumbnail_size_h', 512);
		update_option('medium_size_w', 1280);
		update_option('medium_size_h', 720);
		update_option('large_size_w', 1920);
		update_option('large_size_h', 1080);

		$wp_rewrite->set_permalink_structure('%postname%');
		$wp_rewrite->flush_rules();
		if (!term_exists('Actualités', 'category')) {
			wp_insert_term('Actualités', 'category');
		}
	}

	private function create_and_set_pages(): void {
		$home_page_id = $this->get_or_create_page('Page d\'accueil', 'homepage', 'Bienvenue sur notre site');
		$blog_page_id = $this->get_or_create_page('Page des articles', 'actualites', 'Les actualités');
		update_option('show_on_front', 'page');
		update_option('page_on_front', $home_page_id);
		update_option('page_for_posts', $blog_page_id);
	}

	private function get_or_create_page($title, $slug, $content) {
		$page = $this->get_page_by_title($title);

		if (!$page) {
			return wp_insert_post([
				'post_title'    => $title,
				'post_content'  => $content,
				'post_name'     => $slug,
				'post_status'   => 'publish',
				'post_type'     => 'page'
			]);
		}
		return $page->ID;
	}

	private function get_page_by_title($title) {
		$query = new \WP_Query([
			'post_type'              => 'page',
			'title'                  => $title,
			'post_status'            => 'all',
		]);

		if (!empty($query->posts)) {
			return $query->posts[0];
		}
		return null;
	}

	public function insert_custom_meta() {
		global $post;
		echo '<!-- OpenGraphTags -->' . "\n";

		if( is_main_site() && is_home() OR is_front_page() ) {
			$og_title = get_bloginfo('name');
			$og_description = get_bloginfo('description');
			$og_url = get_bloginfo('url');
			$og_type = 'website';
			$og_image = wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ) );
		}
		else {
			$og_title = get_the_title();
			$og_description = has_excerpt($post->ID) ? get_the_excerpt() : wp_trim_words(get_the_content(), 55, '...');
			$og_url = get_permalink();
			$og_type = get_post_type();
			// Image
			$og_image = '';
			if (has_post_thumbnail($post->ID)) {
				$og_image = get_the_post_thumbnail_url($post->ID, 'large');
			}
		}


		// Output
		echo '<meta property="og:id" content="' . esc_attr($post->ID) . '" />' . "\n";
		echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\n";
		echo '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\n";
		echo '<meta property="og:url" content="' . esc_url($og_url) . '" />' . "\n";
		echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
		if ($og_image) {
			echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
		}
		echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";


		echo '<!-- /OpenGraphTags -->' . "\n";
	}

}
