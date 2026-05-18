<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EDM_Public {

	public function __construct() {
		add_action( 'init',              [ __CLASS__, 'add_rewrite_rules' ] );
		add_filter( 'query_vars',        [ $this, 'add_query_vars' ] );
		add_action( 'template_redirect', [ $this, 'maybe_render_menu' ] );
	}

	public static function add_rewrite_rules() {
		add_rewrite_rule(
			'^meni/([^/]+)/?$',
			'index.php?espresso_meni_slug=$matches[1]',
			'top'
		);
	}

	public function add_query_vars( $vars ) {
		$vars[] = 'espresso_meni_slug';
		return $vars;
	}

	public function maybe_render_menu() {
		$slug = get_query_var( 'espresso_meni_slug' );
		if ( ! $slug ) {
			return;
		}

		$slug = sanitize_title( $slug );

		$posts = get_posts( [
			'post_type'      => 'job_listing',
			'name'           => $slug,
			'posts_per_page' => 1,
			'post_status'    => 'publish',
		] );

		if ( empty( $posts ) ) {
			wp_die(
				'<h1>Meni nije pronađen</h1><p>Ovaj lokal nema aktivan digitalni meni.</p>',
				'Meni nije pronađen',
				[ 'response' => 404 ]
			);
		}

		$listing = $posts[0];

		$raw       = get_post_meta( $listing->ID, '_edm_menu_data', true );
		$menu_data = $raw ? json_decode( $raw, true ) : [ 'categories' => [] ];
		if ( ! is_array( $menu_data ) ) {
			$menu_data = [ 'categories' => [] ];
		}

		$listing_name = $listing->post_title;
		$listing_url  = get_permalink( $listing->ID );

		// Use MyListing's logo field (job_logo meta) — the circular logo shown on the listing page
		$logo_url = '';
		if ( class_exists( '\\MyListing\\Src\\Listing' ) ) {
			$ml = \MyListing\Src\Listing::get( $listing );
			if ( $ml ) {
				$logo_url = $ml->get_logo( 'thumbnail' );
			}
		}
		if ( ! $logo_url ) {
			$logo_url = get_the_post_thumbnail_url( $listing->ID, 'thumbnail' );
		}

		// Load standalone template
		include EDM_PLUGIN_DIR . 'templates/menu.php';
		exit;
	}
}
