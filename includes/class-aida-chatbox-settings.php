<?php
/**
 * Settings storage and sanitization for Aida Chatbox.
 *
 * Attribute names and defaults mirror app.aidasales.ir chatbox settings
 * (see AiDa_Front Chatbox.tsx script tag builder).
 *
 * @package Aida_Chatbox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Aida_Chatbox_Settings {

	/**
	 * Default getInfo prompt (same as Front fa locale).
	 */
	const DEFAULT_GET_INFO_TEXT = 'سلام من آیدا هستم، برای شروع گفتگو ابتدا موارد زیر را تکمیل کنید.';

	/**
	 * Default option values.
	 *
	 * @return array
	 */
	public static function defaults() {
		return array(
			'enabled'            => true,
			'api_key'            => '',
			'position'           => 'right',
			'bottom'             => 0,
			'bottom_mobile'      => 0,
			'side'               => 0,
			'side_mobile'        => 0,
			'initial_state'      => 'closed',
			'time_open_enabled'  => false,
			'time_open_seconds'  => 1,
			'time_open_once'     => false,
			'processing'         => false,
			'get_info'           => false,
			'get_info_text'      => '',
			'questions'          => array(),
			'color'              => '00af9e',
			'color_ai'           => 'f2f2f2',
			'settings_version'   => time(),
		);
	}

	/**
	 * Register hooks.
	 */
	public static function init() {
		// Reserved for future settings API hooks.
	}

	/**
	 * Get all settings merged with defaults (with legacy key migration).
	 *
	 * @return array
	 */
	public static function get() {
		$stored = get_option( AIDA_CHATBOX_OPTION, array() );
		if ( ! is_array( $stored ) ) {
			$stored = array();
		}

		$settings = array_merge( self::defaults(), $stored );

		// Migrate older installs that stored time_open as "-1"|seconds.
		if ( ! array_key_exists( 'time_open_enabled', $stored ) && isset( $stored['time_open'] ) ) {
			$legacy = (string) $stored['time_open'];
			if ( '-1' === $legacy || '' === $legacy ) {
				$settings['time_open_enabled'] = false;
				$settings['time_open_seconds'] = 1;
			} else {
				$settings['time_open_enabled'] = true;
				$settings['time_open_seconds'] = max( 1, (int) $legacy );
			}
		}

		// Clamp offsets to Front app range 0–5.
		foreach ( array( 'bottom', 'bottom_mobile', 'side', 'side_mobile' ) as $key ) {
			$settings[ $key ] = self::clamp_offset( $settings[ $key ] );
		}

		$settings['time_open_seconds'] = max( 1, (int) $settings['time_open_seconds'] );

		return $settings;
	}

	/**
	 * Get a single setting.
	 *
	 * @param string $key     Option key.
	 * @param mixed  $default Fallback when missing.
	 * @return mixed
	 */
	public static function get_value( $key, $default = null ) {
		$settings = self::get();
		if ( array_key_exists( $key, $settings ) ) {
			return $settings[ $key ];
		}
		return $default;
	}

	/**
	 * Persist settings and bump version for cache busting.
	 *
	 * @param array $input Raw input from the admin form.
	 * @return array Sanitized settings that were saved.
	 */
	public static function save( array $input ) {
		$clean                     = self::sanitize( $input );
		$clean['settings_version'] = time();
		update_option( AIDA_CHATBOX_OPTION, $clean, false );
		return $clean;
	}

	/**
	 * Clamp offset to Front slider range (0–5).
	 *
	 * @param mixed $value Raw value.
	 * @return int
	 */
	public static function clamp_offset( $value ) {
		if ( ! is_numeric( $value ) ) {
			return 0;
		}
		return (int) max( 0, min( 5, round( (float) $value ) ) );
	}

	/**
	 * Sanitize and normalize settings input.
	 *
	 * @param array $input Raw input.
	 * @return array
	 */
	public static function sanitize( array $input ) {
		$defaults = self::defaults();
		$clean    = $defaults;

		$clean['enabled'] = ! empty( $input['enabled'] );

		$clean['api_key'] = isset( $input['api_key'] )
			? sanitize_text_field( wp_unslash( $input['api_key'] ) )
			: '';

		$position = isset( $input['position'] )
			? strtolower( sanitize_text_field( wp_unslash( $input['position'] ) ) )
			: 'right';
		$clean['position'] = in_array( $position, array( 'left', 'right' ), true ) ? $position : 'right';

		foreach ( array( 'bottom', 'bottom_mobile', 'side', 'side_mobile' ) as $spacing_key ) {
			$clean[ $spacing_key ] = isset( $input[ $spacing_key ] )
				? self::clamp_offset( wp_unslash( $input[ $spacing_key ] ) )
				: 0;
		}

		$initial = isset( $input['initial_state'] )
			? strtolower( sanitize_text_field( wp_unslash( $input['initial_state'] ) ) )
			: 'closed';
		$clean['initial_state'] = in_array( $initial, array( 'open', 'closed' ), true ) ? $initial : 'closed';

		$clean['time_open_enabled'] = ! empty( $input['time_open_enabled'] );
		$seconds                    = isset( $input['time_open_seconds'] )
			? (int) wp_unslash( $input['time_open_seconds'] )
			: 1;
		$clean['time_open_seconds'] = max( 1, $seconds );
		$clean['time_open_once']    = ! empty( $input['time_open_once'] ) && $clean['time_open_enabled'];

		$clean['processing'] = ! empty( $input['processing'] );
		$clean['get_info']   = ! empty( $input['get_info'] );

		$clean['get_info_text'] = isset( $input['get_info_text'] )
			? sanitize_textarea_field( wp_unslash( $input['get_info_text'] ) )
			: '';

		$questions = array();
		if ( isset( $input['questions'] ) ) {
			if ( is_array( $input['questions'] ) ) {
				$raw_list = $input['questions'];
			} else {
				$raw     = wp_unslash( $input['questions'] );
				$decoded = json_decode( $raw, true );
				if ( is_array( $decoded ) ) {
					$raw_list = $decoded;
				} else {
					$raw_list = preg_split( '/\r\n|\r|\n/', $raw );
				}
			}

			foreach ( (array) $raw_list as $question ) {
				$question = sanitize_text_field( $question );
				if ( '' !== $question ) {
					$questions[] = $question;
				}
			}
			$questions = array_slice( $questions, 0, 3 );
		}
		$clean['questions'] = $questions;

		$clean['color']    = self::sanitize_hex( isset( $input['color'] ) ? $input['color'] : $defaults['color'], $defaults['color'] );
		$clean['color_ai'] = self::sanitize_hex( isset( $input['color_ai'] ) ? $input['color_ai'] : $defaults['color_ai'], $defaults['color_ai'] );

		if ( isset( $input['settings_version'] ) && is_numeric( $input['settings_version'] ) ) {
			$clean['settings_version'] = (int) $input['settings_version'];
		}

		return $clean;
	}

	/**
	 * Normalize a hex color without # (lowercase, like Front toScriptColorValue).
	 *
	 * @param string $raw      Raw color.
	 * @param string $fallback Fallback hex without #.
	 * @return string
	 */
	public static function sanitize_hex( $raw, $fallback ) {
		$value = strtolower( ltrim( sanitize_text_field( wp_unslash( (string) $raw ) ), '#' ) );
		if ( preg_match( '/^[0-9a-f]{3}$/', $value ) ) {
			$value = $value[0] . $value[0] . $value[1] . $value[1] . $value[2] . $value[2];
		}
		if ( ! preg_match( '/^[0-9a-f]{6}$/', $value ) ) {
			return strtolower( ltrim( $fallback, '#' ) );
		}
		return $value;
	}

	/**
	 * data-timeOpen value: "-1" when disabled, otherwise seconds (Front behavior).
	 *
	 * @param array $settings Settings.
	 * @return string
	 */
	public static function time_open_attribute( array $settings ) {
		if ( empty( $settings['time_open_enabled'] ) ) {
			return '-1';
		}
		return (string) max( 1, (int) $settings['time_open_seconds'] );
	}

	/**
	 * Map stored settings to chatbox data-* attributes (Front script tag parity).
	 *
	 * @param array|null $settings Optional settings override.
	 * @return array Attribute name => value.
	 */
	public static function to_data_attributes( $settings = null ) {
		$settings = is_array( $settings ) ? array_merge( self::defaults(), $settings ) : self::get();

		$get_info_text = '';
		if ( ! empty( $settings['get_info'] ) ) {
			$get_info_text = trim( (string) $settings['get_info_text'] );
			if ( '' === $get_info_text ) {
				$get_info_text = self::DEFAULT_GET_INFO_TEXT;
			}
		}

		return array(
			'data-aida-api-key'     => (string) $settings['api_key'],
			'data-position-chatbox' => (string) $settings['position'],
			'data-bottom'           => (string) self::clamp_offset( $settings['bottom'] ),
			'data-bottom-mobile'    => (string) self::clamp_offset( $settings['bottom_mobile'] ),
			'data-side'             => (string) self::clamp_offset( $settings['side'] ),
			'data-side-mobile'      => (string) self::clamp_offset( $settings['side_mobile'] ),
			'data-initial-state'    => (string) $settings['initial_state'],
			'data-timeOpen'         => self::time_open_attribute( $settings ),
			'data-timeOpenOnce'     => ! empty( $settings['time_open_once'] ) && ! empty( $settings['time_open_enabled'] ) ? 'true' : 'false',
			'data-processing'       => ! empty( $settings['processing'] ) ? 'true' : 'false',
			'data-getInfo'          => ! empty( $settings['get_info'] ) ? 'true' : 'false',
			'data-getInfo-text'     => $get_info_text,
			'data-color'            => (string) $settings['color'],
			'data-color-ai'         => (string) $settings['color_ai'],
			'data-questions'        => wp_json_encode(
				array_values( (array) $settings['questions'] ),
				JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
			),
		);
	}

	/**
	 * Public config payload for the frontend loader / REST API.
	 *
	 * @return array
	 */
	public static function public_config() {
		$settings = self::get();

		return array(
			'enabled'    => ! empty( $settings['enabled'] ) && '' !== $settings['api_key'],
			'scriptUrl'  => AIDA_CHATBOX_SCRIPT_URL,
			'version'    => (int) $settings['settings_version'],
			'attributes' => self::to_data_attributes( $settings ),
		);
	}
}
