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
					<?php vlchannel_breadcrumb(); ?>
					<?php
					$title = __('Archive', 'videolivre-channel');
					if(is_post_type_archive())
						$title = post_type_archive_title('', false);
					elseif(is_category() || is_tag() || is_tax())
						$title = single_term_title('', false);
					?>
					<h1><?php echo $title ?></h1>
				</div>
			</div>
		</header>
		<section id="archive-content">
			<div class="container">
				<div class="row">
					<?php
					$template = 'small';
					if(get_post_type() == 'program')
						$template = 'featured';
					if(have_posts()) {
						while(have_posts()) {
							the_post();
							get_template_part(get_post_type(), $template);
						}
					}
					?>
				</div>
				<div class="row">
					<div class="pagination <?php echo $scheme; ?>">
						<div class="twelve columns">
							<?php if(vlchannel_has_next_page()) : ?>
								<span class="older" style="background: <?php echo $color; ?>"><?php next_posts_link(__('Older', 'videolivre-channel'), $video_query->max_num_pages); ?></span>
							<?php endif; ?>
							<?php if(vlchannel_has_prev_page()) : ?>
								<span class="newer" style="background: <?php echo $color; ?>"><?php previous_posts_link(__('Newer', 'videolivre-channel'), $video_query->max_num_pages); ?></span>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<?php get_footer(); ?>