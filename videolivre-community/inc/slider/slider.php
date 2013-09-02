<?php

/*
 * Video Livre
 * Slider
 */

class VL_Slider {

	function __construct() {
		add_action('init', array($this, 'init'));
	}

	function init() {

		$this->register_post_type();
		$this->acf_fields();
		add_filter('post_link', array($this, 'post_link'));
		add_filter('the_permalink', array($this, 'post_link'));

	}

	function uri() {
		return apply_filters('vl_videos_uri', get_stylesheet_directory_uri() . '/inc/slider');
	}

	function path() {
		return apply_filters('vl_videos_path', STYLESHETPATH . '/inc/slider');
	}

	function register_post_type() {

		$labels = array( 
			'name' => __('Slider', 'toolkit'),
			'singular_name' => __('Slider item', 'toolkit'),
			'add_new' => __('Add slider item', 'toolkit'),
			'add_new_item' => __('Add new slider item', 'toolkit'),
			'edit_item' => __('Edit slider item', 'toolkit'),
			'new_item' => __('New slider item', 'toolkit'),
			'view_item' => __('View slider item', 'toolkit'),
			'search_items' => __('Search slider items', 'toolkit'),
			'not_found' => __('No slider item found', 'toolkit'),
			'not_found_in_trash' => __('No slider item found in the trash', 'toolkit'),
			'menu_name' => __('Featured slider', 'toolkit')
		);

		$args = array( 
			'labels' => $labels,
			'hierarchical' => false,
			'description' => __('Toolkit slider', 'toolkit'),
			'supports' => array('title', 'thumbnail'),
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'has_archive' => false,
			'menu_position' => 2
		);

		register_post_type('slider', $args);

	}

	function acf_fields() {

		/*
		 * ACF Fields
		 */
		if(function_exists("register_field_group")) {

			$translate_fields = array(
				'wysiwyg' => 'wysiwyg',
				'text' => 'text',
				'textarea' => 'textarea'
			);

			if(function_exists('qtrans_getLanguage')) {
				foreach($translate_fields as &$field) {
					$field = 'qtranslate_' . $field;
				}
			}

			register_field_group(array (
				'id' => 'acf_slider-settings',
				'title' => 'Slider settings',
				'fields' => array (
					array (
						'default_value' => '',
						'formatting' => 'br',
						'key' => 'field_slider_description',
						'label' => __('Description', 'videolivre-community'),
						'name' => 'description',
						'type' => 'textarea',
					),
					array (
						'default_value' => '',
						'formatting' => 'html',
						'key' => 'field_slider_title',
						'label' => 'Link',
						'name' => 'slider_url',
						'type' => $translate_fields['text'],
						'instructions' => 'Link to where the slider item will redirect',
						'required' => 1,
					),
					array (
						'default_value' => 0,
						'message' => __('Hide slider title and content (show only featured image)', 'videolivre-community'),
						'key' => 'field_slider_content',
						'label' => __('Hide content', 'videolivre-community'),
						'name' => 'hide_content',
						'type' => 'true_false',
					),
					array (
						'save_format' => 'url',
						'preview_size' => 'medium',
						'library' => 'all',
						'key' => 'field_slider_image',
						'label' => 'Background image',
						'name' => 'background_image',
						'type' => 'image',
					),
					array (
						'default_value' => '#333333',
						'key' => 'field_slider_bgcolor',
						'label' => 'Background color',
						'name' => 'background_color',
						'type' => 'color_picker',
						'instructions' => 'Slide background color',
					),
				),
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'slider',
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

		}

	}

	function post_link($permalink) {
		global $post;
		if(get_post_type() == 'slider')
			return get_field('slider_url');
		return $permalink;
	}

	function slider() {

		query_posts(array('post_type' => 'slider'));

		if(!have_posts())
			return false;

		wp_enqueue_script('vl-slider', $this->uri() . '/js/slider.js', array('jquery'), '1.3');
		wp_enqueue_style('vl-slider', $this->uri() . '/css/slider.css');
		$this->template();
		wp_reset_query();

	}

	function template() {
		?>
		<section class="featured-slider">
			<ul class="slider-items">
				<?php $i = 0; while(have_posts()) : the_post(); ?>
					<?php
					$featured_image = get_field('background_image');
					$bg_color = get_field('background_color');
					$hide_content = get_field('hide_content');
					$description = get_field('description');
					?>
					<li data-sliderid="item-<?php echo $i; ?>" class="slider-item" <?php if($bg_color) : ?> style="background-color: <?php echo $bg_color; ?>" <?php endif; ?>>
						<?php if($featured_image) : ?>
							<div class="stage-image-container">
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo $featured_image; ?>" alt="<?php the_title(); ?>" ></a>
							</div>
						<?php endif; ?>
						<?php if(!$hide_content) : ?>
							<div class="container">
								<div class="item-content">
									<div class="twelve columns">
										<h2><a href="<?php echo get_field('slider_url'); ?>" titlte="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
									</div>
									<div class="four columns">
										<?php if($description) echo apply_filters('the_content', $description); ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="slider-link"></a>
					</li>
				<?php $i++; endwhile; ?>
			</ul>
			<div class="slider-controllers-container">
				<ol class="slider-controllers">
					<?php $i = 0; while(have_posts()) : the_post(); ?>
						<li data-sliderid="item-<?php echo $i; ?>"></li>
					<?php $i++; endwhile; ?>
				</ol>
			</div>
		</section>
		<?php
	}

}

$GLOBALS['vl_slider'] = new VL_Slider();