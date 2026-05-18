<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EDM_Landing {

	public function __construct() {
		add_action( 'init',                  [ __CLASS__, 'add_rewrite_rules' ] );
		add_filter( 'query_vars',            [ $this, 'add_query_vars' ] );
		add_action( 'template_redirect',     [ $this, 'maybe_render' ] );
		add_filter( 'document_title_parts',  [ $this, 'set_title' ] );
		add_filter( 'body_class',            [ $this, 'add_body_class' ] );
	}

	public static function add_rewrite_rules() {
		add_rewrite_rule(
			'^digitalni-meni/?$',
			'index.php?edm_page=digitalni-meni',
			'top'
		);
	}

	public function add_query_vars( $vars ) {
		$vars[] = 'edm_page';
		return $vars;
	}

	public function maybe_render() {
		if ( get_query_var( 'edm_page' ) !== 'digitalni-meni' ) {
			return;
		}
		// Load from child theme; fall back to plugin template
		$theme_template = get_stylesheet_directory() . '/template-digitalni-meni.php';
		if ( file_exists( $theme_template ) ) {
			include $theme_template;
		} else {
			include EDM_PLUGIN_DIR . 'templates/landing.php';
		}
		exit;
	}

	private static function is_landing() {
		if ( get_query_var( 'edm_page' ) === 'digitalni-meni' ) {
			return true;
		}
		if ( is_singular( 'page' ) && get_page_template_slug() === 'template-digitalni-meni.php' ) {
			return true;
		}
		return false;
	}

	public function set_title( $title ) {
		if ( self::is_landing() ) {
			$title['title'] = 'Digitalni Meni za vaš lokal';
			$title['site']  = get_bloginfo( 'name' );
		}
		return $title;
	}

	public function add_body_class( $classes ) {
		if ( self::is_landing() ) {
			$classes[] = 'edm-landing-page';
		}
		return $classes;
	}

	/**
	 * Adds the landing page link to the primary nav menu.
	 * Called once during plugin activation.
	 */
	public static function maybe_add_nav_menu_item() {
		$target_url = home_url( '/digitalni-meni/' );

		// Try theme location 'primary' first; fall back to any registered menu
		$locations = get_nav_menu_locations();
		$menu_id   = 0;

		foreach ( [ 'primary', 'main', 'primary-menu', 'header-menu', 'main-menu' ] as $loc ) {
			if ( ! empty( $locations[ $loc ] ) ) {
				$menu_id = (int) $locations[ $loc ];
				break;
			}
		}

		if ( ! $menu_id ) {
			$menus = wp_get_nav_menus();
			if ( ! empty( $menus ) ) {
				$menu_id = (int) $menus[0]->term_id;
			}
		}

		if ( ! $menu_id ) {
			return; // No menus found
		}

		// Bail if the item already exists in this menu
		$existing = wp_get_nav_menu_items( $menu_id );
		if ( $existing ) {
			foreach ( $existing as $item ) {
				if ( rtrim( $item->url, '/' ) === rtrim( $target_url, '/' ) ) {
					return;
				}
			}
		}

		wp_update_nav_menu_item( $menu_id, 0, [
			'menu-item-title'  => 'Digitalni Meni',
			'menu-item-url'    => $target_url,
			'menu-item-status' => 'publish',
			'menu-item-type'   => 'custom',
		] );
	}
}

new EDM_Landing();
