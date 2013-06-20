<?php get_header(); ?>

<div id="primary" class="site-content container">
	<div id="content" role="main">
		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
			<h2 class="main-color-text"><?php the_title(); ?></h2>
			<?php the_content(); ?>
		<?php endwhile; endif; ?>
	</div>
</div>

<?php get_footer(); ?>