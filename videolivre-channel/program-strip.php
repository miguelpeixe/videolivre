<div class="clearfix"></div>
<section id="program-<?php the_ID(); ?>" class="section program-strip">
	<div class="three columns">
		<header class="program-description" style="border-color: <?php echo vl_get_program_color(); ?>;">
			<h3 class="program-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
			<?php the_content(); ?>
		</header>
	</div>
	<section class="program-videos">
		<?php
		$query = vl_get_program_query(array(
			'posts_per_page' => 3
		));
		$video_query = new WP_Query($query);
		$template = 'minimal';
		if($video_query->found_posts == 2)
			$template = array('small', 'minimal');
		elseif($video_query->found_posts == 1)
			$template = 'small';

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