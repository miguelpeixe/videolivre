<?php

/*
 * Verify community
 * Returns false or ID of community site
 */
function vlchannel_get_community() {
	if(is_multisite()) {
		global $current_site;
		global $wpdb;

		$main_blog = $wpdb->get_var ($wpdb->prepare ( "SELECT `blog_id` FROM `$wpdb->blogs` WHERE `domain` = '%s' AND `path` = '%s' ORDER BY `blog_id` ASC LIMIT 1", $current_site->domain, $current_site->path ));

		switch_to_blog($main_blog);
		$theme = get_current_theme();
		restore_current_blog();

		$community_theme = vlchannel_get_community_theme();

		if($theme == $community_theme)
			return $main_blog;
		else
			return false;
	}
	return false;
}

/*
 * Community theme name
 */
function vlchannel_get_community_theme() {
	return apply_filters('vlchannel_community_theme', 'Video Livre Community');
}

/*
 * Community header in case of multisite installation
 */
function community_header() {
	$community = vlchannel_get_community();
	if(!$community)
		return false;
	?>
	<aside id="community-header">
		<div class="container">
			<div class="three columns">
				<h2>Logo</h2>
			</div>
			<div class="six columns">
				<?php get_search_form(); ?>
			</div>
			<div class="three columns">
				<div class="meta-links">
					<?php if(is_user_logged_in()) : ?>
						<span class="login">
							<a class="icon login" href="<?php echo vlchannel_login_url(); ?>"><?php _e('Login', 'videolivre-channel'); ?></a>
						</span>
						<span class="register">
							<a class="icon register" href="<?php echo vlchannel_register_url(); ?>"><?php _e('Register', 'videolivre-channel'); ?></a>
						</span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</aside>
	<?php
}