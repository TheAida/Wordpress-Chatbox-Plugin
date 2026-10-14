<?php
/**
 * Uncached REST endpoint that serves live chatbox configuration.
 *
 * Page-cache plugins (WP Rocket, etc.) freeze HTML. Serving config from this
 * endpoint means settings changes apply immediately without waiting for a
 * full cache rebuild.
 *
 * @package Aida_Chatbox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Aida_Chatbox_Rest {

	const NAMESPACE = 'aida-chatbox/v1';
	const ROUTE     = '/config';

	/**
	 * Register routes.
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
	}

	/**
	 * Register GET /wp-json/aida-chatbox/v1/config
	 */
	public static function register_routes() {
		register_rest_route(
			self::NAMESPACE,
			self::ROUTE,
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( __CLASS__, 'get_config' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Return public config with aggressive no-cache headers.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	public static function get_config( WP_REST_Request $request ) {
		$config   = Aida_Chatbox_Settings::public_config();
		$response = new WP_REST_Response( $config, 200 );

		$response->header( 'Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0' );
		$response->header( 'Pragma', 'no-cache' );
		$response->header( 'Expires', '0' );
		$response->header( 'X-Aida-Chatbox-Version', (string) $config['version'] );

		/**
		 * Allow hosts / CDNs to treat this route as uncacheable.
		 *
		 * @param WP_REST_Response $response Response object.
		 * @param array            $config   Public config payload.
		 */
		return apply_filters( 'aida_chatbox_rest_config_response', $response, $config );
	}

	/**
	 * Absolute URL for the config endpoint.
	 *
	 * @return string
	 */
	public static function config_url() {
		return esc_url_raw( rest_url( self::NAMESPACE . self::ROUTE ) );
	}
}
