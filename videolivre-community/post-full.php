<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header id="posthead" class="site-header clearfix">
		<div class="container">
			<div class="twelve columns">
				<div class="title-area">
					<?php vlchannel_breadcrumb(); ?>
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
		</div>
		<div class="three columns">
			<aside class="sidebar">
				<div class="row">
					<?php dynamic_sidebar('post'); ?>
				</div>
			</aside>
		</div>
	</div>
</article>