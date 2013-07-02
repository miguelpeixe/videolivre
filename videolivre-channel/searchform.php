<?php
if(!vlchannel_get_community()) {
	$search_label = __('Search for videos and programs', 'videolivre-channel');
} else {
	$search_label = __('Search for channels, programs and videos', 'videolivre-channel');
}
?>
<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
	<input type="text" value="" name="s" id="s" placeholder="<?php echo $search_label; ?>" />
	<input type="submit" id="searchsubmit" class="button" value="<?php _e('Search', 'videolivre-channel'); ?>" />
</form>