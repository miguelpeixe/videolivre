<?php

/*
 * Program functions
 */

/*
 * Get current program color
 */
function vlchannel_get_program_color() {
	if(is_single() && get_post_type() == 'video')
		return get_post_meta(vlchannel_get_video_program_id(), 'program_color', true);

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