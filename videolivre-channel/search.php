<?php

get_header();

$color = get_theme_mod('header_background_color');
$scheme = vl_get_color_scheme($color);

?>

<div id="primary" class="site-content">
	<div id="content" role="main">
		<header id="archive-header" class="site-header <?php echo $scheme; ?>">
			<div class="container">
				<div class="twelve columns">
					<?php vl_breadcrumb(); ?>
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
							if(get_post_type() == 'program') {
								get_template_part(get_post_type(), 'featured');
							} else {
								?><div class="six columns"><?php
								get_template_part(get_post_type(), 'small');
								?></div><?php
							}
						}
					}
					?>
				</div>
				<div class="row">
					<div class="pagination <?php echo $scheme; ?>">
						<div class="twelve columns">
							<?php if(vlchannel_has_next_page()) : ?>
								<span class="older" style="background: <?php echo $color; ?>"><?php next_posts_link(__('Older', 'videolivre')); ?></span>
							<?php endif; ?>
							<?php if(vlchannel_has_prev_page()) : ?>
								<span class="newer" style="background: <?php echo $color; ?>"><?php previous_posts_link(__('Newer', 'videolivre')); ?></span>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<?php
get_footer();
?>