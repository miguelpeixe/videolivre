<?php
class VL_Options {

	var $ID = 'vl_options';

	var $group = 'vl_options_group';

	function __construct() {
		if(current_user_can('manage_options') && !defined('IS_VLCOMMUNITY')) {
			add_action('admin_menu', array($this, 'add_plugin_page'));
			add_action('admin_init', array($this, 'page_init'));
		}
	}

	function add_plugin_page() {
		// This page will be under "Settings"
		add_options_page(__('Channel settings', 'videolivre-channel'), __('Channel settings', 'videolivre-channel'), 'manage_options', $this->ID, array($this, 'create_admin_page'));
	}

	function create_admin_page() {
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php _e('Channel settings', 'videolivre-channel'); ?></h2>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields($this->group);	
				do_settings_sections($this->ID);
				?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
	
	function page_init() {

		register_setting($this->group, $this->ID, array($this, 'check_data'));

		/* 
		 * Team and description
		 */

		add_settings_section(
			'vl_basic_setting',
			__('Team and description', 'videolivre-channel'),
			array($this, 'team_section_info'),
			$this->ID
		);

		add_settings_field(
			'creator',
			__('Creator name', 'videolivre-channel'),
			array($this, 'create_creator_field'),
			$this->ID,
			'vl_basic_setting'
		);

		add_settings_field(
			'producer',
			__('Producer name', 'videolivre-channel'),
			array($this, 'create_producer_field'),
			$this->ID,
			'vl_basic_setting'
		);

		add_settings_field(
			'description',
			__('Channel description', 'videolivre-channel'),
			array($this, 'create_description_field'),
			$this->ID,
			'vl_basic_setting'
		);

		/*
		 * Social
		 */

		add_settings_section(
			'vl_social_info',
			__('Social links', 'videolivre-channel'),
			array($this, 'social_section_info'),
			$this->ID
		);

		add_settings_field(
			'facebook',
			'Facebook',
			array($this, 'create_social_facebook_field'),
			$this->ID,
			'vl_social_info'
		);

		add_settings_field(
			'twitter',
			'Twitter',
			array($this, 'create_social_twitter_field'),
			$this->ID,
			'vl_social_info'
		);

		add_settings_field(
			'youtube',
			'Youtube',
			array($this, 'create_social_youtube_field'),
			$this->ID,
			'vl_social_info'
		);

		add_settings_field(
			'vimeo',
			'Vimeo',
			array($this, 'create_social_vimeo_field'),
			$this->ID,
			'vl_social_info'
		);

		do_action('vl_options_page_init', $this->ID);
	}
	
	function check_data($input){
		/*
		 * Creator
		 */
		if(isset($input['creator'])){
			$mid = $input['creator'];			
			if(get_option('vl_creator') === FALSE){
				add_option('vl_creator', $mid);
			} else {
				update_option('vl_creator', $mid);
			}
		}
		/*
		 * Producer
		 */
		if(isset($input['producer'])){
			$mid = $input['producer'];
			if(get_option('vl_producer') === FALSE){
				add_option('vl_producer', $mid);
			} else {
				update_option('vl_producer', $mid);
			}
		}
		/*
		 * Producer
		 */
		if(isset($input['description'])){
			$mid = $input['description'];			
			if(get_option('vl_description') === FALSE){
				add_option('vl_description', $mid);
			} else {
				update_option('vl_description', $mid);
			}
		}

		/*
		 * Social
		 */
		if(isset($input['facebook'])){
			$mid = $input['facebook'];			
			if(get_option('vl_facebook') === FALSE){
				add_option('vl_facebook', $mid);
			} else {
				update_option('vl_facebook', $mid);
			}
		}
		if(isset($input['twitter'])){
			$mid = $input['twitter'];			
			if(get_option('vl_twitter') === FALSE){
				add_option('vl_twitter', $mid);
			} else {
				update_option('vl_twitter', $mid);
			}
		}
		if(isset($input['youtube'])){
			$mid = $input['youtube'];			
			if(get_option('vl_youtube') === FALSE){
				add_option('vl_youtube', $mid);
			} else {
				update_option('vl_youtube', $mid);
			}
		}
		if(isset($input['vimeo'])){
			$mid = $input['vimeo'];			
			if(get_option('vl_vimeo') === FALSE){
				add_option('vl_vimeo', $mid);
			} else {
				update_option('vl_vimeo', $mid);
			}
		}

		do_action('vl_options_page_save');

	}

	function team_section_info(){
		echo '<p>' . __('Enter your channel information below:', 'videolivre-channel') , '</p>';
	}

	function social_section_info(){
		echo '<p>' . __('Fill with links to your connected social networks:', 'videolivre-channel') , '</p>';
	}

	function create_creator_field() {
		?><input type="text" id="vl_creator" name="vl_options[creator]" value="<?=get_option('vl_creator');?>" /><?php
	}

	function create_producer_field() {
		?><input type="text" id="vl_producer" name="vl_options[producer]" value="<?=get_option('vl_producer');?>" /><?php
	}

	function create_description_field() {
		?><textarea type="text" id="vl_description" name="vl_options[description]" rows="10" cols="100"><?=get_option('vl_description');?></textarea><?php
	}

	function create_social_facebook_field() {
		?><input type="text" id="vl_facebook" name="vl_options[facebook]" size="80" value="<?=get_option('vl_facebook'); ?>" /><?php
	}

	function create_social_twitter_field() {
		?><input type="text" id="vl_twitter" name="vl_options[twitter]" size="80" value="<?=get_option('vl_twitter'); ?>" /><?php
	}

	function create_social_youtube_field() {
		?><input type="text" id="vl_youtube" name="vl_options[youtube]" size="80" value="<?=get_option('vl_youtube'); ?>" /><?php
	}

	function create_social_vimeo_field() {
		?><input type="text" id="vl_vimeo" name="vl_options[vimeo]" size="80" value="<?=get_option('vl_vimeo'); ?>" /><?php
	}
}

$vl_options = new VL_Options();