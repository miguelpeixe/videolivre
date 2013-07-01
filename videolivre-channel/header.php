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

	<?php if(is_front_page()) : ?>
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
	<?php endif; ?>

	<?php if(is_front_page()) : ?>
		<section id="channel-meta" class="sub-header">
			<div class="container">
				<div class="four columns">
					<div class="team">
						<p class="idea icon lightbulb"><?php _e('created by', 'videolivre-channel'); ?> <strong>Fulano</strong></p>
						<p class="production icon star"><?php _e('produced by', 'videolivre-channel'); ?> <strong>Cicrano</strong></p>
					</div>
				</div>
				<div class="four columns">
					<ul class="social social-icons">
						<li class="facebook social-item">
							<a href="#" title="<?php _e('Find us on Facebook', 'videolivre-channel'); ?>"><?php _e('Find us on Facebook', 'videolivre-channel'); ?></a>
						</li>
						<li class="twitter social-item">
							<a href="#" title="<?php _e('Find us on Twitter', 'videolivre-channel'); ?>"><?php _e('Find us on Twitter', 'videolivre-channel'); ?></a>
						</li>
						<li class="youtube social-item">
							<a href="#" title="<?php _e('Find us on YouTube', 'videolivre-channel'); ?>"><?php _e('Find us on YouTube', 'videolivre-channel'); ?></a>
						</li>
						<li class="vimeo social-item">
							<a href="#" title="<?php _e('Find us on Vimeo', 'videolivre-channel'); ?>"><?php _e('Find us on Vimeo', 'videolivre-channel'); ?></a>
						</li>
					</ul>
				</div>
				<div class="four columns">
					<?php vlchannel_social_shares(); ?>
				</div>
				<div class="twelve columns">
					<p class="channel-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse volutpat luctus est. Sed eu purus nunc. Proin rutrum sem ut enim ullamcorper accumsan. Fusce venenatis nunc id risus placerat sodales. Nulla aliquet dui vitae arcu porta non dapibus arcu ornare.</p>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<div id="main" class="wrapper">