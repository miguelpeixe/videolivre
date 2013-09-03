<?php

query_posts(array(
	'post_type' => 'video',
	'post__not_in' => array($post->ID)
));
if(have_posts()) :
	wp_enqueue_script('vlchannel-carousel');
	?>
	<section id="related-videos" class="content-element video-carousel">
		<div class="container">
			<div class="twelve columns">
				<h3><?php _e('More videos', 'videolivre-channel'); ?></h3>
			</div>
			<div class="carousel-container clearfix">
				<a href="#" title="<?php _e('Back', 'videolivre-channel'); ?>" class="carousel-nav prev left-arrow"><?php _e('Back', 'videolivre-channel'); ?></a>
				<a href="#" title="<?php _e('Forward', 'videolivre-channel'); ?>" class="carousel-nav next right-arrow"><?php _e('Forward', 'videolivre-channel'); ?></a>
				<div class="carousel-items-area">
				<ul class="carousel">
					<?php
					while(have_posts()) :
						the_post();
						echo '<li class="carousel-item"><div class="three columns">';
						get_template_part('video', 'minimal');
						echo '</div></li>';
					endwhile;
					?>
				</ul>
				</div>
			</div>
		</div>
	</section>

<?php endif; ?>
<?php wp_reset_query(); ?>