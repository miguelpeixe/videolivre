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
}
add_action('wp_enqueue_scripts', 'vlchannel_video_scripts');

function video_embed($post_id = false) {
	echo get_video_embed($post_id);
}

function get_video_embed($post_id = false) {
	global $post;
	$post_id = ($post_id ? $post_id : $post->ID);

	$video_data = get_post_meta($post_id);

	$embed = '';

	if($video_data['video_srv'][0] == 'html5') {

		$embed .= '<video id="video_' . $post_id . '" controls="controls" preload="none">';

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
	}

	return $embed;


	/*
	echo '
	<video width="320" height="240" poster="poster.jpg" controls="controls" preload="none">
	    <!-- MP4 for Safari, IE9, iPhone, iPad, Android, and Windows Phone 7 -->
	    <source type="video/mp4" src="myvideo.mp4" />
	    <!-- WebM/VP8 for Firefox4, Opera, and Chrome -->
	    <source type="video/webm" src="myvideo.webm" />
	    <!-- Ogg/Vorbis for older Firefox and Opera versions -->
	    <source type="video/ogg" src="myvideo.ogv" />
	    <!-- Optional: Add subtitles for each language -->
	    <track kind="subtitles" src="subtitles.srt" srclang="en" />
	    <!-- Optional: Add chapters -->
	    <track kind="chapters" src="chapters.srt" srclang="en" /> 
	    <!-- Flash fallback for non-HTML5 browsers without JavaScript -->
	    <object width="320" height="240" type="application/x-shockwave-flash" data="flashmediaelement.swf">
	        <param name="movie" value="flashmediaelement.swf" />
	        <param name="flashvars" value="controls=true&file=myvideo.mp4" />
	        <!-- Image as a last resort -->
	        <img src="myvideo.jpg" width="320" height="240" title="No video playback capabilities" />
	    </object>
	</video>
	';
	*/
}