<article id="<?php echo get_post_type(); ?>-<?php the_ID(); ?>" <?php post_class('minimal-video card'); ?>>
	<div class="thumbnail">
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="video-thumb">
			<?php if(has_post_thumbnail()) : ?>
				<?php the_post_thumbnail('thumbnail-video', array('class' => 'scale-with-grid')); ?>
			<?php else : ?>
				<img src="<?php echo get_template_directory_uri(); ?>/img/default-thumb.png" class="scale-with-grid" />
			<?php endif; ?>
		</a>
	</div>
	<header>
		<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a> <span class="duration">[<?php vl_the_duration(); ?>]</span></h2>
	</header>
</article>