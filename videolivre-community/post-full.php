<?php
$color = get_theme_mod('header_background_color');
$scheme = vl_get_color_scheme($color);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header id="posthead" class="site-header clearfix <?php echo $scheme; ?>">
		<div class="container">
			<div class="twelve columns">
				<div class="title-area">
					<?php vl_breadcrumb(); ?>
					<h1><?php the_title(); ?></h1>
					<p class="date"><?php the_date(); ?></p>
				</div>
			</div>
		</div>
	</header>
	<div class="container">
		<div class="eight columns">
			<section class="post-content">
				<div class="row">
					<?php the_content(); ?>
				</div>
			</section>
			<div class="row">
				<?php comments_template('', true); ?>
			</div>
		</div>
		<div class="three columns">
			<aside class="sidebar">
				<div class="row">
					<div class="three columns">	
						<?php dynamic_sidebar('post'); ?>
					</div>
				</div>
			</aside>
		</div>
	</div>
</article>