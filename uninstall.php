<?php
/**
 * Uninstall cleanup for Aida Chatbox.
 *
 * @package Aida_Chatbox
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'aida_chatbox_settings' );
