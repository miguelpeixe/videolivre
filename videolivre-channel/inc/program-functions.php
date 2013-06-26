<?php

/*
 * Program functions
 */

/*
 * Get current program color
 */
function vlchannel_get_program_color() {
	global $post;

	if(is_single() && get_post_type() == 'video')
		return get_post_meta(vlchannel_get_video_program_id(), 'program_color', true);
	elseif(is_single() && get_post_type() == 'program')
		return get_post_meta($post->ID, 'program_color', true);

	return false;
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
	if(!$color)
		return false;
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
		)
	);

	$query = array_merge_recursive($p_query, $query);

	return apply_filters('vlchannel_program_query', $query);
}