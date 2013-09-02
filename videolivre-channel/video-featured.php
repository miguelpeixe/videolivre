<div class="twelve columns">
	<article id="<?php echo get_post_type(); ?>-<?php the_ID(); ?>" <?php post_class('featured-video card clearfix'); ?>>
		<div class="clearfix">
			<div class="six columns alpha">
				<div class="thumbnail program-color-border">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="video-thumb"><?php the_post_thumbnail('featured-video', array('class' => 'scale-with-grid')); ?></a>
				</div>
			</div>
			<div class="six columns omega">
				<header>
					<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
				</header>
				<section class="description">
					<div class="site-background">
						<?php the_excerpt(); ?>
					</div>
				</section>
				<footer class="video-meta">
					<ul class="status">
						<li class="views icon eye"><?php vl_the_views(); ?></li>
						<li class="shares icon heart" title="<?php _e('Social share', 'videolivre-channel'); ?>"><?php $shares = vl_get_the_shares($post->ID); echo $shares['total']; ?></li>
						<?php if(vl_has_duration()) : ?>
							<li class="length icon clock"><?php vl_the_duration(); ?></li>
						<?php endif; ?>
						<li class="comments icon comment"><?php comments_number('0', '1', '%'); ?></li>
					</ul>
					<?php the_tags('<p class="icon tag tags">', ', ', '</p>'); ?>
				</footer>
			</div>
		</div>
	</article>
</div>