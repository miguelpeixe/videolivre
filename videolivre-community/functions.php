<?php

function change_default_theme($blog_id) {
	switch_to_blog($blog_id);
	switch_theme('videolivre-channel', 'videolivre-channel');
	restore_current_blog();
}
add_action('wpmu_new_blog', 'change_default_theme', 100, 1);

function vccommunity_setup() {

	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	));

	add_theme_support('post-thumbnails');
	set_post_thumbnail_size( 624, 9999 );

}

add_action('after_setup_theme', 'vlcommunity_setup');


function vlcommunity_wp_title( $title, $sep ) {
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
		$title = "$title $sep " . sprintf( __( 'Page %s', 'videolivre_community' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'vlcommunity_wp_title', 10, 2 );