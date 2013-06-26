<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

	<section id="program-<?php the_ID(); ?>" <?php post_class(); ?>>

		<header id="program-header" class="program-background">
			<div class="container">
				<div class="twelve columns">
					<h2><a href="<?php echo home_url('/'); ?>" title="<?php _e('Home page', 'videolivre'); ?>"><?php bloginfo('name'); ?></a></h2>
					<h1><?php the_title(); ?></h1>
				</div>
			</div>
		</header>
		<section id="program-meta" class="sub-header">
			<div class="container">
				<div class="eight columns">
					<p class="program-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse volutpat luctus est. Sed eu purus nunc. Proin rutrum sem ut enim ullamcorper accumsan. Fusce venenatis nunc id risus placerat sodales. Nulla aliquet dui vitae arcu porta non dapibus arcu ornare.</p>
				</div>
				<div class="four columns">
					<?php vlchannel_social_shares(); ?>
				</div>
			</div>
		</section>
		<section id="program-videos">
			<div class="container">
				<div class="twelve columns">
					<h2 class="section-title"><?php _e('Videos', 'videolivre'); ?></h2>
				</div>
				<?php
				/*
				 * Featured post
				 */
				$featured = vlchannel_get_program_featured();
				global $post;
				$post = $featured;
				setup_postdata($post);
				get_template_part('card', 'video-featured');
				wp_reset_postdata();
				/*
				 * Video list
				 */
				$query = vlchannel_get_program_query(array(
					'post__not_in' => array($featured->ID)
				));
				$video_query = new WP_Query($query);
				if($video_query->have_posts()) {
					while($video_query->have_posts()) {
						$video_query->the_post();
						get_template_part('card', 'video-small');
					}
				}
				?>
			</div>
		</section>

	</section>

<?php endif; ?>

<?php get_footer(); ?>