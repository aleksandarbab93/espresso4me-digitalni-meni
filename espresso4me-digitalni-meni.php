<?php
/**
 * Plugin Name: espresso4me Digitalni Meni
 * Plugin URI:  https://espresso4.me
 * Description: Digitalni meni sa QR kodom za lokale registrovane na espresso4.me portalu.
 * Version:     1.0.0
 * Author:      espresso4.me
 * Text Domain: espresso4me-meni
 * License:     GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EDM_VERSION',    '1.0.0' );
define( 'EDM_PLUGIN_FILE', __FILE__ );
define( 'EDM_PLUGIN_DIR',  plugin_dir_path( __FILE__ ) );
define( 'EDM_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );

require_once EDM_PLUGIN_DIR . 'includes/class-edm-admin.php';
require_once EDM_PLUGIN_DIR . 'includes/class-edm-public.php';
require_once EDM_PLUGIN_DIR . 'includes/class-edm-rest.php';
require_once EDM_PLUGIN_DIR . 'includes/class-edm-tools.php';
require_once EDM_PLUGIN_DIR . 'includes/class-edm-landing.php';

register_activation_hook( EDM_PLUGIN_FILE, 'edm_activate' );
register_deactivation_hook( EDM_PLUGIN_FILE, 'edm_deactivate' );

function edm_activate() {
	EDM_Public::add_rewrite_rules();
	EDM_Landing::add_rewrite_rules();
	flush_rewrite_rules();
	EDM_Landing::maybe_add_nav_menu_item();
}

function edm_deactivate() {
	flush_rewrite_rules();
}

new EDM_Admin();
new EDM_Public();
new EDM_Rest();
