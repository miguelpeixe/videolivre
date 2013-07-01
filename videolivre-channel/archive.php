<?php get_header(); ?>

<div id="primary" class="site-content">
	<div id="content" role="main">
		<header id="archive-header" class="site-header">
			<div class="container">
				<div class="twelve columns">
					<?php vlchannel_breadcrumb(); ?>
					<?php
					$title = __('Archive', 'videolivre-channel');
					if(is_post_type_archive())
						$title = post_type_archive_title('', false);
					?>
					<h1><?php echo $title ?></h1>
				</div>
			</div>
		</header>
		<section id="archive-content">
			<div class="container">
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
		</section>
	</div>
</div>

<?php get_footer(); ?>