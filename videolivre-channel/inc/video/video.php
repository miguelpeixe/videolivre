<?php

/*
 * Video Livre
 * Videos
 */

class VL_Video {

	public static $post_type = 'video';
	public static $slug = 'videos';

	function __construct() {

		//if(!defined('IS_VLCOMMUNITY'))
			add_action('init', array($this, 'init'));

	}

	function init() {

		add_action('wp_enqueue_scripts', array($this, 'scripts'));

		$this->register_post_type();
		$this->acf_fields();
		$this->views_setup();
		$this->video_box_setup();

		add_filter('upload_mimes', array($this, 'upload_mimes'));

		do_action('vl_video_init');

	}

	function uri() {
		return apply_filters('vl_videos_uri', get_template_directory_uri() . '/inc/video');
	}

	function path() {
		return apply_filters('vl_videos_path', TEMPLATEPATH . '/inc/video');
	}


	function register_post_type() {

		$labels = array( 
			'name' => __('Videos', 'videolivre-channel'),
			'singular_name' => __('Video', 'videolivre-channel'),
			'add_new' => __('Add new', 'videolivre-channel'),
			'add_new_item' => __('Add new video', 'videolivre-channel'),
			'edit_item' => __('Edit video', 'videolivre-channel'),
			'new_item' => __('New video', 'videolivre-channel'),
			'view_item' => __('View video', 'videolivre-channel'),
			'search_items' => __('Search videos', 'videolivre-channel'),
			'not_found' => __('No videos found', 'videolivre-channel'),
			'not_found_in_trash' => __('No videos found in trash', 'videolivre-channel'),
			'parent_item_colon' => __('Parent video:', 'videolivre-channel'),
			'menu_name' => __('Videos', 'videolivre-channel'),
		);

		$args = array( 
			'labels' => $labels,
			'hierarchical' => false,

			'supports' => array('title', 'editor', 'author', 'trackbacks', 'comments', 'thumbnail'),
			'taxonomies' => array('post_tag'),

			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 2,

			'show_in_nav_menus' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => array('slug' => self::$slug),
			'capability_type' => 'post'
		);

		register_post_type(self::$post_type, $args);
	}

	/*
	 * Register scripts
	 */

	function scripts() {
		wp_enqueue_script('mediaelement-and-player', $this->uri() . '/js/mediaelement/mediaelement-and-player.min.js', array('jquery'));
		wp_enqueue_style('mediaelementplayer', $this->uri() . '/js/mediaelement/mediaelementplayer.css');
		wp_enqueue_script('vl-player', $this->uri() . '/js/videoplayer.js', 'mediaelement-and-player');
		wp_enqueue_script('fitvids', $this->uri() . '/js/jquery.fitvids.js', 'mediaelement-and-player');
	}

	/*
	 * Get video embed
	 */
	function get_the_video($post_id = false) {

		global $post;

		$post_id = ($post_id ? $post_id : $post->ID);

		$video_data = get_post_meta($post_id);

		$embed = apply_filters('vl_video_before_video', '');

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

				$embed .= '</object>';
			}

			$embed .= '</video>';

			$embed .= '<script type="text/javascript">jQuery("#video_' . $post_id . '").mediaelementplayer();</script>';
		} elseif($video_data['video_srv'][0] == 'youtube') {
			$embed .= '<iframe id="video_' . $post_id . '" class="fitvid" src="http://www.youtube.com/embed/'.$video_data['video_src'][0].'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		} elseif($video_data['video_srv'][0] == 'vimeo') {
			$embed .= '<iframe id="video_' . $post_id . '" class="fitvid" src="http://player.vimeo.com/video/' . $video_data['video_src'][0] . '?title=0&amp;byline=0&amp;portrait=0&amp;color=' . apply_filters('vl_video_vimeo_color', '') . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		}

		$embed .= apply_filters('vl_video_after_video', '');

		return $embed;
	}

	// Views
	function get_the_views($post_id = false) {
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
			set_transient('video_'.$post_id.'_views', $views, 60*60);
		}
		// grab views from youtube and store transient
		elseif($video_data['video_srv'][0] == 'vimeo') {
			$views = get_transient('video_'.$post_id.'_views');
			if(!$views) {
				$vimeo_data = array_shift(json_decode(file_get_contents('http://vimeo.com/api/v2/video/' . $video_data['video_src'][0] . '.json')));
				$views = $vimeo_data->stats_number_of_plays;
			}
			set_transient('video_'.$post_id.'_views', $views, 60*60);
		} else {
			$views = get_post_meta($post_id, '_vl_views', true);
		}

		if(!$views)
			$views = 0;

		return apply_filters('vl_video_views', $views);
	}

	// Own views system

	function views_setup() {
		add_action('wp_ajax_nopriv_vl_view', array($this, 'views_ajax'));
		add_action('wp_ajax_vl_view', array($this, 'views_ajax'));
		add_action('wp_footer', array($this, 'views_scripts'));
	}

	function views_ajax() {
		if(!wp_verify_nonce($_REQUEST['nonce'], 'vl_count_view'))
			die(__('Permission denied.', 'videolivre-channel'));

		$views = get_post_meta($_REQUEST['post_id'], '_vl_views', true);

		if(!$views)
			$views = 1;
		else
			$views++;

		update_post_meta($_REQUEST['post_id'], '_vl_views', $views);

		exit();
	}

	function views_scripts() {
		if(is_singular('video')) {
			global $post;
			wp_enqueue_script('vl-views', $this->uri() . '/js/views.js', array('jquery'), '0.1');
			wp_localize_script('vl-views', 'vl_views', array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('vl_count_view'),
				'postid' => $post->ID
			));
		}
	}

	function video_box_setup() {
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		add_action('save_post', array($this, 'save_post'));
	}

	function add_meta_boxes() {
		add_meta_box(
			'video_metabox',
			__('Add video', 'videolivre-channel'),
			array($this, 'video_box'),
			'video',
			'advanced',
			'high'
		);
	}

	function save_post($post_id) {
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		if (defined('DOING_AJAX') && DOING_AJAX)
			return;

		if (false !== wp_is_post_revision($post_id))
			return;

		update_post_meta($post_id, 'video_src', $_POST['video_src']);
		update_post_meta($post_id, 'video_url', $_POST['video_url']);
		update_post_meta($post_id, 'video_srv', $_POST['video_srv']);
		update_post_meta($post_id, 'video_duration', $_POST['video_duration']);

		if($_POST['video_srv'] == 'html5') {
			update_post_meta($post_id, 'video_html5_mp4', $_POST['html5_mp4']);
			update_post_meta($post_id, 'video_html5_webm', $_POST['html5_webm']);
			update_post_meta($post_id, 'video_html5_ogv', $_POST['html5_ogv']);
			if($_POST['subtitles'])
				update_post_meta($post_id, 'video_html5_subtitles', $_POST['subtitles']);
		}
	}

	// add srt mime type
	function upload_mimes($mimes = array()) {
		$mimes['srt'] = 'text/plain';
		return $mimes;
	}

	function video_box($post) {

		wp_enqueue_script('google-jsapi', 'http://www.google.com/jsapi');
		wp_enqueue_script('vimeo-jsapi', $this->uri() . '/js/froogaloop.min.js', array('jquery'));
		wp_enqueue_script('video-metabox', $this->uri() . '/js/video-metabox.js', array('jquery', 'google-jsapi', 'vimeo-jsapi'), '1.0');
		wp_localize_script('video-metabox', 'video_metabox_messages', array(
			'empty_url' => __('You must enter a valid URL', 'videolivre-channel')
		));

		wp_enqueue_style('video-metabox', $this->uri() . '/css/video-metabox.css');

		wp_enqueue_script('custom-uploader', $this->uri() . '/js/custom-uploader.js');

		if($post) {
			$video_src = get_post_meta($post->ID, 'video_src', true);
			$video_url = get_post_meta($post->ID, 'video_url', true);
			$video_srv = get_post_meta($post->ID, 'video_srv', true);
			$video_duration = get_post_meta($post->ID, 'video_duration', true);
			$video_html5_mp4 = get_post_meta($post->ID, 'video_html5_mp4', true);
			$video_html5_webm = get_post_meta($post->ID, 'video_html5_webm', true);
			$video_html5_ogv = get_post_meta($post->ID, 'video_html5_ogv', true);
			$video_subtitles = get_post_meta($post->ID, 'video_html5_subtitles', true);
		}
		?>
		<input type="hidden" name="video_srv" value="<?php echo $video_srv; ?>" />
		<input type="hidden" name="video_src" value="<?php echo $video_src; ?>" />
		<input type="hidden" name="video_duration" value="<?php echo $video_duration; ?>" />
		<div class="supported-formats">
			<span><?php _e('Supported formats', 'videolivre-channel'); ?></span>
			<ul>
				<li class="youtube" title="YouTube">YouTube</li>
				<li class="vimeo" title="Vimeo">Vimeo</li>
				<li class="html5" title="HTML5">HTML5</li>
			</ul>
		</div>
		<h4><?php _e('Paste a video URL and click "Load video" or press enter to load the video', 'videolivre-channel'); ?></h4>
		<p><input type="text" size="80" id="video_url" name="video_url" placeholder="<?php _e('Video URL', 'videolivre-channel'); ?>" value="<?php echo $video_url; ?>" /></p>
		<div class="html5-extras">
			<p>
				<input type="text" size="80" class="html5_mp4" name="html5_mp4" placeholder="<?php _e('MP4 fallback', 'videolivre-channel'); ?>" value="<?php echo $video_html5_mp4; ?>" />
				<input type="text" size="80" class="html5_ogv" name="html5_ogv" placeholder="<?php _e('OGV fallback', 'videolivre-channel'); ?>" value="<?php echo $video_html5_ogv; ?>" />
				<input type="text" size="80" class="html5_webm" name="html5_webm" placeholder="<?php _e('WebM fallback', 'videolivre-channel'); ?>" value="<?php echo $video_html5_webm; ?>" />
			</p>
			<div class="subtitle-tracks">
				<a href="#" class="add-subtitle button">+ <?php _e('Add subtitle track', 'videolivre-channel'); ?></a>
				<div class="subtitle-container">
					<ul class="subtitle-list">
						<li class="model list-item">
							<span class="subtitle_url subtitle_part">
								<input type="text" placeholder="<?php _e('Subtitle url', 'videolivre-channel'); ?>" />
								<a class="button upload_file_button"><?php _e('Upload file', 'videolivre-channel'); ?></a>
							</span>
							<span class="subtitle_lang_code subtitle_part">
								<input type="text" placeholder="<?php _e('Language code', 'videolivre-channel'); ?>" title="<?php _e('E.g.: pt-BR, en-US, fr', 'videolivre-channel'); ?>" />
							</span>
							<span class="subtitle_lang_label subtitle_part">
								<input type="text" placeholder="<?php _e('Language label', 'videolivre-channel'); ?>" title="<?php _e('E.g.: English, Portuguese', 'videolivre-channel'); ?>" />
							</span>
							<span class="subtitle_manage subtitle_part">
								<a href="#" class="remove-subtitle"><?php _e('Remove subtitle', 'videolivre-channel'); ?></a>
							</span>
						</li>
						<?php if($video_subtitles) : $i = 0; foreach($video_subtitles as $subtitle) : ?>
							<li class="list-item">
								<span class="subtitle_url subtitle_part">
									<input type="text" name="subtitles[<?php echo $i; ?>][url]" placeholder="<?php _e('Subtitle url', 'videolivre-channel'); ?>" value="<?php echo $subtitle['url']; ?>" />
									<a class="button upload_file_button"><?php _e('Upload file', 'videolivre-channel'); ?></a>
								</span>
								<span class="subtitle_lang_code subtitle_part">
									<input type="text" name="subtitles[<?php echo $i; ?>][lang-code]" placeholder="<?php _e('Language code', 'videolivre-channel'); ?>" title="<?php _e('E.g.: pt-BR, en-US, fr', 'videolivre-channel'); ?>" value="<?php echo $subtitle['lang-code']; ?>" />
								</span>
								<span class="subtitle_lang_label subtitle_part">
									<input type="text" name="subtitles[<?php echo $i; ?>][lang-label]" placeholder="<?php _e('Language label', 'videolivre-channel'); ?>" title="<?php _e('E.g.: English, Portuguese', 'videolivre-channel'); ?>" value="<?php echo $subtitle['lang-label']; ?>" />
								</span>
								<span class="subtitle_manage subtitle_part">
									<a href="#" class="remove-subtitle"><?php _e('Remove subtitle', 'videolivre-channel'); ?></a>
								</span>
							</li>
						<?php $i++; endforeach; endif; ?>
					</ul>
					<div class="subtitle-tips">
						<span class="subtitle_url tip"><?php _e('Enter the file url or click to upload a <strong>.srt</strong> or <strong>.vtt</strong> file', 'videolivre-channel'); ?></span>
						<span class="subtitle_lang_code tip"><?php echo sprintf(__('According to <a href="%s" target="_blank" rel="external">BCP 47</a> code. E.g.: pt-BR, en-US, fr.', 'videolivre-channel'), 'http://tools.ietf.org/html/bcp47'); ?></span>
						<span class="subtitle_lang_label tip"><?php _e('E.g.: English, Portuguese', 'videolivre-channel'); ?></span>
					</div>
				</div>
			</div>
		</div>
		<p><input type="button" class="locate-video button" value="<?php _e('Preview video', 'videolivre-channel'); ?>" /></p>
		<div class="video-container">
			<div id="apiplayer"></div>
		</div>
		<?php
	}

	/* 
	 * ACF fields
	 */

	function acf_fields() {
		if(function_exists("register_field_group"))	{
			register_field_group(array(
				'id' => 'acf_video-information',
				'title' => __('Video information', 'videolivre-channel'),
				'fields' => array(
					array(
						'default_value' => '',
						'formatting' => 'html',
						'key' => 'field_51d23120cec9e',
						'label' => __('Year of production', 'videolivre-channel'),
						'name' => 'production_year',
						'type' => 'text',
						'instructions' => 'E.g.: 2008',
					),
					array(
						'default_value' => '',
						'formatting' => 'html',
						'key' => 'field_51d23145cec9f',
						'label' => __('Direction', 'videolivre-channel'),
						'name' => 'direction',
						'type' => 'text',
						'instructions' => 'E.g.: John Doe',
					),
					array(
						'default_value' => '',
						'formatting' => 'br',
						'key' => 'field_51d2315bceca0',
						'label' => __('Technical information', 'videolivre-channel'),
						'name' => 'tech_info',
						'type' => 'textarea',
					),
				),
				'location' => array(
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'video',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options' => array(
					'position' => 'normal',
					'layout' => 'no_box',
					'hide_on_screen' => array(),
				),
				'menu_order' => 0,
			));

			$featured_fields = apply_filters('vl_video_acf_fields_featured', array(
				array(
					'default_value' => 0,
					'message' => __('This video is channel featured', 'videolivre-channel'),
					'key' => 'field_51d25ad7e88f3',
					'label' => __('Channel featured', 'videolivre-channel'),
					'name' => 'channel_featured',
					'type' => 'true_false',
					'instructions' => __('Set this video as channel featured. You can only have one featured video on your channel. If more than one is selected, the last uploaded will be selected.', 'videolivre-channel'),
				)
			));

			register_field_group(array(
				'id' => 'acf_featured-options',
				'title' => __('Featured options', 'videolivre-channel'),
				'fields' => $featured_fields,
				'location' => array(
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => self::$post_type,
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options' => array(
					'position' => 'side',
					'layout' => 'default',
					'hide_on_screen' => array(),
				),
				'menu_order' => 0,
			));
		}
	}


	/*
	 * Video metadata getters
	 */

	// Year of production
	function get_the_launch($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		$launch = get_post_meta($post_id, 'production_year', true);
		return apply_filters('vl_video_launch', $launch);
	}
	function has_launch($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		if(get_post_meta($post_id, 'year', true))
			return true;
		
		return false;
	}

	// Direction
	function get_the_director($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		$director = get_post_meta($post_id, 'direction', true);
		return apply_filters('vl_video_direction', $director);
	}
	function has_director($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		if(get_post_meta($post_id, 'direction', true))
			return true;
		
		return false;
	}

	// Duration
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
		return apply_filters('vl_video_duration', $duration);
	}
	function has_duration($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		if(get_post_meta($post_id, 'video_duration', true))
			return true;
		
		return false;
	}

	// Crew
	function get_the_crew($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		$team = get_post_meta($post_id, 'crew', true);
		return apply_filters('vl_video_crew', $team);
	}
	function has_crew($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		if(get_post_meta($post_id, 'crew', true))
			return true;
		
		return false;
	}

}

$GLOBALS['vl_video'] = new VL_Video();

function vl_the_video($post_id = false) {
	echo $GLOBALS['vl_video']->get_the_video($post_id);
}

function vl_get_the_video($post_id = false) {
	return $GLOBALS['vl_video']->get_the_video($post_id);
}

function vl_the_views($post_id = false) {
	echo $GLOBALS['vl_video']->get_the_views($post_id);
}

function vl_get_the_views($post_id = false) {
	return $GLOBALS['vl_video']->get_the_views($post_id);
}

function vl_the_launch($post_id = false) {
	echo $GLOBALS['vl_video']->get_the_launch($post_id);
}

function vl_get_the_launch($post_id = false) {
	return $GLOBALS['vl_video']->get_the_launch($post_id);
}

function vl_has_launch($post_id = false) {
	return $GLOBALS['vl_video']->has_launch($post_id);	
}

function vl_the_director($post_id = false) {
	echo $GLOBALS['vl_video']->get_the_director($post_id);
}

function vl_get_the_director($post_id = false) {
	return $GLOBALS['vl_video']->get_the_director($post_id);
}

function vl_has_director($post_id = false) {
	return $GLOBALS['vl_video']->has_director($post_id);	
}

function vl_the_duration($post_id = false) {
	echo $GLOBALS['vl_video']->get_the_duration($post_id);
}

function vl_get_the_duration($post_id = false) {
	return $GLOBALS['vl_video']->get_the_duration($post_id);
}

function vl_has_duration($post_id = false) {
	return $GLOBALS['vl_video']->has_duration($post_id);	
}

function vl_the_crew($post_id = false) {
	echo $GLOBALS['vl_video']->get_the_crew($post_id);
}

function vl_get_the_crew($post_id = false) {
	return $GLOBALS['vl_video']->get_the_crew($post_id);
}

function vl_has_crew($post_id = false) {
	return $GLOBALS['vl_video']->has_crew($post_id);	
}