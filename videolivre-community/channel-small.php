<?php
//$logo = get_theme_mod('logo_image');
$logo = false;
?>
<div class="row">
	<div class="channel item">
		<div class="six columns">
			<h3 <?php if($logo) echo 'class="logo"'; ?>><a href="<?php echo home_url('/'); ?>">
				<?php bloginfo('title'); ?>
				<?php if($logo) : ?>
					<img src="<?php echo $logo; ?>" alt="<?php bloginfo('title'); ?>" />
				<?php endif; ?>
			</a></h3>
			<?php echo apply_filters('the_content', get_option('vl_description')); ?>
			<a class="button" href="<?php echo home_url('/'); ?>" title="<?php _e('Visit channel', 'videolivre-community'); ?>"><?php _e('Visit channel', 'videolivre-community'); ?></a>
		</div>
		<div>
			<div class="six columns">
				<h4><?php _e('Latest videos', 'videolivre-community'); ?></h4>
			</div>
			<?php
			$latest_videos = new WP_Query(array('post_type' => 'video', 'posts_per_page' => 2));
			if($latest_videos->have_posts()) {
				while($latest_videos->have_posts()) {
					$latest_videos->the_post();
					?><div class="three columns"><?php
					get_template_part('video', 'minimal');
					?></div><?php
				}
			}
			?>
		</div>
	</div>
</div>