<div class="three columns">
	<article id="<?php echo get_post_type(); ?>-<?php the_ID(); ?>" <?php post_class('minimal-video card'); ?>>
		<div class="thumbnail">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('thumbnail-video', array('class' => 'scale-with-grid')); ?></a>
		</div>
		<header>
			<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a> <span class="duration">[<?php the_duration(); ?>]</span></h2>
		</header>
	</article>
</div>