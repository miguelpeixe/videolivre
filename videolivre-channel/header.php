<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<title><?php wp_title(); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/img/favicon.ico">
<link rel="apple-touch-icon" href="<?php bloginfo('stylesheet_directory'); ?>/img/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php bloginfo('stylesheet_directory'); ?>/img/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo('stylesheet_directory'); ?>/img/apple-touch-icon-114x114.png">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<!-- Facebook code -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=174607379284946";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- End of Facebook code -->

<div id="page" class="hfeed site">

	<?php if(is_multisite()) community_header(); ?>

	<?php do_action('vl_before_header'); ?>

	<?php if(is_front_page() && !defined('IS_VLCOMMUNITY')) : ?>
		<header id="masthead" role="banner">
			<hgroup>
				<?php
				$header_image = get_header_image();
				$logo = get_theme_mod('logo_image');
				if ( ! empty( $header_image ) ) : ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></a>
						<div class="left-gradient"></div>
						<div class="right-gradient"></div>
						<div class="bottom-gradient"></div>
				<?php endif; ?>
				<div class="header-content <?php if(!empty($header_image)) echo 'with-image'; ?>">
					<div class="container">
						<div class="twelve columns">
							<?php if(!empty($logo)) { ?>

								<h1 class="site-title logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo( 'name' ); ?><img src="<?php echo $logo; ?>" alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" /></a></h1>

							<?php } else { ?>

								<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
								
								<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
								
							<?php } ?>
						</div>
					</div>
				</div>
			</hgroup>
			<?php /*
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<?php wp_nav_menu(array('theme_location' => 'primary', 'menu_class' => 'nav-menu')); ?>
			</nav><!-- #site-navigation -->
			*/ ?>
		</header><!-- #masthead -->
		<?php
		$producer = get_option('vl_producer');
		$creator = get_option('vl_creator');

		$description = get_option('vl_description');

		$fb = get_option('vl_facebook');
		$tw = get_option('vl_twitter');
		$yt = get_option('vl_youtube');
		$vm = get_option('vl_vimeo');
		?>
		<section id="channel-meta" class="sub-header">
			<div class="container">
				<div class="four columns">
					<?php if($producer || $creator) : ?>
						<div class="team">
							<?php if($creator) : ?>
								<p class="idea icon lightbulb"><?php _e('created by', 'videolivre-channel'); ?> <strong><?php echo $creator; ?></strong></p>
							<?php endif; ?>
							<?php if($producer) : ?>
								<p class="production icon star"><?php _e('produced by', 'videolivre-channel'); ?> <strong><?php echo $producer; ?></strong></p>
							<?php endif; ?>
						</div>
						&nbsp;
					<?php endif; ?>
				</div>
				<div class="four columns">
					<?php if($fb || $tw || $yt || $vm) : ?>
						<ul class="social social-icons">
							<?php if($fb) : ?>
								<li class="facebook social-item">
									<a href="<?php echo $fb; ?>" title="<?php _e('Find us on Facebook', 'videolivre-channel'); ?>" rel="external" target="_blank"><?php _e('Find us on Facebook', 'videolivre-channel'); ?></a>
								</li>
							<?php endif; ?>
							<?php if($tw) : ?>
								<li class="twitter social-item">
									<a href="<?php echo $tw; ?>" title="<?php _e('Find us on Twitter', 'videolivre-channel'); ?>" rel="external" target="_blank"><?php _e('Find us on Twitter', 'videolivre-channel'); ?></a>
								</li>
							<?php endif; ?>
							<?php if($yt) : ?>
								<li class="youtube social-item">
									<a href="<?php echo $yt; ?>" title="<?php _e('Find us on YouTube', 'videolivre-channel'); ?>" rel="external" target="_blank"><?php _e('Find us on YouTube', 'videolivre-channel'); ?></a>
								</li>
							<?php endif; ?>
							<?php if($vm) : ?>
								<li class="vimeo social-item">
									<a href="<?php echo $vm; ?>" title="<?php _e('Find us on Vimeo', 'videolivre-channel'); ?>" rel="external" target="_blank"><?php _e('Find us on Vimeo', 'videolivre-channel'); ?></a>
								</li>
							<?php endif; ?>
						</ul>
					<?php endif; ?>
					&nbsp;
				</div>
				<?php if($producer || $creator || $fb || $tw || $yt || $vm) : ?>
					<div class="four columns">
						<?php vlchannel_social_shares(); ?>
						&nbsp;
					</div>
				<?php endif; ?>
				<?php if($description) : ?>
					<div class="twelve columns">
						<p class="channel-description"><?php echo $description; ?></p>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php do_action('vl_after_header'); ?>

	<div id="main" class="wrapper">