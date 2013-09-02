<?php get_header(); ?>

<div id="primary" class="site-content">
	<div id="content" role="main">
		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header id="posthead" class="site-header clearfix">
					<div class="container">
						<div class="twelve columns">
							<div class="title-area">
								<?php vl_breadcrumb(); ?>
								<h1><?php the_title(); ?></h1>
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
				</div>
			</article>

		<?php endwhile; endif; ?>
	</div>
</div>

<?php get_footer(); ?>