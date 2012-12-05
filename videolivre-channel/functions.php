<?php

function vlchannel_setup() {

	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	));

	add_theme_support('post-thumbnails');
	set_post_thumbnail_size( 624, 9999 );

}

add_action('after_setup_theme', 'vlchannel_setup');

/**
 * Register post types
 */
 include(TEMPLATEPATH . '/inc/post-types.php');

/**
 * Include metaboxes
 */
include(TEMPLATEPATH . '/metaboxes/metaboxes.php');

/**
 * Adds support for a custom header image.
 */
require(get_template_directory() . '/inc/custom-header.php');

// custom preview
function vlchannel_customize_preview_js() {
	wp_enqueue_script( 'vlchannel-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20120827', true );
}
//add_action( 'customize_preview_init', 'vlchannel_customize_preview_js' );


function vlchannel_wp_title( $title, $sep ) {
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
add_filter( 'wp_title', 'vlchannel_wp_title', 10, 2 );