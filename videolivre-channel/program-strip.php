<div class="clearfix"></div>
<section id="program-<?php the_ID(); ?>" class="section row program-strip">
	<div class="three columns">
		<header class="program-description" style="border-color: <?php echo vl_get_program_color(); ?>;">
			<h3 class="program-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
			<?php the_content(); ?>
		</header>
	</div>
	<div class="nine columns">
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

					if($t == 'minimal') {
						$columns = 'three';
						if($template == 'minimal') {
							if($i == 0) {
								$columns .= ' alpha';
							} elseif($i == 2) {
								$columns .= ' omega';
							}
						} else {
							$columns .= ' omega';
						}
					} else
						$columns = 'six alpha';

					echo '<div class="' . $columns . ' columns">';
					get_template_part('video', $t);
					echo '</div>';
					
					$i++;
				}
			}
			?>
		</section>
	</div>
</section>