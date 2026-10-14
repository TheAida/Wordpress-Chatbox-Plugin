<?php
/**
 * WordPress admin settings page for Aida Chatbox.
 *
 * @package Aida_Chatbox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Aida_Chatbox_Admin {

	const PAGE_SLUG = 'aida-chatbox';

	/**
	 * Register admin hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'handle_save' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( AIDA_CHATBOX_FILE ), array( __CLASS__, 'action_links' ) );
		add_filter( 'plugin_row_meta', array( __CLASS__, 'row_meta' ), 10, 2 );
	}

	/**
	 * Settings link on the Plugins screen.
	 *
	 * @param array $links Existing links.
	 * @return array
	 */
	public static function action_links( $links ) {
		$url = admin_url( 'admin.php?page=' . self::PAGE_SLUG );
		array_unshift(
			$links,
			'<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'aida-chatbox' ) . '</a>'
		);
		return $links;
	}

	/**
	 * Extra meta links under the plugin name.
	 *
	 * @param array  $links Plugin row meta.
	 * @param string $file  Plugin basename.
	 * @return array
	 */
	public static function row_meta( $links, $file ) {
		if ( plugin_basename( AIDA_CHATBOX_FILE ) !== $file ) {
			return $links;
		}

		$links[] = '<a href="https://aidasales.ir" target="_blank" rel="noopener noreferrer">' . esc_html__( 'About Aida', 'aida-chatbox' ) . '</a>';
		$links[] = '<a href="https://aidasales.ir/i" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Live demos', 'aida-chatbox' ) . '</a>';

		return $links;
	}

	/**
	 * Top-level admin menu (Dashicon — custom PNG was overflowing the sidebar).
	 */
	public static function register_menu() {
		add_menu_page(
			__( 'Aida Chatbox', 'aida-chatbox' ),
			__( 'Aida Chatbox', 'aida-chatbox' ),
			'manage_options',
			self::PAGE_SLUG,
			array( __CLASS__, 'render_page' ),
			'dashicons-format-chat',
			58
		);
	}

	/**
	 * Admin CSS/JS only on our settings page.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public static function enqueue_assets( $hook ) {
		if ( 'toplevel_page_' . self::PAGE_SLUG !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'aida-chatbox-admin',
			AIDA_CHATBOX_URL . 'assets/css/admin.css',
			array(),
			AIDA_CHATBOX_VERSION
		);

		wp_enqueue_script(
			'aida-chatbox-admin',
			AIDA_CHATBOX_URL . 'assets/js/admin.js',
			array(),
			AIDA_CHATBOX_VERSION,
			true
		);
	}

	/**
	 * Process settings form POST.
	 */
	public static function handle_save() {
		if ( ! isset( $_POST['aida_chatbox_save'] ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		check_admin_referer( 'aida_chatbox_save_settings', 'aida_chatbox_nonce' );

		$raw = isset( $_POST['aida_chatbox'] ) && is_array( $_POST['aida_chatbox'] )
			? wp_unslash( $_POST['aida_chatbox'] )
			: array();

		// Checkboxes: missing keys mean unchecked.
		$checkboxes = array( 'enabled', 'time_open_enabled', 'time_open_once', 'processing', 'get_info' );
		foreach ( $checkboxes as $key ) {
			if ( ! isset( $raw[ $key ] ) ) {
				$raw[ $key ] = false;
			}
		}

		Aida_Chatbox_Settings::save( $raw );
		Aida_Chatbox_Cache::purge();

		add_settings_error(
			'aida_chatbox',
			'aida_chatbox_saved',
			__( 'Settings saved. Page caches were purged so the chatbox updates immediately.', 'aida-chatbox' ),
			'success'
		);
	}

	/**
	 * Render the settings page.
	 */
	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings       = Aida_Chatbox_Settings::get();
		$questions_text = implode( "\n", (array) $settings['questions'] );
		$is_rtl         = Aida_Chatbox_I18n::is_rtl();
		$logo_url       = AIDA_CHATBOX_URL . 'assets/images/aida-logo.png';
		$face_url       = AIDA_CHATBOX_URL . 'assets/images/aida-face.png';

		require AIDA_CHATBOX_PATH . 'admin/views/settings-page.php';
	}
}
