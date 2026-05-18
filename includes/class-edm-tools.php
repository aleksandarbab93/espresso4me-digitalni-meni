<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EDM_Tools {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'handle_demo_action' ] );
	}

	public function add_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=job_listing',
			'espresso4me Meni — Alati',
			'🛠 Alati menija',
			'manage_options',
			'edm-tools',
			[ $this, 'render_tools_page' ]
		);
	}

	public function handle_demo_action() {
		if ( ! isset( $_GET['edm_action'] ) || $_GET['edm_action'] !== 'demo' ) {
			return;
		}
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'edm_demo_nonce' ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$this->create_demo_menu();
	}

	public function render_tools_page() {
		$demo_nonce = wp_create_nonce( 'edm_demo_nonce' );
		$demo_url   = add_query_arg( [
			'post_type'   => 'job_listing',
			'page'        => 'edm-tools',
			'edm_action'  => 'demo',
			'_wpnonce'    => $demo_nonce,
		], admin_url( 'edit.php' ) );

		?>
		<div class="wrap">
			<h1>🛠 espresso4me Digitalni Meni — Alati</h1>

			<div style="background:#fff;border:1px solid #ccc;border-radius:5px;padding:20px;margin-top:20px;max-width:600px;">

				<h2 style="margin-top:0;">Demo Meni</h2>
				<p style="color:#666;">Kreiraj demo meni sa uzorcima stavki na prvom objavljenom lokalu kako bi vidio kako će izgledati meni stranica.</p>

				<p>
					<a href="<?php echo esc_url( $demo_url ); ?>" class="button button-primary button-large">
						➕ Kreiraj Demo Meni
					</a>
				</p>

				<hr style="margin:20px 0;">

				<h3>O Demo Meniju (6 kategorija, 60+ stavki)</h3>
				<ul style="color:#666;line-height:1.8;">
					<li><strong>Topli Napici:</strong> Espresso, Espresso sa mlijekom, Cappuccino, Ice Coffee, Nescafe, Caffe Latte, Topla čokolada, Organska kafa…</li>
					<li><strong>Bezalkoholna Pića:</strong> Cijeđeni sokovi (Limunada, Američka limunada, Cijeđena pomorandža, Cijeđeni ananas…) + Sokovi (Pomorandža, Jabuka, Borovnica, Breskva)</li>
					<li><strong>Piva &amp; Cider:</strong> Točena piva — Carlsberg, Tuborg, Budweiser, Budweiser dark, Erdinger, Kronenbourg (0.25 / 0.3 / 0.5l)</li>
					<li><strong>Džin, Rum, Tequila:</strong> Džin (Beefeater, Monkey 47, Hendrick's…) + Rum (Havana Club, Captain Morgan…) + Tequila (Olmeca, Altos, Avion)</li>
					<li><strong>Viski:</strong> Jack Daniel's, Jameson, Chivas Regal 12Y, Glenfiddich 12Y, Ballantine's, Maker's Mark</li>
					<li><strong>Rakija:</strong> Podrum Pevac (Dunja, Kajsija, Šljiva, Zlatna) + Stara Pesma (Šljiva 5Y/7Y/12Y, Dunja Lux, Kajsija Lux, Medeno…)</li>
				</ul>

				<p style="color:#999;font-size:13px;margin-top:20px;">
					Možete kasnije obrisati ili ažurirati sve stavke u admin meta box-u lokala.
				</p>

			</div>

		</div>
		<?php
	}

	private function create_demo_menu() {
		$demo_menu = [
			'categories' => [
				[
					'id'            => 'cat_1',
					'name'          => 'Topli Napici',
					'icon'          => 'coffee',
					'subcategories' => [],
					'items'         => [
						[ 'id' => 'item_1',  'name' => 'Espresso',                 'description' => '0.05l',  'price' => '1.70' ],
						[ 'id' => 'item_2',  'name' => 'Espresso sa mlijekom S/C', 'description' => '0.1l',   'price' => '1.90' ],
						[ 'id' => 'item_3',  'name' => 'Espresso sa mlijekom L/C', 'description' => '0.2l',   'price' => '2.10' ],
						[ 'id' => 'item_4',  'name' => 'Espresso sa šlagom',       'description' => '0.1l',   'price' => '2.20' ],
						[ 'id' => 'item_5',  'name' => 'Domaća kafa',              'description' => '0.15l',  'price' => '1.60' ],
						[ 'id' => 'item_6',  'name' => 'Cappuccino',               'description' => '0.2l',   'price' => '2.20' ],
						[ 'id' => 'item_7',  'name' => 'Ice Coffee',               'description' => '0.3l',   'price' => '3.00' ],
						[ 'id' => 'item_8',  'name' => 'Nescafe',                  'description' => '0.15l',  'price' => '2.50' ],
						[ 'id' => 'item_9',  'name' => 'Topla čokolada',           'description' => '0.2l',   'price' => '2.80' ],
						[ 'id' => 'item_10', 'name' => 'Caffe Latte',              'description' => '0.3l',   'price' => '2.40' ],
						[ 'id' => 'item_11', 'name' => 'Organska kafa',            'description' => '0.05l',  'price' => '2.40' ],
						[ 'id' => 'item_12', 'name' => 'Espresso bez kofeina',     'description' => '0.05l',  'price' => '2.40' ],
					],
				],
				[
					'id'            => 'cat_2',
					'name'          => 'Bezalkoholna Pića',
					'icon'          => 'droplets',
					'subcategories' => [
						[
							'id'    => 'subcat_2_1',
							'name'  => 'Cijeđeni Sokovi',
							'items' => [
								[ 'id' => 'item_13', 'name' => 'Limunada',             'description' => '0.4l',  'price' => '3.00' ],
								[ 'id' => 'item_14', 'name' => 'Limunada s nanom',     'description' => '0.4l',  'price' => '3.20' ],
								[ 'id' => 'item_15', 'name' => 'Američka limunada',    'description' => '0.4l',  'price' => '3.20' ],
								[ 'id' => 'item_16', 'name' => 'Cijeđena pomorandža',  'description' => '0.3l',  'price' => '3.60' ],
								[ 'id' => 'item_17', 'name' => 'Cijeđeni ananas',      'description' => '0.3l',  'price' => '5.40' ],
								[ 'id' => 'item_18', 'name' => '100% cijeđeni sok',    'description' => '0.3l',  'price' => '4.10' ],
								[ 'id' => 'item_19', 'name' => 'Dodatak ananas',       'description' => '',       'price' => '1.00' ],
							],
						],
						[
							'id'    => 'subcat_2_2',
							'name'  => 'Sokovi',
							'items' => [
								[ 'id' => 'item_20', 'name' => 'Sok Pomorandža',  'description' => '0.2l',  'price' => '2.70' ],
								[ 'id' => 'item_21', 'name' => 'Sok Jabuka',      'description' => '0.2l',  'price' => '2.70' ],
								[ 'id' => 'item_22', 'name' => 'Sok Borovnica',   'description' => '0.2l',  'price' => '2.70' ],
								[ 'id' => 'item_23', 'name' => 'Sok Breskva',     'description' => '0.2l',  'price' => '2.70' ],
							],
						],
					],
					'items' => [],
				],
				[
					'id'            => 'cat_3',
					'name'          => 'Piva & Cider',
					'icon'          => 'beer',
					'subcategories' => [
						[
							'id'    => 'subcat_3_1',
							'name'  => 'Točena Piva',
							'items' => [
								[ 'id' => 'item_24', 'name' => 'Carlsberg 0.25',      'description' => 'Točeno',  'price' => '2.80' ],
								[ 'id' => 'item_25', 'name' => 'Carlsberg 0.50',      'description' => 'Točeno',  'price' => '4.80' ],
								[ 'id' => 'item_26', 'name' => 'Tuborg 0.3',          'description' => 'Točeno',  'price' => '2.60' ],
								[ 'id' => 'item_27', 'name' => 'Tuborg 0.5',          'description' => 'Točeno',  'price' => '4.40' ],
								[ 'id' => 'item_28', 'name' => 'Budweiser 0.3',       'description' => 'Točeno',  'price' => '2.90' ],
								[ 'id' => 'item_29', 'name' => 'Budweiser 0.5',       'description' => 'Točeno',  'price' => '4.50' ],
								[ 'id' => 'item_30', 'name' => 'Budweiser Dark 0.3',  'description' => 'Točeno',  'price' => '2.80' ],
								[ 'id' => 'item_31', 'name' => 'Budweiser Dark 0.5',  'description' => 'Točeno',  'price' => '4.50' ],
								[ 'id' => 'item_32', 'name' => 'Erdinger 0.3',        'description' => 'Točeno',  'price' => '3.60' ],
								[ 'id' => 'item_33', 'name' => 'Erdinger 0.5',        'description' => 'Točeno',  'price' => '5.10' ],
								[ 'id' => 'item_34', 'name' => 'Kronenbourg 0.3',     'description' => 'Točeno',  'price' => '2.80' ],
							],
						],
					],
					'items' => [],
				],
				[
					'id'            => 'cat_4',
					'name'          => 'Džin, Rum, Tequila',
					'icon'          => 'wine',
					'subcategories' => [
						[
							'id'    => 'subcat_4_1',
							'name'  => 'Džin',
							'items' => [
								[ 'id' => 'item_35', 'name' => 'Beefeater',       'description' => '0.04l',  'price' => '3.80' ],
								[ 'id' => 'item_36', 'name' => 'Beefeater 24',    'description' => '0.04l',  'price' => '5.10' ],
								[ 'id' => 'item_37', 'name' => 'Beefeater Pink',  'description' => '0.04l',  'price' => '3.90' ],
								[ 'id' => 'item_38', 'name' => 'Monkey 47',       'description' => '0.04l',  'price' => '7.60' ],
								[ 'id' => 'item_39', 'name' => 'Plymouth',        'description' => '0.04l',  'price' => '7.10' ],
								[ 'id' => 'item_40', 'name' => 'Hendrick\'s',     'description' => '0.04l',  'price' => '6.80' ],
							],
						],
						[
							'id'    => 'subcat_4_2',
							'name'  => 'Rum',
							'items' => [
								[ 'id' => 'item_41', 'name' => 'Havana Club Anejo 3Y',   'description' => '0.04l',  'price' => '3.80' ],
								[ 'id' => 'item_42', 'name' => 'Havana Club Anejo 7Y',   'description' => '0.04l',  'price' => '5.20' ],
								[ 'id' => 'item_43', 'name' => 'Captain Morgan Spiced',  'description' => '0.04l',  'price' => '4.20' ],
								[ 'id' => 'item_44', 'name' => 'Bacardi White',          'description' => '0.04l',  'price' => '3.80' ],
								[ 'id' => 'item_45', 'name' => 'Bumbu Original',         'description' => '0.04l',  'price' => '6.50' ],
							],
						],
						[
							'id'    => 'subcat_4_3',
							'name'  => 'Tequila',
							'items' => [
								[ 'id' => 'item_46', 'name' => 'Olmeca Gold',      'description' => '0.04l',  'price' => '4.30' ],
								[ 'id' => 'item_47', 'name' => 'Olmeca Blanco',    'description' => '0.04l',  'price' => '4.30' ],
								[ 'id' => 'item_48', 'name' => 'Altos Plata',      'description' => '0.04l',  'price' => '5.00' ],
								[ 'id' => 'item_49', 'name' => 'Altos Reposado',   'description' => '0.04l',  'price' => '5.00' ],
								[ 'id' => 'item_50', 'name' => 'Avion Silver',     'description' => '0.04l',  'price' => '7.00' ],
							],
						],
					],
					'items' => [],
				],
				[
					'id'            => 'cat_5',
					'name'          => 'Viski',
					'icon'          => 'glass-water',
					'subcategories' => [],
					'items'         => [
						[ 'id' => 'item_51', 'name' => 'Jack Daniel\'s Tennessee',  'description' => '0.04l',  'price' => '4.50' ],
						[ 'id' => 'item_52', 'name' => 'Jameson Irish',             'description' => '0.04l',  'price' => '4.20' ],
						[ 'id' => 'item_53', 'name' => 'Chivas Regal 12Y',          'description' => '0.04l',  'price' => '5.80' ],
						[ 'id' => 'item_54', 'name' => 'Glenfiddich 12Y',           'description' => '0.04l',  'price' => '6.50' ],
						[ 'id' => 'item_55', 'name' => 'Ballantine\'s Finest',      'description' => '0.04l',  'price' => '3.90' ],
						[ 'id' => 'item_56', 'name' => 'Maker\'s Mark',             'description' => '0.04l',  'price' => '5.40' ],
					],
				],
				[
					'id'            => 'cat_6',
					'name'          => 'Rakija',
					'icon'          => 'flask-conical',
					'subcategories' => [],
					'items'         => [
						[ 'id' => 'item_57', 'name' => 'Dunja Podrum Pevac',                    'description' => '0.03l',  'price' => '3.80' ],
						[ 'id' => 'item_58', 'name' => 'Kajsija Podrum Pevac',                  'description' => '0.03l',  'price' => '3.80' ],
						[ 'id' => 'item_59', 'name' => 'Šljiva 10-ka Podrum Pevac',             'description' => '0.03l',  'price' => '3.80' ],
						[ 'id' => 'item_60', 'name' => 'Zlatna Podrum Pevac 24-karatno zlato',  'description' => '0.03l',  'price' => '8.00' ],
						[ 'id' => 'item_61', 'name' => 'Stara Pesma Šljiva 5Y',                 'description' => '0.03l',  'price' => '3.90' ],
						[ 'id' => 'item_62', 'name' => 'Stara Pesma Šljiva 7Y',                 'description' => '0.03l',  'price' => '4.60' ],
						[ 'id' => 'item_63', 'name' => 'Stara Pesma Šljiva 12Y',                'description' => '0.03l',  'price' => '8.80' ],
						[ 'id' => 'item_64', 'name' => 'Stara Pesma Dunja Lux',                 'description' => '0.03l',  'price' => '4.60' ],
						[ 'id' => 'item_65', 'name' => 'Stara Pesma Kajsija Lux',               'description' => '0.03l',  'price' => '4.60' ],
						[ 'id' => 'item_66', 'name' => 'Stara Pesma Medeno',                    'description' => '0.03l',  'price' => '3.90' ],
						[ 'id' => 'item_67', 'name' => 'Stara Pesma Jabuka',                    'description' => '0.03l',  'price' => '3.90' ],
						[ 'id' => 'item_68', 'name' => 'Šljiva Stara Sokolova 5 God',           'description' => '0.03l',  'price' => '4.30' ],
					],
				],
			],
		];

		$posts = get_posts( [
			'post_type'      => 'job_listing',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
		] );

		if ( empty( $posts ) ) {
			wp_die( 'Nema objavljenih lokala. Prvo kreiraj jedan lokal.' );
		}

		$post_id      = $posts[0]->ID;
		$listing_name = $posts[0]->post_title;

		update_post_meta( $post_id, '_edm_menu_data', json_encode( $demo_menu, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );

		wp_safe_remote_get( add_query_arg( 'edm_msg', 'demo_created', admin_url( 'edit.php?post_type=job_listing&page=edm-tools' ) ) );

		wp_redirect( add_query_arg( [
			'post_type' => 'job_listing',
			'page'      => 'edm-tools',
			'edm_msg'   => 'demo_created',
		], admin_url( 'edit.php' ) ) );
		exit;
	}
}

new EDM_Tools();
