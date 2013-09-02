<?php get_header(); ?>

<?php
$color = get_theme_mod('header_background_color');
$scheme = vl_get_color_scheme($color);
?>

<div id="primary" class="site-content">
	<div id="content" role="main">
		<header id="archive-header" class="site-header <?php echo $scheme; ?>">
			<div class="container">
				<div class="twelve columns">
					<?php vl_breadcrumb(); ?>
					<h1><?php _e('Blog', 'videolivre-community'); ?></h1>
				</div>
			</div>
		</header>
		<section id="archive-content" class="list post">
			<div class="container">
				<div class="row">
					<div class="nine columns">
						<?php
						$template = 'small';
						if(have_posts()) {
							$i = 0;
							while(have_posts()) {
								$class = 'three columns';
								if($i % 3 == 0) $class .= ' alpha';
								if(($i+1) % 3 == 0) $class .= ' omega';
								the_post();
								echo '<div class="' . $class . '">';
								get_template_part(get_post_type(), $template);
								echo '</div>';
								$i++;
							}
						}
						?>
						<div class="clearfix"></div>
						<div class="pagination <?php echo $scheme; ?>">
							<?php if(vlchannel_has_next_page()) : ?>
								<span class="older" style="background: <?php echo $color; ?>"><?php next_posts_link(__('Older', 'videolivre-community'), $video_query->max_num_pages); ?></span>
							<?php endif; ?>
							<?php if(vlchannel_has_prev_page()) : ?>
								<span class="newer" style="background: <?php echo $color; ?>"><?php previous_posts_link(__('Newer', 'videolivre-community'), $video_query->max_num_pages); ?></span>
							<?php endif; ?>
						</div>
					</div>
					<aside class="sidebar">
						<div class="three columns">
							<?php dynamic_sidebar('post'); ?>
						</div>
					</aside>
				</div>
				<div class="row">
				</div>
			</div>
		</section>
	</div>
</div>

<?php get_footer(); ?>