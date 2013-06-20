<?php get_header(); ?>

<div id="primary" class="site-content">
	<div id="content" role="main">
		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
			<?php get_template_part('content', get_post_type()); ?>
		<?php endwhile; endif; ?>
	</div>
</div>

<?php get_footer(); ?>