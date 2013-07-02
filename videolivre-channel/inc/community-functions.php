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
	if($community)
		switch_to_blog(1);

	$title = get_bloginfo('name');
	$home_url = home_url('/');

	if($community)
		restore_current_blog();

	?>
	<aside id="community-header">
		<div class="container">
			<div class="<?php if(!is_user_logged_in() && !get_option('users_can_register')) echo 'six'; else echo 'three'; ?> columns">
				<h2><a href="<?php echo $home_url; ?>"><?php echo $title; ?></a></h2>
			</div>
			<div class="six columns">
				<?php get_search_form(); ?>
			</div>
			<?php if(is_user_logged_in() || get_option('users_can_register')) : ?>
				<div class="three columns">
					<div class="meta-links">
						<?php if(!is_user_logged_in()) : ?>
							<?php if(get_option('users_can_register')) : ?>
								<span class="login">
									<a class="icon login" href="<?php echo vlchannel_login_url(); ?>"><?php _e('Login', 'videolivre-channel'); ?></a>
								</span>
								<span class="register">
									<a class="icon register" href="<?php echo vlchannel_register_url(); ?>"><?php _e('Register', 'videolivre-channel'); ?></a>
								</span>
							<?php endif; ?>
						<?php else : ?>
							<?php
							$user = wp_get_current_user();
							$active_signup = get_site_option('registration');
							?>
							<div class="dropdown user">
								<span class="title"><a href="<?php echo get_edit_user_link(); ?>" title="<?php _e('Edit profile', 'videolivre-channel'); ?>"><?php echo $user->display_name; ?></a></span>
								<ul class="options">
									<li><a href="<?php echo get_edit_user_link(); ?>" title="<?php _e('Edit profile', 'videolivre-channel'); ?>"><?php _e('Edit profile', 'videolivre-channel'); ?></a></li>
									<?php if(current_user_can('edit_posts')) : ?>
										<li><a href="<?php echo admin_url('/post-new.php?post_type=video'); ?>"><?php _e('New video', 'videolivre-channel'); ?></a></li>
										<li><a href="<?php echo admin_url('/post-new.php?post_type=program'); ?>"><?php _e('New program', 'videolivre-channel'); ?></a></li>
										<li><a href="<?php echo admin_url(); ?>"><?php _e('Dashboard', 'videolivre-channel'); ?></a></li>
									<?php endif; ?>
									<?php if($active_signup == 'all' || $active_signup == 'blog') : ?>
										<li><a href="<?php echo vlchannel_register_url(); ?>"><?php _e('Create new video channel', 'videolivre-channel'); ?></a></li>
									<?php endif; ?>
									<li><a href="<?php echo vlchannel_logout_url(); ?>"><?php _e('Logout', 'videolivre-channel'); ?></a></li>
								</ul>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</aside>
	<?php
}