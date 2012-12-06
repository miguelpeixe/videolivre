<?php

/*
 * Theme setup
 */
function vlchannel_setup() {

	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	));

	add_theme_support('post-thumbnails');
	set_post_thumbnail_size( 624, 9999 );

}
add_action('after_setup_theme', 'vlchannel_setup');

/*
 * Styles
 */

function vlchannel_styles() {
	wp_register_style('base', get_template_directory_uri() . '/css/base.css');
	wp_register_style('skeleton', get_template_directory_uri() . '/css/skeleton.css', array('base'));
	wp_register_style('main', get_template_directory_uri() . '/css/main.css', array('base', 'skeleton'));

	wp_enqueue_style('main');
}
add_action('wp_enqueue_scripts', 'vlchannel_styles');

/**
 * Register post types
 */
include(TEMPLATEPATH . '/inc/post-types.php');

/**
 * Video functions
 */
include(TEMPLATEPATH . '/inc/video-functions.php');

/**
 * Include metaboxes
 */
include(TEMPLATEPATH . '/metaboxes/metaboxes.php');

/**
 * Add support for a custom header image.
 */
require(TEMPLATEPATH . '/inc/custom-header.php');

/**
 * Theme customizer
 */
require(TEMPLATEPATH . '/inc/theme-customizer.php');


function vlchannel_wp_title($title, $sep) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'videolivre_channel' ), max( $paged, $page ) );

	return $title;
}
add_filter('wp_title', 'vlchannel_wp_title', 10, 2);

/*
 * Add subtitle (srt files) mime type
 */
add_filter('upload_mimes', 'vlchannel_upload_mimes');
function vlchannel_upload_mimes ($mimes = array()) {
	$mimes['srt'] = 'text/plain';
	return $mimes;
}