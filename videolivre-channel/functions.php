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
	add_image_size('featured-video', 460, 266, true);
	add_image_size('thumbnail-video', 220, 124, true);

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

/**
 * Register post types
 */
include(TEMPLATEPATH . '/inc/post-types.php');

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
	return '#';
}

function vlchannel_register_url() {
	return '#';
}

/*
 * Share butttons
 */

function vlchannel_social_shares() {
	?>
	<ul class="social social-share">
		<li class="facebook">
			<div class="fb-like" data-send="false" data-layout="box_count" data-width="53" data-show-faces="false"></div>
		</li>
		<li class="twitter">
			<iframe allowtransparency="true" frameborder="0" scrolling="no" src="https://platform.twitter.com/widgets/tweet_button.html?count=vertical"></iframe>
		</li>
		<li class="gplus">
			<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
			<div class="g-plusone" data-size="tall"></div>
		</li>
	</ul>
	<?php
}

/*
 * Breadcrumbs
 */

function vlchannel_breadcrumb() {
	if(vlchannel_get_community()) {
		$links[get_bloginfo('name')] = home_url('/');
	}
	$program = vlchannel_get_video_program_id();
	if(is_single() && $program) {
		$links[get_the_title($program)] = get_permalink($program);
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