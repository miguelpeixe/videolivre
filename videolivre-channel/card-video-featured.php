<div class="twelve columns">
	<article id="<?php echo get_post_type(); ?>-<?php the_ID(); ?>" <?php post_class('featured-video card'); ?>>
		<div class="thumbnail">
			<?php the_post_thumbnail('featured-video'); ?>
		</div>
		<header>
			<h2><?php the_title(); ?></h2>
		</header>
		<section class="description">
			<div class="site-background">
				<?php the_excerpt(); ?>
			</div>
		</section>
		<footer class="video-meta">
			<ul class="status">
				<li class="views icon eye"><?php the_views(); echo ' '; _e('views', 'videolivre-channel'); ?></li>
				<?php if(has_duration()) : ?>
					<li class="length icon clock"><?php the_duration(); ?></li>
				<?php endif; ?>
				<li class="comments icon comment"><?php comments_number('0', '1', '%'); ?></li>
			</ul>
			<?php the_tags('<p class="icon tag tags">', ', ', '</p>'); ?>
		</footer>
	</article>
</div>