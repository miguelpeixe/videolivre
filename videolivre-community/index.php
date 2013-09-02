<?php get_header(); ?>

<div id="primary" class="site-content container">
	<div id="content" role="main">

		<?php
		$channels = vl_get_channels(2, 0);
		if($channels) :
			?>
			<section class="channel list">
				<div class="twelve columns">
					<h2 class="section-title clearfix">
						<span><?php _e('Active channels', 'videolivre-community'); ?></span>
						<a class="button" href="<?php echo home_url('/channels/'); ?>"><?php _e('All channels', 'videolivre-community'); ?></a>
					</h2>
				</div>
				<?php
				foreach($channels as $channel) :
					switch_to_blog($channel->blog_id);
					get_template_part('channel', 'small');
					restore_current_blog();
				endforeach;
				?>
			</section>
			<?php
		endif;
		?>

		<?php
		query_posts('posts_per_page=3');
		if(have_posts()) :
			?>
			<section id="blog-list" class="list post row">
				<div class="container">
					<div class="twelve columns">
						<h2 class="section-title clearfix">
							<span><?php _e('From the blog', 'videolivre-community'); ?></span>
							<a class="button" href="<?php echo vl_get_blog_archive_url(); ?>"><?php _e('View blog', 'videolivre-community'); ?></a>
						</h2> 
					</div>
				</div>
				<?php
				while(have_posts()) {
					the_post();
					get_template_part('post', 'big');
				}
				?>
			</section>
			<?php
		endif;
		wp_reset_query();
		?>

	</div>
</div>

<?php get_footer(); ?>