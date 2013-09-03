<?php
global $switched;
if(!defined('IS_VLCOMMUNITY') && vl_get_community() != $switched) {
	$search_label = __('Search for videos and programs', 'videolivre-channel');
	$name = 's';
} else {
	$search_label = __('Search for channels, programs and videos', 'videolivre-channel');
	$name = 's';
}
?>
<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
	<input type="text" value="<?php if(isset($_GET[$name])) echo $_GET[$name]; ?>" name="<?php echo $name; ?>" id="s" placeholder="<?php echo $search_label; ?>" />
	<input type="submit" id="searchsubmit" class="button" value="<?php _e('Search', 'videolivre-channel'); ?>" />
</form>