<?php

/*
 * Theme setup
 */
function vlchannel_setup() {

	load_theme_textdomain('videolivre-channel', get_template_directory() . '/languages');

	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	));

	add_theme_support('post-thumbnails');
	set_post_thumbnail_size( 624, 9999 );
	add_image_size('featured-video', 460, 266, true);
	add_image_size('thumbnail-video', 260, 145, true);

	register_sidebar(array(
		'name'          => __('Footer widgets', 'videlivre-channel'),
		'id'            => 'footer-widgets',
		'description'   => '',
		'class'         => '',
		'before_widget' => '<div class="three columns"><div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>'
	));

}
add_action('after_setup_theme', 'vlchannel_setup');

/*
 * Styles
 */

function vlchannel_styles() {
	wp_register_style('base', get_template_directory_uri() . '/css/base.css');
	wp_register_style('skeleton', get_template_directory_uri() . '/css/skeleton.css', array('base'));
	wp_register_style('main', get_template_directory_uri() . '/css/main.css', array('base', 'skeleton'));

	wp_enqueue_style('font-dosis', 'http://fonts.googleapis.com/css?family=Dosis:200,300,400,500,600');

	wp_enqueue_style('main');
}
add_action('wp_enqueue_scripts', 'vlchannel_styles');

/*
 * Scripts
 */

function vlchannel_scripts() {
	wp_enqueue_script('vlchannel-main', get_template_directory_uri() . '/js/videolivre.js', array('jquery'));
	wp_register_script('vlchannel-carousel', get_template_directory_uri() . '/js/carousel.js', array('jquery'));
}
add_action('wp_enqueue_scripts', 'vlchannel_scripts');

/* 
 * ACF
 */
function vlchannel_acf_path() {
	return get_template_directory_uri() . '/inc/acf/';
}
add_filter('acf/helpers/get_dir', 'vlchannel_acf_path');
define('ACF_LITE' , true);
include_once(TEMPLATEPATH . '/inc/acf/acf.php');

/**
 * Register post types
 */
include(TEMPLATEPATH . '/inc/post-types.php');

/**
 * Share count
 */
include(TEMPLATEPATH . '/inc/shares.php');

/**
 * Program functions
 */
include(TEMPLATEPATH . '/inc/program-functions.php');

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

/**
 * Admin settings
 */
include(TEMPLATEPATH . '/admin/settings.php');

/**
 * Community functions (WordPress MS)
 */
if(is_multisite())
	include(TEMPLATEPATH . '/inc/community-functions.php');

/*
 * Add subtitle (srt files) mime type
 */
add_filter('upload_mimes', 'vlchannel_upload_mimes');
function vlchannel_upload_mimes ($mimes = array()) {
	$mimes['srt'] = 'text/plain';
	return $mimes;
}

/*
 * Custom title
 */

function vlchannel_wp_title($title, $sep) {
	global $paged, $page;

	if (is_feed())
		return $title;

	// Add the site name.
	$title .= get_bloginfo('name');

	// Add the site description for the home/front page.
	$site_description = get_bloginfo('description', 'display');
	if ($site_description && (is_home() || is_front_page()))
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ($paged >= 2 || $page >= 2)
		$title = "$title $sep " . sprintf(__('Page %s', 'videolivre_channel'), max($paged, $page));

	return $title;
}
add_filter('wp_title', 'vlchannel_wp_title', 10, 2);

/*
 * Registration and login url
 */

function vlchannel_login_url() {
	return wp_login_url();
}

function vlchannel_logout_url() {
	return wp_logout_url();
}

function vlchannel_register_url() {
	return site_url('/wp-login.php?action=register');
}

/*
 * Share butttons
 */

function vlchannel_social_shares($url = false) {
	?>
	<ul class="social social-share">
		<li class="facebook">
			<div class="fb-like" <?php if($url) echo 'data-href="'. $url . '"'; ?> data-send="false" data-layout="box_count" data-width="53" data-show-faces="false"></div>
		</li>
		<li class="twitter">
			<iframe allowtransparency="true" frameborder="0" scrolling="no" src="https://platform.twitter.com/widgets/tweet_button.html?count=vertical<?php if($url) echo '&url=' . $url; ?>"></iframe>
		</li>
		<li class="gplus">
			<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
			<div class="g-plusone" <?php if($url) echo 'data-href="'. $url . '"'; ?> data-size="tall"></div>
		</li>
	</ul>
	<?php
}

/*
 * Breadcrumbs
 */

function vlchannel_breadcrumb() {

	$links = array();

	$links[get_bloginfo('name')] = home_url('/');
	$program = vlchannel_get_video_program_id();
	if(is_single() && $program) {
		$links[get_the_title($program)] = get_permalink($program);
	}
	if(is_singular('program')) {
		$links[__('Programs', 'videolivre-channel')] = get_post_type_archive_link('program');
	}

	if($links) {
		echo '<nav id="breadcrumb">';
		foreach($links as $title => $url) {
			echo '<a href="' . $url . '">' . $title . '</a> <span class="arrow">></span> ';
		}
		echo '</nav>';
	}
}

/*
 * Comment form
 */

function vlchannel_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
			// Display trackbacks differently than normal comments.
			?>
			<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
				<p><?php _e( 'Pingback:', 'videolivre-channel' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?></p>
			<?php
		break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment clearfix">
			<div class="<?php if($comment->comment_parent) echo 'three offset-by-one'; else echo 'four'; ?> columns alpha">
				<header class="comment-meta comment-author vcard program-color-border clearfix">
					<?php
						if($comment->comment_parent)
							echo get_avatar($comment, 40);
						else
							echo get_avatar($comment, 60);
						printf( '<cite class="fn">%1$s %2$s</cite>',
							get_comment_author_link(),
							// If current post author is also comment author, make it known visually.
							( $comment->user_id === $post->post_author ) ? '<span class="program-background"> ' . __( 'Author', 'videolivre-channel' ) . '</span>' : ''
						);
						printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
							esc_url( get_comment_link( $comment->comment_ID ) ),
							get_comment_time( 'c' ),
							/* translators: 1: date, 2: time */
							sprintf( __( '%1$s at %2$s', 'videolivre-channel' ), get_comment_date(), get_comment_time() )
						);
					?>
				</header><!-- .comment-meta -->
			</div>
			<div class="eight columns omega">
				<div class="program-color-border comment-content-area">
					<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'videolivre-channel' ); ?></p>
					<?php endif; ?>

					<section class="comment-content comment">
						<?php edit_comment_link( __( 'Edit', 'videolivre-channel' ), '<p class="edit-link">', '</p>' ); ?>
						<?php comment_text(); ?>
					</section><!-- .comment-content -->

					<div class="reply">
						<?php comment_reply_link( array_merge( $args, array('reply_text' => __( 'Reply', 'videolivre-channel' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</div><!-- .reply -->
				</div>
			</div>
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}

/*
 * Check if has next/prev page
 */

function vlchannel_has_next_page() {
	if(get_next_posts_link() === null)
		return false;

	return true;
}

function vlchannel_has_prev_page() {
	if(get_previous_posts_link() === null)
		return false;

	return true;
}

/*
 * Get color brightness
 */

function vlchannel_get_brightness($hex) {
	// returns brightness value from 0 to 255

	// strip off any leading #
	$hex = str_replace('#', '', $hex);

	$c_r = hexdec(substr($hex, 0, 2));
	$c_g = hexdec(substr($hex, 2, 2));
	$c_b = hexdec(substr($hex, 4, 2));

	return (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;
}

// determine light or dark color scheme

function vlchannel_get_color_scheme($hex) {
	if(!$hex)
		return false;

	if(vlchannel_get_brightness($hex) > 130)
		return 'dark-scheme';
	else
		return 'light-scheme';
}

/*
 * Helpers
 */

function hex2rgb($hex) {
	$hex = str_replace("#", "", $hex);

	if(strlen($hex) == 3) {
		$r = hexdec(substr($hex,0,1).substr($hex,0,1));
		$g = hexdec(substr($hex,1,1).substr($hex,1,1));
		$b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
	}
	$rgb = array($r, $g, $b);
	return implode(",", $rgb); // returns the rgb values separated by commas
	//return $rgb; // returns an array with the rgb values
}

function vlchannel_custom_ordering_var() {
	global $wp;
	$wp->add_query_var('vlchannel_order');
}
add_action('init', 'vlchannel_custom_ordering_var');

function vlchannel_custom_ordering($wp_query) {
	$order = $wp_query->get('vlchannel_order');
	if($order) {
		if($order == 'recent') {
			$wp_query->set('orderby', 'date');
			$wp_query->set('order', 'DESC');
		} elseif ($order == 'old') {
			$wp_query->set('orderby', 'date');
			$wp_query->set('order', 'ASC');
		} elseif($order == 'popular') {
			$wp_query->set('meta_key', '_vlchannel_share_count_total');
			$wp_query->set('orderby', 'meta_value_num');
			$wp_query->set('order', 'DESC');
		}
	}
	return $wp_query;
}
add_filter('pre_get_posts', 'vlchannel_custom_ordering');

function vlchannel_custom_ordering_labels() {
	return apply_filters('vlchannel_ordering_labels', array(
		'popular' => __('Most popular', 'vlchannel'),
		'recent' => __('Most recent', 'vlchannel'),
		'old' => __('Oldests', 'vlchannel')
	));
}

function vlchannel_get_current_order_label() {
	$current = get_query_var('vlchannel_order');
	if(!$current)
		$current = 'recent';

	$labels = vlchannel_custom_ordering_labels();

	return $labels[$current];
}

function vlchannel_custom_ordering_dropdown() {
	$current = get_query_var('vlchannel_order');
	if(!$current)
		$current = 'recent';

	$labels = vlchannel_custom_ordering_labels();

	$available = $labels;
	unset($available[$current]);

	global $wp;

	?>
	<div class="ordering-dropdown">
		<p class="title"><?php _e('Order by', 'vlchannel'); ?></p>
		<div class="choices button program-color-border">
			<p class="current"><?php echo $labels[$current]; ?></p>
			<ul class="list program-color-border">
				<?php foreach($available as $key => $label) : ?>
					<li class="<?php echo $key; ?> choice"><a href="<?php echo add_query_arg('order', $key, home_url($wp->request)); ?>" title="<?php echo $label; ?>"><?php echo $label; ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php
}

function vlchannel_login_logo() {
	wp_enqueue_style('font-dosis', 'http://fonts.googleapis.com/css?family=Dosis:200,300,400,500,600');
	?>
    <style type="text/css">
        body.login div#login h1 a {
            background: transparent;
            padding-bottom: 30px;
            text-indent: 0;
            font-size: 60px;
            line-height: 60px;
            overflow: visible;
            width: auto;
            height: auto;
            font-family: "Dosis";
            font-weight: 200;
            text-decoration: none;
        }
    </style>
	<?php
}
add_action( 'login_enqueue_scripts', 'vlchannel_login_logo' );

/*
 * Allow any post type on tag and category archive
 */

add_filter('pre_get_posts', 'vlchannel_archive_query');
function vlchannel_archive_query($query) {
	if(is_category() || is_tag()) {
		$query->set('post_type', 'any');
	}
	return $query;
}

/*
 * Remove some menu pages
 */

function vlchannel_remove_menu_pages() {
	remove_menu_page('link-manager.php');
	remove_menu_page('edit.php');	
}
add_action('admin_menu', 'vlchannel_remove_menu_pages');

function vlchannel_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_node('about');
    $wp_admin_bar->remove_node('wporg');
    $wp_admin_bar->remove_node('documentation');
    $wp_admin_bar->remove_node('support-forums');
    $wp_admin_bar->remove_node('feedback');
    $wp_admin_bar->remove_node('view-site');

    $wp_admin_bar->remove_node('new-post');
}
add_action('wp_before_admin_bar_render', 'vlchannel_admin_bar');