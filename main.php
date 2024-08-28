<?php
/*
  Plugin Name: Update test v2.0.0
  Version: 2.0.0
  Update URI: updatetest
 */

$time_not_changed = isset( $current->last_checked ) && $timeout > ( time() - $current->last_checked );
// これを追加
$time_not_changed = false;

if ( $time_not_changed && ! $extra_stats ) {
	$plugin_changed = false;
}

define( 'MY_PLUGIN_UPDATE_URL', 'https://api.github.com/repos/rish-inc/updatetest/releases/latest' );

function my_plugin_update_plugin( $update, $plugin_data ) {
	$response = wp_remote_get( MY_PLUGIN_UPDATE_URL );
	if( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return $update;
	}
	$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
	$new_version   = isset( $response_body['tag_name'] ) ? $response_body['tag_name'] : null;
	$package       = isset( $response_body['assets'][0]['browser_download_url'] ) ? $response_body['assets'][0]['browser_download_url'] : null;
	return array(
		'version'     => $plugin_data['Version'], // 現在のバージョン
		'new_version' => $new_version,            // 最新のバージョン
		'package'     => $package,                // zipファイルパッケージのURL
	);
}
add_filter( 'update_plugins_updatetest', 'my_plugin_update_plugin', 10, 2 );
