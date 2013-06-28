<section id="program-<?php the_ID(); ?>" class="section clearfix program-featured">
	<div class="six columns">
		<header class="program-description" style="border-color: <?php echo vlchannel_get_program_color(); ?>;">
			<h3 class="program-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
			<?php the_content(); ?>
		</header>
	</div>
	<section class="program-videos">
		<?php
		$query = vlchannel_get_program_query();
		$video_query = new WP_Query($query);
		if($video_query->have_posts()) {
			while($video_query->have_posts()) {
				$video_query->the_post();
				get_template_part('video', 'minimal');
			}
		}
		?>
	</section>
</section>