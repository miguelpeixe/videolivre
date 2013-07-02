<header id="videohead" class="clearfix program-color-border">
	<div class="container">
		<div class="ten columns offset-by-one">
			<div class="title-area program-color-border">
				<?php vlchannel_breadcrumb(); ?>
				<h1><?php the_title(); ?></h1>
			</div>
			<div class="video-container">
				<?php vlchannel_video(); ?>
			</div>
		</div>
	</div>
</header>
<section id="video-meta" class="sub-header">
	<div class="container">
		<div class="video-meta-inside clearfix">
			<div class="eight columns">
				<section id="video-specs">
					<ul class="video-specs">
						<li class="production icon calendar"><?php the_date(); ?></li>
						<?php if(has_duration()) : ?>
							<li class="length icon clock"><?php the_duration(); ?></li>
						<?php endif; ?>
						<li class="director icon clapperboard"><?php _e('by', 'videolivre-channel'); ?> <?php the_author(); ?></li>
						<?php if(has_crew()) : ?>
							<li class="tech-crew icon camera toggler"><?php _e('technical crew', 'videolivre-channel'); ?></li>
						<?php endif; ?>
					</ul>
				</section>
			</div>
			<div class="four columns">
				<?php vlchannel_social_shares(get_permalink()); ?>
			</div>
			<?php if(has_crew()) : ?>
				<div class="twelve columns">
					<aside id="tech-crew" class="aside-box">
						<?php the_crew(); ?>
					</aside>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
<section id="video-more">
	<div class="container">
		<div class="seven columns">
			<span class="title"><?php the_title(); ?></span>
			<?php the_content(); ?>
		</div>
		<div class="four columns offset-by-one">
			<aside id="video-widgets">
				<ul class="video-widgets">
					<li class="video-widget program-color-border">
						<ul class="buttons clearfix">
							<li>
								<span class="big-icon icon heart" title="<?php _e('Social share', 'videolivre-channel'); ?>"><?php $shares = vlchannel_get_shares($post->ID); echo $shares['total']; ?></span>
							</li>
							<li>
								<span class="big-icon icon comment" title="<?php _e('Comments', 'videolivre-channel'); ?>"><?php comments_number('0', '1', '%'); ?></span>
							</li>
						</ul>
					</li>
					<li class="video-widget program-color-border">
						<ul class="icon-list">
							<li class="eye views"><?php the_views(); echo ' '; _e('views', 'videolivre-channel'); ?></li>
							<?php the_tags('<li class="tag tags">', ', ', '</li>'); ?>
						</ul>
					</li>
					<?php
					$attachments = get_attachments();
					if($attachments) : ?>
						<li class="video-widget program-color-border">
							<ul class="icon-list">
								<li class="clip attachments">
									<ul class="attachment-list">
										<?php foreach($attachments as $attachment) : ?>
											<li>
												<a href="<?php echo $attachment['url']; ?>" title="<?php echo $attachment['description']; ?>">
													<span class="attachment-title"><?php echo $attachment['name']; ?></span>
													<span class="attachment-base"><?php echo $attachment['base']; ?></span>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>
								</li>
							</ul>
						</li>
					<?php endif; ?>
				</ul>
				<?php 
				$enabled_flag = false;
				if($enable_flag) : ?>
					<p class="flag-video">
						<?php _e('Flag this video', 'videolivre-channel'); ?>
					</p>
				<?php endif; ?>
			</aside>
		</div>
	</div>
</section>

<?php get_template_part('loop', 'related-video'); ?>

<div class="container">
	<div class="twelve columns">
		<?php comments_template('', true); ?>
	</div>
</div>