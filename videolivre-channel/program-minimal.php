<div class="three columns">
	<article id="program-<?php the_ID(); ?>" <?php post_class('minimal-program card'); ?>>
		<header style="background-color: <?php echo vl_get_program_color(); ?>;">
			<h3 class="program-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
		</header>
	</article>
</div>