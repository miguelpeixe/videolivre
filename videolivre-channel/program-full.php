<section id="program-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	$scheme = vl_get_program_text_scheme();
	?>

	<header id="program-header" class="site-header program-background <?php echo $scheme; ?>">
		<div class="container">
			<div class="twelve columns">
				<?php vl_breadcrumb(); ?>
				<h1><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
			</div>
		</div>
	</header>
	<section id="program-meta" class="sub-header">
		<div class="container">
			<div class="eight columns">
				<div class="program-description">
					<div class="description-content">
						<?php the_content(); ?>
					</div>
				</div>
				<a class="readmore" data-opentext="<?php _e('Click to close full description', 'videolivre-channel'); ?>" href="#"><?php _e('Click to read the full description', 'videolivre-channel'); ?></a>
			</div>
			<div class="four columns">
				<?php vlchannel_social_shares(); ?>
			</div>
		</div>
	</section>
	<section id="program-videos">
		<div class="container">
			<?php
			/*
			 * Featured post
			 */
			$featured = vl_get_program_featured();
			if($featured && !is_paged() && !$_REQUEST['order']) {
				global $post;
				$post = $featured;
				setup_postdata($post);
				get_template_part('video', 'featured');
				wp_reset_postdata();
			} else {
				$featured = null;
			}
			/*
			 * Video list
			 */
			$query = vl_get_program_query(array(
				'post__not_in' => array(($featured ? $featured->ID : 0)),
				'vlchannel_order' => $_REQUEST['order']
			));
			query_posts($query);
			?>
			<div class="section-subtitle clearfix">
				<div class="nine columns">
					<h3><?php echo vlchannel_get_current_order_label(); ?></h3>
				</div>
				<div class="three columns">
					<?php vlchannel_custom_ordering_dropdown(); ?>
				</div>
			</div>
			<?php
			if(have_posts()) {
				while(have_posts()) {
					the_post();
					get_template_part('video', 'small');
				}
			}
			?>
			<div class="pagination <?php echo $scheme; ?>">
				<div class="twelve columns">
					<?php if(vlchannel_has_next_page($video_query->max_num_pages)) : ?>
						<span class="older program-background"><?php next_posts_link(__('Older', 'videolivre'), $video_query->max_num_pages); ?></span>
					<?php endif; ?>
					<?php if(vlchannel_has_prev_page()) : ?>
						<span class="newer program-background"><?php previous_posts_link(__('Newer', 'videolivre')); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php wp_reset_query(); ?>
		</div>
	</section>

</section>