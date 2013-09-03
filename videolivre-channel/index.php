<?php get_header(); ?>

<div id="primary" class="site-content container">
	<div id="content" role="main">
		<?php
		/*
		 * Featured video
		 */
		$featured_video = get_posts(array(
			'post_type' => 'video',
			'meta_query' => array(
				array(
					'key' => 'channel_featured',
					'value' => 1
				)
			)
		));
		if($featured_video) :
			global $post;
			$post = array_shift($featured_video);
			setup_postdata($post);
			get_template_part('video', 'featured');
			wp_reset_postdata();
		else :
			$featured_video = null;
		endif;
		/*
		 * Featured program
		 */
		$featured = vl_get_featured_program();
		if($featured && !is_paged()) :
			global $post;
			$post = $featured;
			setup_postdata($post);
			get_template_part('program', 'featured');
			wp_reset_postdata();
		else :
			$featured = null;
		endif;
		/*
		 * Program list
		 */
		?>
		<?php
		$query = array(
			'post_type' => 'program',
			'posts_per_page' => 2,
			'post__not_in' => array(($featured ? $featured->ID : 0)),
		);
		query_posts($query);
		if(have_posts()) :
			?>
			<div class="twelve columns">
				<h2 class="section-title clearfix">
					<span><?php _e('More programs', 'videolivre-channel'); ?></span>
					<a class="button" href="<?php echo get_post_type_archive_link('program'); ?>"><?php _e('All programs', 'videolivre-channel'); ?></a>
				</h2>
			</div>
			<?php
			while(have_posts()) :
				the_post();
				get_template_part('program', 'strip');
			endwhile;
		endif;
		/*
		 * Latest videos
		 */
		$query = array(
			'post_type' => 'video',
			'posts_per_page' => 8,
		);
		query_posts($query);
		if(have_posts()) :
			?>
			<div class="twelve columns">
				<h2 class="section-title clearfix">
					<span><?php _e('Latest videos', 'videolivre-channel'); ?></span>
					<a class="button" href="<?php echo get_post_type_archive_link('video'); ?>"><?php _e('All videos', 'videolivre-channel'); ?></a>
				</h2>
			</div>
			<?php
			while(have_posts()) :
				the_post();
				?><div class="three columns"><?php
				get_template_part('video', 'minimal');
				?></div><?php
			endwhile;
		endif;
		?>
	</div>
</div>

<?php get_footer(); ?>