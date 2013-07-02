<?php

/*
 * Program functions
 */

/*
 * ACF fields
 */

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_program-configuration',
		'title' => 'Program configuration',
		'fields' => array (
			array (
				'default_value' => '',
				'key' => 'field_51d23642db67f',
				'label' => 'Program color',
				'name' => 'program_color',
				'type' => 'color_picker',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'program',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));

	register_field_group(array (
		'id' => 'acf_program-featured-options',
		'title' => __('Featured options', 'videolivre-channel'),
		'fields' => array (
			array (
				'default_value' => 0,
				'message' => __('This program is featured', 'videolivre-channel'),
				'key' => 'field_51d25bf1e15b6',
				'label' => __('Featured program', 'videolivre-channel'),
				'name' => 'featured_program',
				'type' => 'true_false',
				'instructions' => __('Set this program as channel featured. You can only have one featured program on your channel. If more than one is selected, the last uploaded will be selected.', 'videolivre-channel'),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'program',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'side',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));

}


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
 * Get featured program
 */

function vlchannel_get_featured_program() {

	$featured = get_posts(array(
		'post_type' => 'program',
		'meta_query' => array(
			array(
				'key' => 'featured_program',
				'value' => 1
			)
		)
	));

	if(!$featured)
		$featured = get_posts(array('post_type' => 'program'));

	if($featured)
		return array_shift($featured);

	return false;
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
		$query['meta_query'] = array_merge($p_query['meta_query'], $query['meta_query']);
	}

	$query = array_merge($p_query, $query);

	return apply_filters('vlchannel_program_query', $query);
}