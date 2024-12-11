<?php
namespace ThemeIntranet;

if (!defined('ABSPATH')) exit;

class Intranet {

	private string $theme_slug;
	private string $theme_version;

	public function __construct() {
		$this->theme_slug = strtolower(wp_get_theme()->get('Name'));
		$this->theme_version = wp_get_theme()->get('Version');
	}

	public function remove_unnecessary_wp_head_links() {
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'wp_resource_hints', 2);
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'xfn_link', 2);

		add_filter('xmlrpc_enabled', '__return_false');
	}

	public function enqueue_scripts() {
		wp_enqueue_style($this->theme_slug, get_stylesheet_directory_uri().'/assets/css/style.css', [], $this->theme_version, 'screen');
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
		echo '<!-- OpenGraphTags -->' . PHP_EOL;

		$site_name = get_bloginfo('name');
		$blog_id = get_current_blog_id();
		$site_name = get_current_site()->site_name;
		$site_id = get_current_site()->site_id;

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
			if (has_post_thumbnail($post->ID)) {
				$og_image = get_the_post_thumbnail_url($post->ID, 'large');
			}
		}


		printf('<meta property="og:id" content="">'.PHP_EOL, esc_attr($post->ID));
		printf('<meta property="og:title" content="%s">'.PHP_EOL, esc_attr($og_title));
		printf('<meta property="og:description" content="%s">'.PHP_EOL, esc_attr($og_description));
		printf('<meta property="og:url" content="%s">'.PHP_EOL, esc_url($og_url));
		printf('<meta property="og:type" content="%s">'.PHP_EOL, esc_attr($og_type));
		if ($og_image) {
			printf('<meta property="og:image" content="%s">'.PHP_EOL, esc_url($og_image));
		}
		if (is_multisite()) {
			printf('<meta property="og:site_name" content="%s">'.PHP_EOL, esc_attr($site_name));
			printf('<meta property="og:site_id" content="%s">'.PHP_EOL, esc_attr($site_id));
			printf('<meta property="og:blog_id" content="%s">'.PHP_EOL, esc_attr($blog_id));
		}
		echo '<!-- /OpenGraphTags -->' .PHP_EOL;
	}

}
