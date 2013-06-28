<?php get_header(); ?>

<div id="primary" class="site-content container">
	<div id="content" role="main">
		<?php
		query_posts(array(
			'post_type' => 'program'
		));
		if(have_posts()) {
			while(have_posts()) {
				the_post();
				get_template_part('program', 'featured');
			}
		}
		wp_reset_query();
		?>
		<?php
		query_posts(array(
			'post_type' => 'program'
		));
		if(have_posts()) {
			while(have_posts()) {
				the_post();
				get_template_part('program', 'strip');
			}
		}
		wp_reset_query();
		?>
	</div>
</div>

<?php get_footer(); ?>