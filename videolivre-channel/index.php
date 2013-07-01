<?php get_header(); ?>

<div id="primary" class="site-content container">
	<div id="content" role="main">
		<?php
		/*
		 * Featured program
		 */
		$featured = vlchannel_get_featured_program();
		if($featured && !is_paged()) {
			global $post;
			$post = $featured;
			setup_postdata($post);
			get_template_part('program', 'featured');
			wp_reset_postdata();
		} else {
			$featured = null;
		}
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
		if(have_posts()) {
			?>
			<div class="twelve columns">
				<h2 class="section-title"><?php _e('More programs', 'videolivre-channel'); ?></h2>
			</div>
			<?php
			while(have_posts()) {
				the_post();
				get_template_part('program', 'strip');
			}
		}
		/*
		 * Latest videos
		 */
		?>
		<div class="twelve columns">
			<h2 class="section-title"><?php _e('Latest videos', 'videolivre-channel'); ?></h2>
		</div>
		<?php
		$query = array(
			'post_type' => 'video',
			'posts_per_page' => 8,
		);
		query_posts($query);
		if(have_posts()) {
			while(have_posts()) {
				the_post();
				get_template_part('video', 'minimal');
			}
		}
		?>
	</div>
</div>

<?php get_footer(); ?>