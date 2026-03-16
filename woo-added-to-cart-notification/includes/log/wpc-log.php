<?php
defined( 'ABSPATH' ) || exit;

register_activation_hook( defined( 'WOOAC_LITE' ) ? WOOAC_LITE : WOOAC_FILE, 'wooac_activate' );
register_deactivation_hook( defined( 'WOOAC_LITE' ) ? WOOAC_LITE : WOOAC_FILE, 'wooac_deactivate' );
add_action( 'admin_init', 'wooac_check_version' );

function wooac_check_version() {
	if ( ! empty( get_option( 'wooac_version' ) ) && ( get_option( 'wooac_version' ) < WOOAC_VERSION ) ) {
		wpc_log( 'wooac', 'upgraded' );
		update_option( 'wooac_version', WOOAC_VERSION, false );
	}
}

function wooac_activate() {
	wpc_log( 'wooac', 'installed' );
	update_option( 'wooac_version', WOOAC_VERSION, false );
}

function wooac_deactivate() {
	wpc_log( 'wooac', 'deactivated' );
}

if ( ! function_exists( 'wpc_log' ) ) {
	function wpc_log( $prefix, $action ) {
		$logs = get_option( 'wpc_logs', [] );
		$user = wp_get_current_user();

		if ( ! isset( $logs[ $prefix ] ) ) {
			$logs[ $prefix ] = [];
		}

		$logs[ $prefix ][] = [
			'time'   => current_time( 'mysql' ),
			'user'   => $user->display_name . ' (ID: ' . $user->ID . ')',
			'action' => $action
		];

		update_option( 'wpc_logs', $logs, false );
	}
}