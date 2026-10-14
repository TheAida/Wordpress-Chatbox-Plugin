<?php
/**
 * Bilingual (English / Persian) text-domain loading.
 *
 * @package Aida_Chatbox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Aida_Chatbox_I18n {

	const DOMAIN = 'aida-chatbox';

	/**
	 * Register locale filters and load translations.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'load_textdomain' ), 0 );
		add_filter( 'all_plugins', array( __CLASS__, 'translate_plugin_meta' ) );
	}

	/**
	 * Resolve UI locale from WordPress site / user language.
	 *
	 * @return string Locale code (e.g. en_US, fa_IR).
	 */
	public static function get_ui_locale() {
		$locale = function_exists( 'determine_locale' ) ? determine_locale() : get_locale();
		if ( 0 === strpos( (string) $locale, 'fa' ) ) {
			return 'fa_IR';
		}

		return $locale ? $locale : 'en_US';
	}

	/**
	 * Whether the plugin UI should render RTL.
	 *
	 * @return bool
	 */
	public static function is_rtl() {
		return 'fa_IR' === self::get_ui_locale() || ( function_exists( 'is_rtl' ) && is_rtl() );
	}

	/**
	 * Load English source or Persian translations.
	 */
	public static function load_textdomain() {
		$locale = self::get_ui_locale();
		$rel    = dirname( plugin_basename( AIDA_CHATBOX_FILE ) ) . '/languages';

		// Always register the domain (Plugin Check / WP.org expectation).
		load_plugin_textdomain( self::DOMAIN, false, $rel );

		// Source language: keep English msgids as-is.
		if ( 'en_US' === $locale || 0 === strpos( $locale, 'en_' ) ) {
			return;
		}

		$base = AIDA_CHATBOX_PATH . 'languages/' . self::DOMAIN . '-' . $locale;

		// Prefer .l10n.php (WP 6.5+) then .mo for locales we ship.
		if ( file_exists( $base . '.l10n.php' ) ) {
			self::load_php_translations( $base . '.l10n.php' );
		} elseif ( file_exists( $base . '.mo' ) ) {
			load_textdomain( self::DOMAIN, $base . '.mo' );
		}
	}

	/**
	 * Load a WordPress-style .l10n.php file on any supported WP version.
	 *
	 * @param string $file Absolute path to .l10n.php.
	 * @return bool
	 */
	private static function load_php_translations( $file ) {
		// Native loader when available.
		if ( function_exists( 'load_textdomain' ) ) {
			$native = load_textdomain( self::DOMAIN, $file );
			if ( $native ) {
				return true;
			}
		}

		$data = include $file;
		if ( ! is_array( $data ) || empty( $data['messages'] ) || ! is_array( $data['messages'] ) ) {
			return false;
		}

		if ( ! class_exists( 'Gettext_Translations', false ) ) {
			$pomo = ABSPATH . WPINC . '/pomo/translations.php';
			if ( ! file_exists( $pomo ) ) {
				return false;
			}
			require_once $pomo;
		}

		if ( ! class_exists( 'Translation_Entry', false ) ) {
			$entry = ABSPATH . WPINC . '/pomo/entry.php';
			if ( file_exists( $entry ) ) {
				require_once $entry;
			}
		}

		$translations = new Gettext_Translations();
		foreach ( $data['messages'] as $original => $translated ) {
			if ( '' === $original ) {
				continue;
			}
			if ( is_array( $translated ) ) {
				$translations->add_entry(
					new Translation_Entry(
						array(
							'singular'     => $original,
							'translations' => $translated,
						)
					)
				);
			} else {
				$translations->add_entry(
					new Translation_Entry(
						array(
							'singular'     => $original,
							'translations' => array( (string) $translated ),
						)
					)
				);
			}
		}

		$GLOBALS['l10n'][ self::DOMAIN ] = $translations;
		return true;
	}

	/**
	 * Translate plugin name/description on the Plugins screen when UI is Persian.
	 *
	 * @param array $plugins Installed plugins list.
	 * @return array
	 */
	public static function translate_plugin_meta( $plugins ) {
		if ( ! self::is_rtl() ) {
			return $plugins;
		}

		$basename = plugin_basename( AIDA_CHATBOX_FILE );
		if ( ! isset( $plugins[ $basename ] ) ) {
			return $plugins;
		}

		$plugins[ $basename ]['Name']        = __( 'Aida Chatbox', 'aida-chatbox' );
		$plugins[ $basename ]['Description'] = __(
			'Embed Aida — the AI sales assistant — on your WordPress site. Configure the chatbox from the admin; no manual script-tag edits.',
			'aida-chatbox'
		);

		return $plugins;
	}
}
