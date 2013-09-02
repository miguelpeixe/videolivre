<div class="four columns">
	<article id="post-<?php the_ID(); ?>" <?php post_class('post item small'); ?>>
		<header class="post-header">
			<?php if(has_post_thumbnail()) : ?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_post_thumbnail('featured-video', array('class' => 'scale-with-grid')); ?>
				</a>
			<?php endif; ?>
			<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
			<p class="date"><?php echo get_the_date(); ?></p>
		</header>
		<?php the_excerpt(); ?>
	</article>
</div>