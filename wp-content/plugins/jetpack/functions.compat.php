<?php

if ( !function_exists( 'rawurlencode_deep' ) ) :
/**
 * Navigates through an array and raw encodes the values to be used in a URL.
 *
 * @since WordPress 3.4.0
 *
 * @param array|string $value The array or string to be encoded.
 * @return array|string $value The encoded array (or string from the callback).
 */
function rawurlencode_deep( $value ) {
	return is_array( $value ) ? array_map( 'rawurlencode_deep', $value ) : rawurlencode( $value );
}
endif;

if ( !function_exists( 'get_youtube_id' ) ) :
/**
* @param $url Can be just the $url or the whole $atts array
* @return bool|mixed The Youtube video ID
*/
function get_youtube_id( $url ) {
	// Do we have an $atts array?  Get first att
	if ( is_array( $url ) )
		$url = $url[0];

	$url = youtube_sanitize_url( $url );
	$url = parse_url( $url );
	$id  = false;

	if ( ! isset( $url['query'] ) )
		return false;

	parse_str( $url['query'], $qargs );

	if ( ! isset( $qargs['v'] ) && ! isset( $qargs['list'] ) )
		return false;

	if ( isset( $qargs['list'] ) )
		$id = preg_replace( '|[^_a-z0-9-]|i', '', $qargs['list'] );

	if ( empty( $id ) )
		$id = preg_replace( '|[^_a-z0-9-]|i', '', $qargs['v'] );

	return $id;
}
endif;

if ( !function_exists( 'youtube_sanitize_url' ) ) :
/**
* Normalizes a YouTube URL to include a v= parameter and a query string free of encoded ampersands.
*
* @param string $url
* @return string The normalized URL
*/
function youtube_sanitize_url( $url ) {
	$url = trim( $url, ' "' );
	$url = trim( $url );
	$url = str_replace( array( 'youtu.be/', '/v/', '#!v=', '&amp;', '&#038;', 'playlist' ), array( 'youtu.be/?v=', '/?v=', '?v=', '&', '&', 'videoseries' ), $url );

	// Replace any extra question marks with ampersands - the result of a URL like "http://www.youtube.com/v/9FhMMmqzbD8?fs=1&hl=en_US" being passed in.
	$query_string_start = strpos( $url, "?" );

	if ( false !== $query_string_start ) {
		$url = substr( $url, 0, $query_string_start + 1 ) . str_replace( "?", "&", substr( $url, $query_string_start + 1 ) );
	}

	return $url;
}
endif;
