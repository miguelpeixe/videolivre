<?php

define('IS_VLCOMMUNITY', true);

// channels
include_once(STYLESHEETPATH . '/inc/channel/channel.php');

// channels
include_once(STYLESHEETPATH . '/inc/blog.php');

// slider
include_once(STYLESHEETPATH . '/inc/slider/slider.php');

// multisite query
require_once(STYLESHEETPATH . '/inc/multisite-query.php');

function vlcommunity_setup() {

	load_child_theme_textdomain('videolivre-community', get_stylesheet_directory() . '/languages');

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
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>'
	));

	register_nav_menu('main', __('Main navigation menu', 'videlivre-community'));

}
add_action('after_setup_theme', 'vlcommunity_setup');

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
	if(is_front_page() && is_home() && $pagenow !== 'wp-signup.php' && !$wp_query->get('vl_channels') && !$wp_query->get('blog')) {
		$GLOBALS['vl_slider']->slider();
	}
}
add_action('vl_after_header', 'vl_community_slider', 100);

function vl_community_nav() {
	?>
	<nav id="mastnav">
		<div class="container">
			<div class="twelve columns">
				<?php wp_nav_menu('main'); ?>
			</div>
		</div>
	</nav>
	<?php
}
add_action('vl_after_header', 'vl_community_nav', 1);

function vl_multisite_search($query) {
	if(!is_admin() && $query->is_main_query() && $query->is_search) {
		$query->set('post_type', array('video', 'program'));
	}
	return $query;
}
add_action('pre_get_posts', 'vl_multisite_search', 100, 1);

function vl_community_breadcrumb($links) {
	if(is_singular('post'))
		$links['Blog'] = vl_get_blog_archive_url();

	return $links;
}
add_filter('vl_breadcrumb_links', 'vl_community_breadcrumb');