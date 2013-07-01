<?php

/*
 * Program functions
 */



/*
 * Disable canonical redirect on map/map-group post type for stories pagination
 */
function vlchannel_program_disable_canonical($redirect_url) {
	if(is_singular('program'))
		return false;
}
add_filter('redirect_canonical', 'vlchannel_program_disable_canonical');

/*
 * Get current program color
 */
function vlchannel_get_program_color($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;

	if(get_post_type($post_id) != 'program' && get_post_type($post_id) != 'video')
		return false;

	if(get_post_type($post_id) == 'video')
		$post_id = vlchannel_get_video_program_id($post_id);

	$color = get_post_meta($post_id, 'program_color', true);

	if(!$color)
		return get_theme_mod('main_color');

	return $color;
}

/*
 * Get video program
 */
function vlchannel_get_video_program_id($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	return get_post_meta($post->ID, 'program', true);;
}


/*
 * Apply program colors on css
 */
function vlchannel_program_css() {
	$color = vlchannel_get_program_color();
	?>
	<style type="text/css">
		.program-color-border {
			border-color: <?php echo $color; ?> !important;
		}
		.program-color-border-t {
			border-color: rgba(<?php echo hex2rgb($color); ?>,0.1) !important;
		}
		.program-color-text {
			color: <?php echo $color ?> !important;
		}
		.program-background {
			background-color: <?php echo $color; ?> !important;
		}
	</style>
	<?php
}
add_action('wp_head', 'vlchannel_program_css');

function vlchannel_get_program_text_scheme() {
	return vlchannel_get_color_scheme(vlchannel_get_program_color());
}

/*
 * Get featured video
 */

function vlchannel_get_program_featured($program_id = false) {
	global $post;
	$post_id = $program_id ? $program_id : $post->ID;

	$featured = get_posts(vlchannel_get_program_query(array(
		'meta_query' => array(
			array(
				'key' => 'program_featured',
				'value' => 1
			)
		)
	)));

	if(!$featured)
		$featured = get_posts(vlchannel_get_program_query());

	if($featured)
		return array_shift($featured);

	return false;
}

/*
 * Get video query
 */

function vlchannel_get_program_query($query = array(), $program_id = false) {
	global $post;
	$post_id = $program_id ? $program_id : $post->ID;

	$p_query = array(
		'post_type' => 'video',
		'meta_query' => array(
			array(
				'key' => 'program',
				'value' => $post_id
			)
		),
		'paged' => (get_query_var('paged')) ? get_query_var('paged') : 1,
		'posts_per_page' => 4
	);

	if($query['meta_query']) {
		$p_query['meta_query'] = array_merge($p_query['meta_query'], $query['meta_query']);
	}

	$query = array_merge($p_query, $query);

	return apply_filters('vlchannel_program_query', $query);
}