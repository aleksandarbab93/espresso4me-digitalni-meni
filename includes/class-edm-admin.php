<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EDM_Admin {

	public function __construct() {
		add_action( 'add_meta_boxes',            [ $this, 'add_meta_box' ] );
		add_action( 'save_post_job_listing',     [ $this, 'save_meta_box' ], 10, 2 );
		add_action( 'admin_enqueue_scripts',     [ $this, 'enqueue_assets' ] );
		add_filter( 'manage_job_listing_posts_columns',       [ $this, 'add_column' ] );
		add_action( 'manage_job_listing_posts_custom_column', [ $this, 'render_column' ], 10, 2 );
	}

	// -------------------------------------------------------------------------
	// Meta box
	// -------------------------------------------------------------------------

	public function add_meta_box() {
		add_meta_box(
			'edm-meta-box',
			'🍽 espresso4me Digitalni Meni',
			[ $this, 'render_meta_box' ],
			'job_listing',
			'normal',
			'default'
		);
	}

	public function render_meta_box( $post ) {
		wp_nonce_field( 'edm_save_menu_' . $post->ID, 'edm_nonce' );

		$raw       = get_post_meta( $post->ID, '_edm_menu_data', true );
		$menu_json = $raw ?: '{"categories":[]}';

		$has_slug = ! empty( $post->post_name ) && $post->post_status === 'publish';
		$menu_url = $has_slug ? home_url( '/meni/' . $post->post_name . '/' ) : '';
		$qr_src   = $menu_url
			? 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&color=2b1e12&bgcolor=ffffff&data=' . rawurlencode( $menu_url )
			: '';
		?>
		<div id="edm-wrapper">

			<?php if ( $has_slug ) : ?>
			<div class="edm-qr-section">
				<div class="edm-qr-img-wrap">
					<img src="<?php echo esc_attr( $qr_src ); ?>" alt="QR kod menija" width="160" height="160" />
				</div>
				<div class="edm-qr-info">
					<h4>URL Menija</h4>
					<code><?php echo esc_html( $menu_url ); ?></code>
					<div class="edm-qr-actions">
						<a href="<?php echo esc_url( $menu_url ); ?>" target="_blank" class="button button-secondary">
							↗ Pregledaj meni
						</a>
						<a href="<?php echo esc_url( 'https://api.qrserver.com/v1/create-qr-code/?size=600x600&download=1&color=2b1e12&bgcolor=ffffff&data=' . rawurlencode( $menu_url ) ); ?>" target="_blank" class="button button-primary edm-btn-download">
							⬇ Preuzmi QR kod (600×600)
						</a>
					</div>
					<p class="edm-hint">Odštampajte QR kod i postavite ga na stol ili ulaz lokala.</p>
				</div>
			</div>
			<?php else : ?>
			<div class="edm-notice edm-notice-info">
				<strong>QR kod će biti dostupan</strong> nakon što se lokal objavi (publish). Sačuvajte lokal kao <em>Objavljen</em> da biste vidjeli i preuzeli QR kod.
			</div>
			<?php endif; ?>

			<div class="edm-editor-section">
				<div class="edm-editor-header">
					<h4>Stavke menija</h4>
					<p class="edm-hint">Dodajte kategorije (Kafa, Čajevi, Sokovi, Hrana…) i stavke unutar svake kategorije.</p>
				</div>

				<div id="edm-categories-list"></div>

				<div id="edm-empty-notice" class="edm-empty-state" style="display:none;">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#c8c0b8" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
					<p>Meni je prazan.<br>Kliknite <strong>+ Dodaj kategoriju</strong> da počnete.</p>
				</div>

				<button type="button" id="edm-add-category" class="button button-secondary edm-add-cat-btn">
					+ Dodaj kategoriju
				</button>

				<input type="hidden" name="edm_menu_data" id="edm-menu-data" value="<?php echo esc_attr( $menu_json ); ?>" />
			</div>

		</div>
		<?php
	}

	public function save_meta_box( $post_id, $post ) {
		if ( ! isset( $_POST['edm_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['edm_nonce'] ) ), 'edm_save_menu_' . $post_id ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( ! isset( $_POST['edm_menu_data'] ) ) {
			return;
		}

		$raw     = wp_unslash( $_POST['edm_menu_data'] );
		$decoded = json_decode( $raw, true );

		if ( $decoded === null ) {
			return;
		}

		// Sanitize all text fields recursively
		$clean = edm_sanitize_menu_data( $decoded );

		update_post_meta( $post_id, '_edm_menu_data', wp_json_encode( $clean ) );
	}

	// -------------------------------------------------------------------------
	// Admin column: show whether a menu exists
	// -------------------------------------------------------------------------

	public function add_column( $columns ) {
		$columns['edm_menu'] = 'Digitalni meni';
		return $columns;
	}

	public function render_column( $column, $post_id ) {
		if ( $column !== 'edm_menu' ) {
			return;
		}
		$raw  = get_post_meta( $post_id, '_edm_menu_data', true );
		$data = $raw ? json_decode( $raw, true ) : null;
		$has  = $data && ! empty( $data['categories'] );

		if ( $has ) {
			$post     = get_post( $post_id );
			$menu_url = $post ? home_url( '/meni/' . $post->post_name . '/' ) : '#';
			echo '<a href="' . esc_url( $menu_url ) . '" target="_blank" style="color:#c8773a;font-weight:600;">✓ Meni aktivan</a>';
		} else {
			echo '<span style="color:#999;">—</span>';
		}
	}

	// -------------------------------------------------------------------------
	// Assets
	// -------------------------------------------------------------------------

	public function enqueue_assets( $hook ) {
		if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( ! $screen || $screen->post_type !== 'job_listing' ) {
			return;
		}

		wp_enqueue_style(
			'edm-admin',
			EDM_PLUGIN_URL . 'assets/admin.css',
			[],
			EDM_VERSION
		);
		wp_enqueue_script(
			'edm-admin',
			EDM_PLUGIN_URL . 'assets/admin.js',
			[],
			EDM_VERSION,
			true
		);
	}
}

// -------------------------------------------------------------------------
// Helpers
// -------------------------------------------------------------------------

function edm_sanitize_menu_data( $data ) {
	if ( ! is_array( $data ) ) {
		return [ 'categories' => [] ];
	}

	$categories = [];

	foreach ( (array) ( $data['categories'] ?? [] ) as $cat ) {
		$items = [];
		foreach ( (array) ( $cat['items'] ?? [] ) as $item ) {
			$items[] = [
				'id'          => sanitize_text_field( $item['id'] ?? '' ),
				'name'        => sanitize_text_field( $item['name'] ?? '' ),
				'description' => sanitize_textarea_field( $item['description'] ?? '' ),
				'price'       => sanitize_text_field( $item['price'] ?? '' ),
			];
		}
		$categories[] = [
			'id'    => sanitize_text_field( $cat['id'] ?? '' ),
			'name'  => sanitize_text_field( $cat['name'] ?? '' ),
			'items' => $items,
		];
	}

	return [ 'categories' => $categories ];
}
