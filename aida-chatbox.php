<?php
/**
 * Plugin Name:       Aida Chatbox
 * Plugin URI:        https://github.com/TheAida/Wordpress-Chatbox-Plugin
 * Description:       Embed Aida — the AI sales assistant — on your WordPress site. Configure the chatbox from the admin; no manual script-tag edits.
 * Version:           1.0.1
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Aida Sales
 * Author URI:        https://aidasales.ir
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       aida-chatbox
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AIDA_CHATBOX_VERSION', '1.0.1' );
define( 'AIDA_CHATBOX_FILE', __FILE__ );
define( 'AIDA_CHATBOX_PATH', plugin_dir_path( __FILE__ ) );
define( 'AIDA_CHATBOX_URL', plugin_dir_url( __FILE__ ) );
define( 'AIDA_CHATBOX_OPTION', 'aida_chatbox_settings' );
define( 'AIDA_CHATBOX_SCRIPT_URL', 'https://cdn.aidasales.ir/chatbox/aida-chatbot.min.fa.js' );

require_once AIDA_CHATBOX_PATH . 'includes/class-aida-chatbox-settings.php';
require_once AIDA_CHATBOX_PATH . 'includes/class-aida-chatbox-i18n.php';
require_once AIDA_CHATBOX_PATH . 'includes/class-aida-chatbox-cache.php';
require_once AIDA_CHATBOX_PATH . 'includes/class-aida-chatbox-rest.php';
require_once AIDA_CHATBOX_PATH . 'includes/class-aida-chatbox-frontend.php';
require_once AIDA_CHATBOX_PATH . 'includes/class-aida-chatbox-admin.php';

/**
 * Bootstrap the plugin.
 */
function aida_chatbox_init() {
	Aida_Chatbox_I18n::init();
	Aida_Chatbox_Settings::init();
	Aida_Chatbox_Rest::init();
	Aida_Chatbox_Frontend::init();

	if ( is_admin() ) {
		Aida_Chatbox_Admin::init();
	}
}
add_action( 'plugins_loaded', 'aida_chatbox_init' );

/**
 * Activation: seed defaults.
 */
function aida_chatbox_activate() {
	if ( false === get_option( AIDA_CHATBOX_OPTION ) ) {
		update_option( AIDA_CHATBOX_OPTION, Aida_Chatbox_Settings::defaults() );
	}
}
register_activation_hook( __FILE__, 'aida_chatbox_activate' );
