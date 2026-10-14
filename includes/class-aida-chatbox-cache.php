<?php
/**
 * Purge popular WordPress page-cache plugins when chatbox settings change.
 *
 * @package Aida_Chatbox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Aida_Chatbox_Cache {

	/**
	 * Attempt to clear known cache plugins and WordPress object cache.
	 *
	 * Config changes are primarily delivered via an uncached REST endpoint
	 * (see Aida_Chatbox_Rest). This purge still helps when HTML/CDN layers
	 * cache any leftover markup or when plugins delay JS execution.
	 */
	public static function purge() {
		// WP Rocket.
		if ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
		}
		if ( function_exists( 'rocket_clean_minify' ) ) {
			rocket_clean_minify();
		}

		// LiteSpeed Cache.
		if ( class_exists( 'LiteSpeed_Cache_API' ) && method_exists( 'LiteSpeed_Cache_API', 'purge_all' ) ) {
			LiteSpeed_Cache_API::purge_all();
		}
		do_action( 'litespeed_purge_all' );

		// W3 Total Cache.
		if ( function_exists( 'w3tc_flush_all' ) ) {
			w3tc_flush_all();
		}

		// WP Super Cache.
		if ( function_exists( 'wp_cache_clear_cache' ) ) {
			wp_cache_clear_cache();
		}

		// WP Fastest Cache.
		if ( function_exists( 'wpfc_clear_all_cache' ) ) {
			wpfc_clear_all_cache( true );
		}
		if ( isset( $GLOBALS['wp_fastest_cache'] ) && method_exists( $GLOBALS['wp_fastest_cache'], 'deleteCache' ) ) {
			$GLOBALS['wp_fastest_cache']->deleteCache( true );
		}

		// Autoptimize.
		if ( class_exists( 'autoptimizeCache' ) && method_exists( 'autoptimizeCache', 'clearall' ) ) {
			autoptimizeCache::clearall();
		}

		// SG Optimizer.
		if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
			sg_cachepress_purge_cache();
		}

		// Hummingbird.
		do_action( 'wphb_clear_page_cache' );

		// Cloudflare plugin (if present).
		if ( class_exists( 'CF\WordPress\Hooks' ) ) {
			do_action( 'cloudflare_purge_everything' );
		}

		// SiteGround / generic.
		if ( function_exists( 'wp_cache_flush' ) ) {
			wp_cache_flush();
		}

		/**
		 * Fires after Aida Chatbox requests a cache purge.
		 *
		 * Third-party cache layers can listen to this hook.
		 */
		do_action( 'aida_chatbox_cache_purged' );
	}

	/**
	 * Filters WP Rocket to keep the Aida loader and CDN script out of
	 * delay/minify/combine, which can strip or defer data attributes.
	 */
	public static function register_rocket_exclusions() {
		add_filter( 'rocket_exclude_js', array( __CLASS__, 'exclude_aida_js' ) );
		add_filter( 'rocket_exclude_defer_js', array( __CLASS__, 'exclude_aida_js' ) );
		add_filter( 'rocket_delay_js_exclusions', array( __CLASS__, 'exclude_aida_js_patterns' ) );
		add_filter( 'rocket_exclude_js_files_from_defer', array( __CLASS__, 'exclude_aida_js' ) );
		add_filter( 'script_loader_tag', array( __CLASS__, 'mark_rocket_bypass' ), 10, 2 );
	}

	/**
	 * @param array $excluded Excluded JS URLs/paths.
	 * @return array
	 */
	public static function exclude_aida_js( $excluded ) {
		if ( ! is_array( $excluded ) ) {
			$excluded = array();
		}
		$excluded[] = 'cdn.aidasales.ir/chatbox/(.*)';
		$excluded[] = 'aida-chatbot';
		$excluded[] = 'aida-chatbox';
		$excluded[] = '/aida-chatbox/assets/js/loader.js';
		return $excluded;
	}

	/**
	 * @param array $patterns Delay-JS exclusion patterns.
	 * @return array
	 */
	public static function exclude_aida_js_patterns( $patterns ) {
		if ( ! is_array( $patterns ) ) {
			$patterns = array();
		}
		$patterns[] = 'aida-chatbox';
		$patterns[] = 'aida-chatbot';
		$patterns[] = 'cdn\.aidasales\.ir/chatbox';
		$patterns[] = 'aidaChatboxLoader';
		return $patterns;
	}

	/**
	 * Add data-no-optimize / rocket attributes so optimizers skip our loader.
	 *
	 * @param string $tag    Script HTML tag.
	 * @param string $handle Script handle.
	 * @return string
	 */
	public static function mark_rocket_bypass( $tag, $handle ) {
		if ( 'aida-chatbox-loader' !== $handle ) {
			return $tag;
		}

		$attrs = ' data-no-optimize="1" data-cfasync="false"';
		if ( false === strpos( $tag, 'data-no-optimize' ) ) {
			$tag = str_replace( ' src', $attrs . ' src', $tag );
		}
		return $tag;
	}
}
