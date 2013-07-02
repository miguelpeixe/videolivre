<?php
class VLChannel_Options {

	public function __construct(){
		if(current_user_can('manage_options')){
			add_action('admin_menu', array($this, 'add_plugin_page'));
			add_action('admin_init', array($this, 'page_init'));
		}
	}

	public function add_plugin_page() {
		// This page will be under "Settings"
		add_options_page(__('Channel settings', 'videolivre-channel'), __('Channel settings', 'videolivre-channel'), 'manage_options', 'vlchannel_options', array($this, 'create_admin_page'));
	}

	public function create_admin_page() {
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php _e('Channel settings', 'videolivre-channel'); ?></h2>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields('vlchannel_option_group');	
				do_settings_sections('vlchannel_options');
				?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
	
	public function page_init() {

		register_setting('vlchannel_option_group', 'vlchannel_options', array($this, 'check_data'));

		add_settings_section(
			'vlchannel_basic_setting',
			__('Team and description', 'videolivre-channel'),
			array($this, 'team_section_info'),
			'vlchannel_options'
		);

		add_settings_field(
			'creator',
			__('Creator name', 'videolivre-channel'),
			array($this, 'create_creator_field'),
			'vlchannel_options',
			'vlchannel_basic_setting'
		);

		add_settings_field(
			'producer',
			__('Producer name', 'videolivre-channel'),
			array($this, 'create_producer_field'),
			'vlchannel_options',
			'vlchannel_basic_setting'
		);

		add_settings_field(
			'description',
			__('Channel description', 'videolivre-channel'),
			array($this, 'create_description_field'),
			'vlchannel_options',
			'vlchannel_basic_setting'
		);

		add_settings_section(
			'vlchannel_social_info',
			__('Social links', 'videolivre-channel'),
			array($this, 'social_section_info'),
			'vlchannel_options'
		);

		add_settings_field(
			'facebook',
			'Facebook',
			array($this, 'create_social_facebook_field'),
			'vlchannel_options',
			'vlchannel_social_info'
		);

		add_settings_field(
			'twitter',
			'Twitter',
			array($this, 'create_social_twitter_field'),
			'vlchannel_options',
			'vlchannel_social_info'
		);

		add_settings_field(
			'youtube',
			'Youtube',
			array($this, 'create_social_youtube_field'),
			'vlchannel_options',
			'vlchannel_social_info'
		);

		add_settings_field(
			'vimeo',
			'Vimeo',
			array($this, 'create_social_vimeo_field'),
			'vlchannel_options',
			'vlchannel_social_info'
		);
	}
	
	public function check_data($input){
		/*
		 * Creator
		 */
		if(isset($input['creator'])){
			$mid = $input['creator'];			
			if(get_option('vlchannel_creator') === FALSE){
				add_option('vlchannel_creator', $mid);
			} else {
				update_option('vlchannel_creator', $mid);
			}
		}
		/*
		 * Producer
		 */
		if(isset($input['producer'])){
			$mid = $input['producer'];
			if(get_option('vlchannel_producer') === FALSE){
				add_option('vlchannel_producer', $mid);
			} else {
				update_option('vlchannel_producer', $mid);
			}
		}
		/*
		 * Producer
		 */
		if(isset($input['description'])){
			$mid = $input['description'];			
			if(get_option('vlchannel_description') === FALSE){
				add_option('vlchannel_description', $mid);
			} else {
				update_option('vlchannel_description', $mid);
			}
		}

		/*
		 * Social
		 */
		if(isset($input['facebook'])){
			$mid = $input['facebook'];			
			if(get_option('vlchannel_facebook') === FALSE){
				add_option('vlchannel_facebook', $mid);
			} else {
				update_option('vlchannel_facebook', $mid);
			}
		}
		if(isset($input['twitter'])){
			$mid = $input['twitter'];			
			if(get_option('vlchannel_twitter') === FALSE){
				add_option('vlchannel_twitter', $mid);
			} else {
				update_option('vlchannel_twitter', $mid);
			}
		}
		if(isset($input['youtube'])){
			$mid = $input['youtube'];			
			if(get_option('vlchannel_youtube') === FALSE){
				add_option('vlchannel_youtube', $mid);
			} else {
				update_option('vlchannel_youtube', $mid);
			}
		}
		if(isset($input['vimeo'])){
			$mid = $input['vimeo'];			
			if(get_option('vlchannel_vimeo') === FALSE){
				add_option('vlchannel_vimeo', $mid);
			} else {
				update_option('vlchannel_vimeo', $mid);
			}
		}

	}

	public function team_section_info(){
		echo '<p>' . __('Enter your channel information below:', 'videolivre-channel') , '</p>';
	}

	public function social_section_info(){
		echo '<p>' . __('Fill with links to your connected social networks:', 'videolivre-channel') , '</p>';
	}

	public function create_creator_field() {
		?><input type="text" id="vlchannel_creator" name="vlchannel_options[creator]" value="<?=get_option('vlchannel_creator');?>" /><?php
	}

	public function create_producer_field() {
		?><input type="text" id="vlchannel_producer" name="vlchannel_options[producer]" value="<?=get_option('vlchannel_producer');?>" /><?php
	}

	public function create_description_field() {
		?><textarea type="text" id="vlchannel_description" name="vlchannel_options[description]" rows="10" cols="100"><?=get_option('vlchannel_description');?></textarea><?php
	}

	public function create_social_facebook_field() {
		?><input type="text" id="vlchannel_facebook" name="vlchannel_options[facebook]" size="80" value="<?=get_option('vlchannel_facebook'); ?>" /><?php
	}

	public function create_social_twitter_field() {
		?><input type="text" id="vlchannel_twitter" name="vlchannel_options[twitter]" size="80" value="<?=get_option('vlchannel_twitter'); ?>" /><?php
	}

	public function create_social_youtube_field() {
		?><input type="text" id="vlchannel_youtube" name="vlchannel_options[youtube]" size="80" value="<?=get_option('vlchannel_youtube'); ?>" /><?php
	}

	public function create_social_vimeo_field() {
		?><input type="text" id="vlchannel_vimeo" name="vlchannel_options[vimeo]" size="80" value="<?=get_option('vlchannel_vimeo'); ?>" /><?php
	}
}

$vlchannel_options = new VLChannel_Options();