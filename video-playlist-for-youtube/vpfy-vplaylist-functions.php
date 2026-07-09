<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/*Register css and Js for Admin/back end*/
add_action('admin_enqueue_scripts','vpfy_vplay_admin_enque');
function vpfy_vplay_admin_enque(){
	wp_register_script('vpfyt-vplay-repeatmeta', VID_PLYLST_PLUGINURL.'includes/js/vpfy-vplay-metabox.js', array('jquery'), time(), false );
	wp_register_script('vpfyt-vplay-settngpg', VID_PLYLST_PLUGINURL.'includes/js/vpfy-settings-page.js', array('jquery'), time(), false );

	wp_register_style('vpfy-vplay-adminstyle', VID_PLYLST_PLUGINURL.'includes/css/vpfy-vplaylist-admin-style.css', array(), time(), 'all' );
	wp_register_style('vpfy-vplay-settings', VID_PLYLST_PLUGINURL.'includes/css/vpfy-settings-page.css', array(), time(), 'all' );
}

/*Register CSS and JS for front end*/
add_action('wp_enqueue_scripts','vpfy_vplaylist_frontend_display_gallery');
function vpfy_vplaylist_frontend_display_gallery(){
	if (!empty(get_option('vpfy_vid_length')) && get_option('vpfy_vid_length') == 1){
    	$showlen = 'yes';
	} 
  	else{
    	$showlen = 'no';
  	}       
	
	wp_register_script('vpfy-playlist-min', VID_PLYLST_PLUGINURL.'includes/js/vpfy-vplay-playlist-min.js', array('jquery'), time(), false);
	wp_register_script('vpfy-playlist-video', VID_PLYLST_PLUGINURL.'includes/js/vpfy-vplay-playlist-video.js', array('jquery'), time(), false );
	wp_register_script('vpfy-unitegallery-video', VID_PLYLST_PLUGINURL.'includes/js/vpfy-unitegallery.min.js', array('jquery'), time(), true );

	wp_localize_script( 'vpfy-playlist-video', 'yt_ajx_obj',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'vpfyshow_length' => $showlen ) );	
	
	wp_register_style('vpfy-vplay-galcss', VID_PLYLST_PLUGINURL.'includes/css/vpfu-vplaylist-gallery.css', array(), time(), 'all' );
	wp_register_style('vpfy-playlist-ryt-no-thumb', VID_PLYLST_PLUGINURL.'includes/css/vpfy-vplaylist-skin-right-no-thumb.css', array(), time(), 'all' );
	wp_register_style('vpfy-playlist-ryt-thumb', VID_PLYLST_PLUGINURL.'includes/css/vpfy-vplaylist-skin-right-thumb.css', array(), time(), 'all' );
	wp_register_style('vpfy-playlist-ryt-ttl-only', VID_PLYLST_PLUGINURL.'includes/css/vpfy-vplaylist-skin-right-title-only.css', array(), time(), 'all' );

}

/**
 * Extract a YouTube video ID from common URL formats.
 *
 * Supports watch, Shorts, youtu.be, embed, and /v/ URLs.
 *
 * @param string $url YouTube video URL.
 * @return string Video ID or empty string when not found.
 */
function vpfy_extract_youtube_video_id( $url ) {
	if ( empty( $url ) || ! is_string( $url ) ) {
		return '';
	}

	$url = trim( $url );
	$parsed = wp_parse_url( $url );

	if ( ! is_array( $parsed ) ) {
		return '';
	}

	$host = isset( $parsed['host'] ) ? strtolower( $parsed['host'] ) : '';

	// youtu.be/VIDEO_ID
	if ( 'youtu.be' === $host && ! empty( $parsed['path'] ) ) {
		$video_id = strtok( trim( $parsed['path'], '/' ), '?#' );
		if ( vpfy_is_valid_youtube_video_id( $video_id ) ) {
			return $video_id;
		}
	}

	// ?v=VIDEO_ID (standard watch URLs and legacy parsing behaviour).
	if ( ! empty( $parsed['query'] ) ) {
		parse_str( $parsed['query'], $query_args );
		if ( ! empty( $query_args['v'] ) ) {
			$video_id = strtok( $query_args['v'], '&#' );
			if ( vpfy_is_valid_youtube_video_id( $video_id ) ) {
				return $video_id;
			}
		}
	}

	// Path-based formats: /shorts/ID, /embed/ID, /v/ID, /live/ID.
	if ( ! empty( $parsed['path'] ) ) {
		$path = trim( $parsed['path'], '/' );
		if ( preg_match( '#^(?:shorts|embed|v|live)/([\w-]{11})#i', $path, $matches ) ) {
			return $matches[1];
		}
	}

	return '';
}

/**
 * Validate a YouTube video ID.
 *
 * @param string $video_id Candidate video ID.
 * @return bool
 */
function vpfy_is_valid_youtube_video_id( $video_id ) {
	return is_string( $video_id ) && (bool) preg_match( '/^[\w-]{11}$/', $video_id );
}


