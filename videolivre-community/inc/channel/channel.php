<?php

/**
 * Video Livre
 * Channel
 */

class VL_Channel {
	
	function __construct() {
		add_action('init', array($this, 'init'));
	}

	function init() {
		add_filter('query_vars', array($this, 'query_vars'));
		add_action('generate_rewrite_rules', array($this, 'generate_rewrite_rules'));
		add_action('template_redirect', array($this, 'template_redirect'));
		add_filter('vl_channels_query_order', array($this, 'query_order'), 1, 1);
	}

	function get_channels($amount = 10, $offset = 0) {

		global $wpdb;

		$channel_theme = vl_get_channel_theme();
		$channels = array();

		// Query all blogs from multi-site install

		$query = " SELECT blog_id,domain,path,last_updated,registered FROM {$wpdb->blogs} ";

		// WHERE
		$query .= apply_filters('vl_channels_query_where', "
			WHERE site_id = '{$wpdb->siteid}'
				AND blog_id != '1'
				AND public = '1'
				AND archived = '0'
				AND mature = '0'
				AND spam = '0'
				AND deleted = '0'
			");

		// ORDER
		$query .= apply_filters('vl_channels_query_order', " ORDER BY last_updated DESC ");

		// LIMIT
		$query .= apply_filters('vl_channels_query_limit', " LIMIT {$offset}, {$amount} ");

		$query = apply_filters('vl_channels_query', $query);

		$blogs = $wpdb->get_results($query);

		// For each blog search for blog name in respective options table
		foreach( $blogs as $blog ) {
			switch_to_blog($blog->blog_id);
			$theme = get_current_theme();
			if($theme == $channel_theme)
				$channels[] = $blog;
			restore_current_blog();
		}

		if(empty($channels))
			$channels = false;

		//$GLOBAL['vl_channels'] = $channels;

		return $channels;
	}

	function query_vars($vars) {
		$vars[] = 'vl_channels';
		$vars[] = 'vl_channels_ordering';
		return $vars;
	}

	function generate_rewrite_rules($wp_rewrite) {
		$rule = array(
			'channels$' => 'index.php?vl_channels=1'
		);
		$wp_rewrite->rules = $rule + $wp_rewrite->rules;
	}

	function template_redirect() {
		if(get_query_var('vl_channels')) {
			$this->list_template();
			exit;
		}
	}

	function list_template() {
		get_template_part('content', 'channels');
	}

	function query_order($order) {
		$current = get_query_var('vl_channels_ordering');
		if($current) {
			if($current == 'registered_desc') {
				$order = " ORDER BY registered DESC ";
			} elseif($current == 'registered_asc') {
				$order = " ORDER BY registered ASC ";
			}
		}
		return $order;
	}

	function ordering_labels() {
		return apply_filters('vl_channels_ordering_labels', array(
			'last_updated' => __('Last updated', 'videolivre-community'),
			'registered_desc' => __('New channels', 'videolivre-community'),
			'registered_asc' => __('Old channels', 'videolivre-community')
		));
	}

	function get_current_order_label() {
		$current = get_query_var('vl_channels_ordering');
		if(!$current)
			$current = 'last_updated';

		$labels = $this->ordering_labels();

		return $labels[$current];
	}

	function ordering_dropdown() {
		$current = get_query_var('vl_channels_ordering');
		if(!$current)
			$current = 'last_updated';

		$labels = $this->ordering_labels();

		$available = $labels;
		unset($available[$current]);

		global $wp;

		?>
		<div class="ordering-dropdown">
			<p class="title"><?php _e('Order by', 'videolivre-community'); ?></p>
			<div class="choices button program-color-border">
				<p class="current"><?php echo $labels[$current]; ?></p>
				<ul class="list program-color-border">
					<?php foreach($available as $key => $label) : ?>
						<li class="<?php echo $key; ?> choice"><a href="<?php echo add_query_arg('vl_channels_ordering', $key); ?>" title="<?php echo $label; ?>"><?php echo $label; ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php
	}

}

$GLOBALS['vl_channel'] = new VL_Channel();

function vl_get_channels($amount = 10, $offset = 0) {
	return $GLOBALS['vl_channel']->get_channels($amount, $offset);
}

function vl_channels_get_current_order_label() {
	return $GLOBALS['vl_channel']->get_current_order_label();
}

function vl_channels_ordering_dropdown() {
	return $GLOBALS['vl_channel']->ordering_dropdown();
}