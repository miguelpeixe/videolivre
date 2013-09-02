<div class="clearfix"></div>
<section id="program-<?php the_ID(); ?>" class="section program-featured">
	<div class="six columns">
		<header class="program-description" style="border-color: <?php echo vl_get_program_color(); ?>;">
			<h3 class="program-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
			<?php the_content(); ?>
		</header>
	</div>
	<section class="program-videos">
		<?php
		$query = vl_get_program_query();
		$video_query = new WP_Query($query);
		$template = 'minimal';
		if($video_query->found_posts <= 2)
			$template = 'small';
		elseif($video_query->found_posts == 3)
			$template = array('small', 'minimal', 'minimal');
		if($video_query->have_posts()) {
			$i = 0;
			while($video_query->have_posts()) {
				$t = $template;
				if(is_array($template))
					$t = $template[$i];
				$video_query->the_post();
				get_template_part('video', $t);
				$i++;
			}
		}
		?>
	</section>
</section>