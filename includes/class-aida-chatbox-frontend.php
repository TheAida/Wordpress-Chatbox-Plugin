<?php
/**
 * Front-end loader injection for Aida Chatbox.
 *
 * @package Aida_Chatbox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Aida_Chatbox_Frontend {

	/**
	 * Register hooks.
	 */
	public static function init() {
		Aida_Chatbox_Cache::register_rocket_exclusions();
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
	}

	/**
	 * Enqueue the thin loader that fetches live config and injects the CDN script.
	 */
	public static function enqueue() {
		if ( is_admin() ) {
			return;
		}

		$settings = Aida_Chatbox_Settings::get();

		if ( empty( $settings['enabled'] ) || '' === $settings['api_key'] ) {
			return;
		}

		/**
		 * Filter whether the chatbox loader should load on this request.
		 *
		 * @param bool  $should_load Default true when enabled + API key set.
		 * @param array $settings    Current settings.
		 */
		$should_load = apply_filters( 'aida_chatbox_should_load', true, $settings );
		if ( ! $should_load ) {
			return;
		}

		$version = (string) (int) $settings['settings_version'];

		wp_enqueue_script(
			'aida-chatbox-loader',
			AIDA_CHATBOX_URL . 'assets/js/loader.js',
			array(),
			$version,
			true
		);

		wp_localize_script(
			'aida-chatbox-loader',
			'aidaChatboxBoot',
			array(
				'configUrl' => Aida_Chatbox_Rest::config_url(),
				'version'   => (int) $settings['settings_version'],
			)
		);
	}
}
