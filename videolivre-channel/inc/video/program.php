<?php

/*
 * Video Livre
 * Video programs
 */

class VL_Program extends VL_Video {

	var $program_post_type = 'program';
	var $program_slug = 'programs';

	function __construct() {
		add_action('vl_video_init', array($this, 'init'));
	}

	function init() {
		$this->register_post_type();
		$this->acf_fields();
		$this->featured_field_setup();
		$this->relationship_box_setup();
		$this->redirect_canonical_setup();
		$this->wp_head_setup();
	}

	function register_post_type() {
		$labels = array( 
			'name' => __('Programs', 'videolivre-channel'),
			'singular_name' => __('Program', 'videolivre-channel'),
			'add_new' => __('Add new', 'videolivre-channel'),
			'add_new_item' => __('Add new program', 'videolivre-channel'),
			'edit_item' => __('Edit program', 'videolivre-channel'),
			'new_item' => __('New program', 'videolivre-channel'),
			'view_item' => __('View program', 'videolivre-channel'),
			'search_items' => __('Search programs', 'videolivre-channel'),
			'not_found' => __('No programs found', 'videolivre-channel'),
			'not_found_in_trash' => __('No programs found in trash', 'videolivre-channel'),
			'parent_item_colon' => __('Parent program:', 'videolivre-channel'),
			'menu_name' => __('Programs', 'videolivre-channel'),
		);

		$args = array( 
			'labels' => $labels,
			'hierarchical' => false,
			
			'supports' => array( 'title', 'editor' ),
			
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => false,
			
			'show_in_nav_menus' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => array('slug' => $this->program_slug),
			'capability_type' => 'post'
		);

		register_post_type($this->program_post_type, $args);

		add_action('admin_menu', array($this, 'admin_menu'));

	}

	function admin_menu() {
		add_submenu_page('edit.php?post_type=' . parent::$post_type, __('Programs', 'videolivre-channel'), __('Programs', 'videolivre-channel'), 'edit_posts', 'edit.php?post_type=' . $this->program_post_type);
		add_submenu_page('edit.php?post_type=' . parent::$post_type, __('Add new program', 'videolivre-channel'), __('Add new program', 'videolivre-channel'), 'edit_posts', 'post-new.php?post_type=' . $this->program_post_type);
	}

	function acf_fields() {

		if(function_exists("register_field_group")) {
			register_field_group(array(
				'id' => 'acf_program-configuration',
				'title' => 'Program configuration',
				'fields' => array(
					array(
						'default_value' => '',
						'key' => 'field_51d23642db67f',
						'label' => 'Program color',
						'name' => 'program_color',
						'type' => 'color_picker',
					),
				),
				'location' => array(
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => $this->program_post_type,
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options' => array(
					'position' => 'normal',
					'layout' => 'no_box',
					'hide_on_screen' => array(
					),
				),
				'menu_order' => 0,
			));

			register_field_group(array(
				'id' => 'acf_program-featured-options',
				'title' => __('Featured options', 'videolivre-channel'),
				'fields' => array(
					array(
						'default_value' => 0,
						'message' => __('This program is featured', 'videolivre-channel'),
						'key' => 'field_51d25bf1e15b6',
						'label' => __('Featured program', 'videolivre-channel'),
						'name' => 'featured_program',
						'type' => 'true_false',
						'instructions' => __('Set this program as channel featured. You can only have one featured program on your channel. If more than one is selected, the last uploaded will be selected.', 'videolivre-channel'),
					),
				),
				'location' => array(
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => $this->program_post_type,
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options' => array(
					'position' => 'side',
					'layout' => 'default',
					'hide_on_screen' => array(
					),
				),
				'menu_order' => 0,
			));

		}

	}

	function featured_field_setup() {
		add_filter('vl_video_acf_fields_featured', array($this, 'featured_field'));
	}

	function featured_field($fields) {
		$fields[] = array(
			'default_value' => 0,
			'message' => __('This video is program featured', 'videolivre-channel'),
			'key' => 'field_51d25a60e88f2',
			'label' => __('Program featured', 'videolivre-channel'),
			'name' => 'program_featured',
			'type' => 'true_false',
			'instructions' => __('Set this video as program featured. You can only have one featured video on your program. If more than one is selected, the last uploaded will be selected.', 'videolivre-channel'),
		);
		return $fields;
	}

	function relationship_box_setup() {
		add_filter('postbox_classes_' . parent::$post_type . '_programs_metabox', create_function('', 'return array("general-box");'));
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		add_action('save_post', array($this, 'save_post'));
	}

	function add_meta_boxes() {
		add_meta_box(
			'programs_metabox',
			__('Program', 'videolivre-channel'),
			array($this, 'relationship_box'),
			parent::$post_type,
			'side',
			'default'
		);
	}

	function relationship_box($post) {

		wp_enqueue_style('general-metaboxes', parent::uri() . '/css/metaboxes.css');

		$programs = get_posts(array('post_type' => $this->program_post_type, 'posts_per_page' => -1));
		$video_program = get_post_meta($post->ID, 'program', true);
		?>
		<p class="description">
			<?php _e('Select program to associate your video.', 'videolivre-channel'); ?><br/>
		</p>
		<div class="field relationship">
			<?php if($programs) : ?>

				<ul class="programs relation-list">
					<?php foreach($programs as $program) : ?>
						<li>
							<input id="program_<?php echo $program->ID; ?>" type="radio" name="video_program" value="<?php echo $program->ID; ?>" <?php if($program->ID == $video_program) echo 'checked'; ?> />
							<label for="program_<?php echo $program->ID; ?>"><?php echo $program->post_title; ?></label>
						</li>
					<?php endforeach; ?>
				</ul>

			<?php else : ?>

				<p><?php _e('No programs were found.', 'videolivre-channel'); ?></p>

			<?php endif; ?>
		</div>
		<div class="clearfix"></div>
		<p class="description">
			<?php echo sprintf(__('<a href="%s" target="_blank" title="New program">Click here</a> to create a new program', 'videolivre-channel'), get_admin_url('', 'post-new.php?post_type=program')); ?>
		</p>
		<?php
	}

	function save_post() {
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		if (defined('DOING_AJAX') && DOING_AJAX)
			return;

		if (false !== wp_is_post_revision($post_id))
			return;

		update_post_meta($post_id, 'program', $_POST['video_program']);
	}

	function redirect_canonical_setup() {
		apply_filters('redirect_canonical', array($this, 'redirect_canonical'));
	}

	function redirect_canonical() {
		if(is_singular($this->program_post_type))
			return false;
	}

	/*
	 * Apply program colors on css
	 */

	function wp_head_setup() {
		add_action('wp_head', array($this, 'wp_head'));
	}

	function wp_head() {
		$color = $this->get_program_color();
		?>
		<style type="text/css">
			.program-color-border {
				border-color: <?php echo $color; ?> !important;
			}
			.program-color-border-t {
				border-color: rgba(<?php echo $this->hex2rgb($color); ?>,0.1) !important;
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

	/*
	 * Get video program
	 */
	function get_video_program_id($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		return get_post_meta($post->ID, 'program', true);;
	}

	/*
	 * Get current program color
	 */
	function get_program_color($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;

		if(get_post_type($post_id) != 'program' && get_post_type($post_id) != 'video')
			return false;

		if(get_post_type($post_id) == 'video')
			$post_id = $this->get_video_program_id($post_id);

		$color = get_post_meta($post_id, 'program_color', true);

		if(!$color)
			return get_theme_mod('main_color');

		return $color;
	}

	function get_program_text_scheme() {
		return $this->get_color_scheme($this->get_program_color());
	}

	/*
	 * Get featured program
	 */

	function get_featured_program() {

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

	function get_program_featured($program_id = false) {
		global $post;
		$post_id = $program_id ? $program_id : $post->ID;

		$featured = get_posts($this->get_program_query(array(
			'meta_query' => array(
				array(
					'key' => 'program_featured',
					'value' => 1
				)
			)
		)));

		if(!$featured)
			$featured = get_posts($this->get_program_query());

		if($featured)
			return array_shift($featured);

		return false;
	}

	/*
	 * Get video query
	 */

	function get_program_query($query = array(), $program_id = false) {
		global $post;
		$post_id = $program_id ? $program_id : $post->ID;

		$p_query = array(
			'post_type' => parent::$post_type,
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

		return apply_filters('vl_program_query', $query);
	}

	/*
	 * Helpers
	 */

	// get color brightness

	function get_brightness($hex) {
		// returns brightness value from 0 to 255

		// strip off any leading #
		$hex = str_replace('#', '', $hex);

		$c_r = hexdec(substr($hex, 0, 2));
		$c_g = hexdec(substr($hex, 2, 2));
		$c_b = hexdec(substr($hex, 4, 2));

		return (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;
	}

	// determine light or dark color scheme

	function get_color_scheme($hex) {
		if(!$hex)
			return false;

		if($this->get_brightness($hex) > 130)
			return 'dark-scheme';
		else
			return 'light-scheme';
	}

	// turns hex into rgb

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

}

$GLOBALS['vl_program'] = new VL_Program();

function vl_get_program_color($post_id = false) {
	return $GLOBALS['vl_program']->get_program_color($post_id);
}

function vl_get_program_text_scheme($post_id = false) {
	return $GLOBALS['vl_program']->get_program_text_scheme($post_id);
}

function vl_get_featured_program() {
	return $GLOBALS['vl_program']->get_featured_program();
}

function vl_get_program_featured($program_id = false) {
	return $GLOBALS['vl_program']->get_program_featured();
}

function vl_get_program_query($query = array(), $program_id = false) {
	return $GLOBALS['vl_program']->get_program_query($query, $program_id);
}

function vl_get_video_program_id($post_id = false) {
	return $GLOBALS['vl_program']->get_video_program_id($post_id);
}

function vl_get_color_scheme($hex = false) {
	return $GLOBALS['vl_program']->get_color_scheme($hex);
}