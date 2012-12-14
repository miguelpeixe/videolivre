<?php
/*
 * Community header in case of multisite installation
 */

function community_header() {
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
							<a href="<?php echo vlchannel_login_url(); ?>"><?php _e('Login', 'videolivre-channel'); ?></a>
						</span>
						<span class="register">
							<a href="<?php echo vlchannel_register_url(); ?>"><?php _e('Register', 'videolivre-channel'); ?></a>
						</span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</aside>
	<?php
}