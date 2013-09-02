<?php get_header(); ?>
<?php
$channels = vl_get_channels();
?>
<div id="primary" class="site-content">
	<div id="content" role="main">
		<header id="posthead" class="site-header clearfix">
			<div class="container">
				<div class="twelve columns">
					<div class="title-area">
						<?php vl_breadcrumb(); ?>
						<h1><?php _e('Channels', 'videolivre-community'); ?></h1>
					</div>
				</div>
			</div>
		</header>
		<div class="container">
			<section class="channel list">
				<div class="section-subtitle clearfix">
					<div class="nine columns">
						<h3><?php echo vl_channels_get_current_order_label(); ?></h3>
					</div>
					<div class="three columns">
						<?php vl_channels_ordering_dropdown(); ?>
					</div>
				</div>
				<?php
				if($channels) {
					foreach($channels as $channel) {
						switch_to_blog($channel->blog_id);
						get_template_part('channel', 'small');
						restore_current_blog();
					}
				}
				?>
			</section>
		</div>
	</div>
</div>

<?php get_footer(); ?>