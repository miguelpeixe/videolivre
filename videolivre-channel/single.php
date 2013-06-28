<?php get_header(); ?>

<div id="primary" class="site-content">
	<div id="content" role="main">
		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
			<?php get_template_part(get_post_type(), 'full'); ?>
		<?php endwhile; endif; ?>
	</div>
</div>

<?php get_footer(); ?>