<article id="<?php echo get_post_type(); ?>-<?php the_ID(); ?>" <?php post_class('list-video card'); ?>>
	<div class="clearfix">
		<div class="three columns alpha">
			<div class="thumbnail" style="border-color: <?php echo vl_get_program_color(); ?>;">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="video-thumb">
					<?php if(has_post_thumbnail()) : ?>
						<?php the_post_thumbnail('thumbnail-video', array('class' => 'scale-with-grid')); ?>
					<?php else : ?>
						<img src="<?php echo get_template_directory_uri(); ?>/img/default-thumb.png" class="scale-with-grid" />
					<?php endif; ?>
				</a>
			</div>
		</div>
		<div class="three columns omega">
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
				</ul>
			</footer>
		</div>
	</div>
</article>