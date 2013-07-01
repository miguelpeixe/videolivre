<?php

/*
 * Video functions
 */

/*
 * Register scripts
 */

function vlchannel_video_scripts() {
	wp_enqueue_script('mediaelement-and-player', get_template_directory_uri() . '/lib/mediaelement/mediaelement-and-player.min.js', array('jquery'));
	wp_enqueue_style('mediaelementplayer', get_template_directory_uri() . '/lib/mediaelement/mediaelementplayer.css');
	wp_enqueue_script('vlchannel-player', get_template_directory_uri() . '/js/videoplayer.js', 'mediaelement-and-player');
	wp_enqueue_script('fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', 'mediaelement-and-player');
}
add_action('wp_enqueue_scripts', 'vlchannel_video_scripts');

/*
 * Echo video embed
 */
function vlchannel_video($post_id = false) {
	do_action('vlchannel_video');
	echo vlchannel_get_video($post_id);
}

/*
 * Get video embed
 */
function vlchannel_get_video($post_id = false) {

	global $post;

	$post_id = ($post_id ? $post_id : $post->ID);

	$video_data = get_post_meta($post_id);

	$embed = apply_filters('vlchannel_before_video', '');

	if($video_data['video_srv'][0] == 'html5') {

		$embed .= '<video id="video_' . $post_id . '" controls="controls" preload="preload" style="max-width: 100%;">';

		// video sources

		if($video_data['video_html5_webm'][0])
			$embed .= '<source type="video/mp4" src="' . $video_data['video_html5_webm'][0] . '" />';
		if($video_data['video_html5_ogv'][0])
			$embed .= '<source type="video/ogg" src="' . $video_data['video_html5_ogv'][0] . '" />';
		if($video_data['video_html5_mp4'][0])
			$embed .= '<source type="video/ogg" src="' . $video_data['video_html5_mp4'][0] . '" />';

		// video subtitles

		$video_subtitles = get_post_meta($post_id, 'video_html5_subtitles', true);
		if($video_subtitles) {
			foreach($video_subtitles as $subtitle) {
				$embed .= '<track kind="subtitles" src="' . $subtitle['url'] . '" srclang="' . $subtitle['lang-code'] . '" label="' . $subtitle['lang-label'] . '" />';
			}
		}

		// flash fallback

		if($video_data['video_html5_webm'][0]) {
			$swf = get_template_directory_uri() . '/lib/mediaelement/flashmediaelement.swf';
			$embed .= '<object name="movie" type="application/x-shockwave-flash" data="' . $swf . '">';

			$embed .= '<param name="movie" value="' . $swf . '" />';
			$embed .= '<param name="flashvars" value="controls=true&file=' . $video_data['video_html5_webm'] . '" />';

	        // $embed .= '<img src="myvideo.jpg" width="320" height="240" title="No video playback capabilities" />';

			$embed .= '</object>';
		}

		$embed .= '</video>';

		$embed .= '<script type="text/javascript">jQuery("#video_' . $post_id . '").mediaelementplayer();</script>';
	} elseif($video_data['video_srv'][0] == 'youtube') {
		$embed .= '<iframe id="video_' . $post_id . ' class="fitvid" src="http://www.youtube.com/embed/'.$video_data['video_src'][0].'" frameborder="0" allowfullscreen></iframe>';
	}

	$embed .= apply_filters('vlchannel_after_video', '');

	return $embed;
}

/*
 * Video dynamic data (external APIs)
 */

// Views
function the_views($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	echo vlchannel_get_video_views($post_id);
}
function vlchannel_get_video_views($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;

	$video_data = get_post_meta($post_id);

	$views = false;

	// grab views from youtube and store transient
	if($video_data['video_srv'][0] == 'youtube') {
		$views = get_transient('video_'.$post_id.'_views');
		if(!$views) {
			$youtube_data = json_decode(file_get_contents('http://gdata.youtube.com/feeds/api/videos/' . $video_data['video_src'][0] . '?v=2&alt=jsonc'));
			$views = $youtube_data->data->viewCount;
		}
	}
	// grab views from youtube and store transient
	elseif($video_data['video_srv'][0] == 'vimeo') {
		$views = get_transient('video_'.$post_id.'_views');
		if(!$views) {
			$vimeo_data = array_shift(json_decode(file_get_contents('http://vimeo.com/api/v2/video/' . $video_data['video_src'][0] . '.json')));
			$views = $vimeo_data->stats_number_of_plays;
		}
	}
	set_transient('video_'.$post_id.'_views', $views, 60*60);
	return $views;
}



/*
 * Video metadata getters
 */

// Year of production
function the_launch($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	echo get_the_launch($post_id);
}
function get_the_launch($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	$launch = get_post_meta($post_id, 'year', true);
	return apply_filters('vlchannel_video_launch', $launch);
}
function has_launch($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	if(get_post_meta($post_id, 'year', true))
		return true;
	
	return false;
}

// Direction
function the_director($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	echo get_the_director($post_id);
}
function get_the_director($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	$director = get_post_meta($post_id, 'direction', true);
	return apply_filters('vlchannel_video_direction', $director);
}
function has_director($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	if(get_post_meta($post_id, 'direction', true))
		return true;
	
	return false;
}

// Duration
function the_duration($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	echo get_the_duration($post_id);
}
function get_the_duration($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	$duration = intval(get_post_meta($post_id, 'video_duration', true));
	// convert seconds into readable duration
	if($duration / 3600 >= 1) {
		$hour = floor($duration / 3600);
		$min = floor(($duration - ($hour * 3600)) / 60);
		$sec = $duration - ($hour * 3600) - ($min * 60);
		$duration = $hour . 'h' . $min . 'm' . $sec . 's';
	} elseif($duration / 60 >= 1) {
		$min = floor($duration / 60);
		$sec = $duration % 60;
		$duration = $min . 'm' . $sec . 's';
	} else {
		$duration = $duration . 's';
	}
	return apply_filters('vlchannel_video_duration', $duration);
}
function has_duration($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	if(get_post_meta($post_id, 'video_duration', true))
		return true;
	
	return false;
}

// Crew
function the_crew($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	echo get_the_crew($post_id);
}
function get_the_crew($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	$team = get_post_meta($post_id, 'crew', true);
	return apply_filters('vlchannel_video_crew', $team);
}
function has_crew($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	if(get_post_meta($post_id, 'crew', true))
		return true;
	
	return false;
}

// Attachments
function get_attachments($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	$attachments = get_post_meta($post_id, 'video_attachments', true);
	return apply_filters('vlchannel_video_attachments', $attachments);
}