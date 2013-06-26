<?php
if (post_password_required())
	return;
?>

<section id="comments" class="content-element comments-area">

	<?php if(have_comments()) : ?>
		<h3 class="comments-title"><?php _e('Comments', 'videolivre-channel'); ?></h3>

		<ol class="commentlist">
			<?php wp_list_comments(array('callback' => 'vlchannel_comment', 'style' => 'ol')); ?>
		</ol>
	<?php endif; ?>

	<?php comment_form(); ?>

</section>