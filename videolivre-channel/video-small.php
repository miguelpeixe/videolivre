<div class="six columns">
	<article id="<?php echo get_post_type(); ?>-<?php the_ID(); ?>" <?php post_class('list-video card'); ?>>
		<div class="thumbnail" style="border-color: <?php echo vlchannel_get_program_color(); ?>;">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('thumbnail-video', array('class' => 'scale-with-grid')); ?></a>
		</div>
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
				<li class="views icon eye"><?php the_views(); ?></li>
				<?php if(has_duration()) : ?>
					<li class="length icon clock"><?php the_duration(); ?></li>
				<?php endif; ?>
			</ul>
		</footer>
	</article>
</div>