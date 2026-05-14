<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EDM_Rest {

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	public function register_routes() {
		register_rest_route(
			'espresso/v1',
			'/menu/(?P<slug>[a-z0-9\-_]+)',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_menu' ],
				'permission_callback' => '__return_true',
				'args'                => [
					'slug' => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_title',
					],
				],
			]
		);
	}

	public function get_menu( WP_REST_Request $request ) {
		$slug = $request->get_param( 'slug' );

		$posts = get_posts( [
			'post_type'      => 'job_listing',
			'name'           => $slug,
			'posts_per_page' => 1,
			'post_status'    => 'publish',
		] );

		if ( empty( $posts ) ) {
			return new WP_Error(
				'not_found',
				'Lokal nije pronađen.',
				[ 'status' => 404 ]
			);
		}

		$listing = $posts[0];
		$raw     = get_post_meta( $listing->ID, '_edm_menu_data', true );
		$menu    = $raw ? json_decode( $raw, true ) : [ 'categories' => [] ];

		return rest_ensure_response( [
			'listing_id'   => $listing->ID,
			'listing_name' => $listing->post_title,
			'listing_url'  => get_permalink( $listing->ID ),
			'menu_url'     => home_url( '/meni/' . $listing->post_name . '/' ),
			'menu'         => $menu,
		] );
	}
}
