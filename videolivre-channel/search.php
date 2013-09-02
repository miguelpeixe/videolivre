<?php get_header(); ?>

<?php
$color = get_theme_mod('header_background_color');
$scheme = vl_get_color_scheme($color);

if(defined('IS_VLCOMMUNITY') && class_exists('WP_Query_Multisite')) {
	global $wp_query;
	$wp_query = new WP_Query_Multisite($wp_query->query_vars);
}
?>

<div id="primary" class="site-content">
	<div id="content" role="main">
		<header id="archive-header" class="site-header">
			<div class="container">
				<div class="twelve columns">
					<?php vlchannel_breadcrumb(); ?>
					<h1><?php echo __('Search results for', 'videolivre-channel') . ' "' . $_GET['s'] . '"'; ?></h1>
				</div>
			</div>
		</header>
		<section id="archive-content">
			<div class="container">
				<div class="row">
					<?php
					if(have_posts()) {
						while(have_posts()) {
							the_post();
							$template = 'small';
							if(get_post_type() == 'program')
								$template = 'strip';
							get_template_part(get_post_type(), $template);
						}
					}
					wp_reset_postdata();
					?>
				</div>
				<div class="row">
					<div class="pagination <?php echo $scheme; ?>">
						<div class="twelve columns">
							<?php if(vlchannel_has_next_page()) : ?>
								<span class="older" style="background: <?php echo $color; ?>"><?php next_posts_link(__('Older', 'videolivre'), $video_query->max_num_pages); ?></span>
							<?php endif; ?>
							<?php if(vlchannel_has_prev_page()) : ?>
								<span class="newer" style="background: <?php echo $color; ?>"><?php previous_posts_link(__('Newer', 'videolivre'), $video_query->max_num_pages); ?></span>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<?php get_footer(); ?>