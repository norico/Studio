<?php
namespace ThemeIntranet;

if (!defined('ABSPATH')) exit;

class Intranet {

	private string $theme_slug;
	private string $theme_version;
	private string $adminbar_documentation_link;
	private string $default_image;

	public function __construct() {
		$this->theme_slug = strtolower(wp_get_theme()->get('Name'));
		$this->theme_version = wp_get_theme()->get('Version');
		$this->adminbar_documentation_link = '/documentation';
		$this->default_image = get_template_directory_uri() . '/assets/images/default-thumbnail.png';;
	}

	/**
	 * @return void
	 */
	public function remove_unnecessary_wp_actions() {
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'wp_resource_hints', 2);
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'xfn_link', 2);
	}

	/**
	 * @return void
	 */
	public function remove_unnecessary_wp_filters(): void {
		add_filter('xmlrpc_enabled', '__return_false');
		add_filter('get_avatar', '__return_false');
	}

	/**
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_style($this->theme_slug, get_stylesheet_directory_uri().'/assets/css/style.css', [], $this->theme_version, 'screen');
	}

	/**
	 * @return void
	 */
	public function after_setup_theme(): void {
		add_theme_support("title-tag");
		add_theme_support("post-thumbnails");
		add_theme_support('html5', array('search-form', 'style', 'script'));
		add_theme_support('custom-logo', array(
			'flex-height' => true,
			'flex-width'  => true,
		));
	}

	/**
	 * @return void
	 */
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

	/**
	 * @return void
	 */
	private function create_and_set_pages(): void {
		$home_page_id = $this->get_or_create_page('Page d\'accueil', 'homepage', '<!-- wp:paragraph --><p>Bienvenue sur notre site</p><!-- /wp:paragraph -->');
		$blog_page_id = $this->get_or_create_page('Page des articles', 'actualites', '<!-- wp:paragraph --><p>Les actualités</p><!-- /wp:paragraph -->');
		update_option('show_on_front', 'page');
		update_option('page_on_front', $home_page_id);
		update_option('page_for_posts', $blog_page_id);
	}

	/**
	 * @param $title
	 * @param $slug
	 * @param $content
	 *
	 * @return int|\WP_Error
	 */
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

	/**
	 * @param $title
	 *
	 * @return int|\WP_Post|null
	 */
	private function get_page_by_title($title): int|\WP_Post|null {
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

	/**
	 * @return void
	 */
	public function insert_custom_meta(): void {
		global $post;
		$og_site_name = get_bloginfo('name');
		$og_blog_id   = get_current_blog_id();
		$og_site_id   = get_current_network_id();
		$og_xxx       = "";

		if( is_main_site() && (is_home() OR is_front_page()) ) {
			$og_title = get_bloginfo('name');
			$og_description = get_bloginfo('description');
			$og_url = get_bloginfo('url');
			$og_type = 'website';
			$og_image = wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ) );
		}

		if( is_home() OR is_front_page() ) {

			$page_type = is_home() ? "HomePage (blog)" : "FrontPage (home)";

			$og_title = get_bloginfo('name'). ' - '. $page_type;
			$og_description = get_bloginfo('description');
			$og_url = get_bloginfo('url');
			$og_type = $page_type;
			$og_image = wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ) );
		}

		else {
			if ( is_404() ) {
				$og_title = '404';
				$og_description = __('Oops! That embed cannot be found.', 'default');
				$og_url = get_bloginfo('url');
				$og_type = 'page-404';
				$og_image = 'none';
			}
			else {
				$og_title = get_the_title();
				$og_description = has_excerpt($post->ID) ? get_the_excerpt() : wp_trim_words(get_the_content(), 55, '...');
				$og_url = get_permalink();
				$og_type = get_post_type();
				$og_image = '';
				if (has_post_thumbnail($post->ID)) {
					$og_image = get_the_post_thumbnail_url($post->ID, 'large');
				}
			}

		}
		printf( '<meta property="og:id" content="%s">' . PHP_EOL, $post->ID );
		printf( '<meta property="og:site_id" content="%s">' . PHP_EOL, $og_site_id );
		printf( '<meta property="og:blog_id" content="%s">' . PHP_EOL, $og_blog_id );
		printf( '<meta property="og:site_name" content="%s">' . PHP_EOL, esc_attr( $og_site_name ) );
		printf( '<meta property="og:url" content="%s">' . PHP_EOL, esc_url( $og_url ) );
		printf( '<meta property="og:title" content="%s">' . PHP_EOL, html_entity_decode(esc_attr( $og_title )) );
		printf( '<meta property="og:description" content="%s">' . PHP_EOL, html_entity_decode(esc_attr($og_description)) );
		printf( '<meta property="og:type" content="%s">' . PHP_EOL, esc_attr( $og_type ) );
		if ( $og_image ) {
			printf( '<meta property="og:image" content="%s">' . PHP_EOL, esc_url( $og_image ) );
		}


	}

	/**
	 * @param \WP_Admin_Bar $wp_admin_bar
	 *
	 * @return void
	 */
	public function admin_bar( \WP_Admin_Bar $wp_admin_bar): void {
		$this->wp_logo( $wp_admin_bar );
		$this->adminbar_documentation($wp_admin_bar);

		$this->my_account( $wp_admin_bar );

	}

	/**
	 * @param \WP_Admin_Bar $wp_admin_bar
	 *
	 * @return void
	 */
	public function wp_logo( \WP_Admin_Bar $wp_admin_bar ): void {
		$wp_admin_bar->add_menu( array(
			'id'   => 'wp-logo',
			'href' => null,
		) );
		$wp_admin_bar->remove_node( 'about' );
		$wp_admin_bar->remove_node( 'contribute' );
		$wp_admin_bar->remove_node( 'wporg' );
		$wp_admin_bar->remove_node( 'learn' );
		$wp_admin_bar->remove_node( 'support-forums' );
		$wp_admin_bar->remove_node( 'feedback' );
	}

	/**
	 * @param \WP_Admin_Bar $wp_admin_bar
	 *
	 * @return void
	 */
	private function adminbar_documentation( \WP_Admin_Bar $wp_admin_bar ) {
		$wp_admin_bar->remove_node('documentation');
		$wp_admin_bar->add_node(
			array(
				'parent' => 'wp-logo-external',
				'id'     => 'documentation',
				'title'  => __( 'Documentation' ),
				'href'   => __( esc_url($this->adminbar_documentation_link) ),
			)
		);
	}

	/**
	 * @param \WP_Admin_Bar $wp_admin_bar
	 *
	 * @return void
	 */
	public function my_account( \WP_Admin_Bar $wp_admin_bar ): void {
		$wp_admin_bar->remove_node( "my-account" );
		$wp_admin_bar->add_menu( array(
			'id'   => 'my-account',
			'href' => null,
		) );
		$howdy = sprintf(__('Howdy, %s'), '<span class="display-name">' . wp_get_current_user()->display_name . '</span>');
		$wp_admin_bar->add_menu(array(
			'id' => 'my-account',
			'title' => $howdy,
			'parent' => 'top-secondary',
			'href' => null,
		));
		$wp_admin_bar->remove_node("user-info");
		$wp_admin_bar->add_menu(array(
			'parent' => 'my-account',
			'id' => 'user-role',
			'title' => $this->get_user_role("true")
		));
		$wp_admin_bar->add_menu(array(
			'id' => 'logout',
			'parent' => 'my-account',
			'title' => __('Log Out'),
			'href' => wp_logout_url(),
		));
	}

	/**
	 * @param $translated
	 *
	 * @return string
	 */
	private function get_user_role( $translated=false ) {
		$role = \WP_Roles()->get_names()[wp_get_current_user()->roles[0]];
		if ($translated) {
			$role = translate_user_role( $role );
		}
		return $role;
	}


	/**
	 * @param $src
	 *
	 * @return mixed|string
	 */
	public function remove_version_script($src) {
		if( !is_user_logged_in() )
		{
			$parts = explode( '?ver', $src );
			if ( is_array($parts) )
			{
				$src = $parts[0];
			}
		}
		return $src;
	}


	/**
	 * @param int $post_id
	 * @param bool $link
	 * @param string|null $format
	 *
	 * @return void
	 */
	public function theme_post_image(int $post_id, bool $link=true, string $format=null) {

		$permalink   = get_permalink($post_id);
		$image_title = get_post(get_post_thumbnail_id($post_id))->post_title;
		$image_alt   = get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true) ?? 'Texte alternatif';
		$post_image  =  get_the_post_thumbnail($post_id, null, [ 'alt' => $image_alt, 'title' => $image_title ] );
		$title       = get_the_title($post_id);

		echo '<div name="' . $format . '">';
		if ( has_post_thumbnail() ) {
			if ( $link === true ) {
				printf( '<a href="%2$s">%3$s</a>', $title, $permalink, $post_image );
			} else {
				printf( '%s', $post_image );
			}
		} else {
			if ( $link === true ) {
				printf( '<img src="%1$s">', $this->default_image );
			} else {
				printf( '<a title="%1$s" href="%2$s"><img alt="default" title="default" src="%3$s"></a>', $title,  $permalink, $this->default_image);
			}
		}
		echo '</div>';
	}


}