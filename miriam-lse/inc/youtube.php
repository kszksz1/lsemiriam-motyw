<?php
/**
 * Najnowsze filmy z kanału YouTube LSE Miriam (z kanału RSS, bez klucza API).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MIRIAM_YT_CHANNEL', 'UCzfyN6_-sBThBEG_ISlBdXw' );

function miriam_latest_videos( $limit = 6 ) {
	$key    = 'miriam_yt_' . MIRIAM_YT_CHANNEL;
	$videos = get_transient( $key );
	if ( false === $videos ) {
		$videos = array();
		$res    = wp_remote_get( 'https://www.youtube.com/feeds/videos.xml?channel_id=' . MIRIAM_YT_CHANNEL, array( 'timeout' => 8 ) );
		if ( ! is_wp_error( $res ) && 200 === (int) wp_remote_retrieve_response_code( $res ) && function_exists( 'simplexml_load_string' ) ) {
			$prev = libxml_use_internal_errors( true );
			$xml  = simplexml_load_string( wp_remote_retrieve_body( $res ) );
			libxml_use_internal_errors( $prev );
			if ( $xml ) {
				foreach ( $xml->entry as $entry ) {
					$yt = $entry->children( 'http://www.youtube.com/xml/schemas/2015' );
					$id = (string) $yt->videoId;
					if ( $id ) {
						$videos[] = array( 'id' => $id, 'title' => (string) $entry->title );
					}
				}
			}
		}
		// Cache success for 6h, failure for 30 min.
		set_transient( $key, $videos, $videos ? 6 * HOUR_IN_SECONDS : 30 * MINUTE_IN_SECONDS );
	}
	return array_slice( (array) $videos, 0, $limit );
}

/**
 * Miniatura filmu zapisana na serwerze strony (przeglądarka odwiedzającego
 * nie łączy się z YouTube, dopóki nie kliknie „odtwórz”).
 */
function miriam_yt_thumb( $id ) {
	$id     = preg_replace( '/[^A-Za-z0-9_-]/', '', $id );
	$remote = 'https://i.ytimg.com/vi/' . $id . '/hqdefault.jpg';
	$up     = wp_upload_dir();
	if ( ! empty( $up['error'] ) ) {
		return $remote;
	}
	$dir  = trailingslashit( $up['basedir'] ) . 'miriam-yt';
	$file = $dir . '/' . $id . '.jpg';
	$url  = trailingslashit( $up['baseurl'] ) . 'miriam-yt/' . $id . '.jpg';
	if ( file_exists( $file ) && filesize( $file ) > 1000 ) {
		return $url;
	}
	if ( get_transient( 'miriam_ytt_fail_' . $id ) ) {
		return '';
	}
	wp_mkdir_p( $dir );
	$res = wp_remote_get( $remote, array( 'timeout' => 6 ) );
	if ( ! is_wp_error( $res ) && 200 === (int) wp_remote_retrieve_response_code( $res ) ) {
		$body = wp_remote_retrieve_body( $res );
		if ( strlen( $body ) > 1000 && false !== file_put_contents( $file, $body ) ) { // phpcs:ignore
			return $url;
		}
	}
	set_transient( 'miriam_ytt_fail_' . $id, 1, HOUR_IN_SECONDS );
	return '';
}
