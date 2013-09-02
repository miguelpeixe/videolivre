<?php

define('IS_VLCOMMUNITY', true);

function vlcommunity_setup() {

	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	));

	add_theme_support('post-thumbnails');
	set_post_thumbnail_size( 624, 9999 );

	register_sidebar(array(
		'name'          => __('Blog widgets', 'videlivre-community'),
		'id'            => 'post',
		'description'   => '',
		'class'         => '',
		'before_widget' => '<div class="three columns"><div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>'
	));

}

add_action('after_setup_theme', 'vlcommunity_setup');

include_once(STYLESHEETPATH . '/inc/channel/channel.php');

include_once(STYLESHEETPATH . '/inc/slider/slider.php');

function vlcommunity_styles() {
	wp_enqueue_style('community-main', get_stylesheet_directory_uri() . '/css/main.css');
}
add_action('wp_enqueue_scripts', 'vlcommunity_styles');

function vlcommunity_flush_rewrite() {
	global $pagenow;
	if(is_admin() && $_REQUEST['activated'] && $pagenow == 'themes.php') {
		global $wp_rewrite;
		$wp_rewrite->init();
		$wp_rewrite->flush_rules();
	}
}
add_action('init', 'vlcommunity_flush_rewrite');

function vl_community_slider() {
	global $pagenow, $wp_query;
	if(is_front_page() && is_home() && $pagenow !== 'wp-signup.php' && !$wp_query->get('vl_channels')) {
		$GLOBALS['vl_slider']->slider();
	}
}
add_action('vl_after_header', 'vl_community_slider');

require_once(STYLESHEETPATH . '/inc/multisite-query.php');
function vl_multisite_search($query) {
	if(!is_admin() && $query->is_main_query() && $query->is_search) {
		$query->set('post_type', array('video', 'program'));
	}
	return $query;
}
add_action('pre_get_posts', 'vl_multisite_search', 1, 100);