<?php

class VL_Blog {

	function __construct() {
		add_action('init', array($this, 'init'));
	}

	function init() {
		add_action('generate_rewrite_rules', array($this, 'generate_rewrite_rules'));
		add_action('query_vars', array($this, 'query_vars'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function generate_rewrite_rules($wp_rewrite) {
		$rule = array(
			'blog$' => 'index.php?blog=1',
			'blog/page/?([0-9]{1,})/?$' => 'index.php?blog=1&paged=' . $wp_rewrite->preg_index(1)
		);

		$wp_rewrite->rules = $rule + $wp_rewrite->rules;
	}

	function query_vars($query_vars) {
		$query_vars[] = 'blog';
		return $query_vars;
	}

	function template_redirect() {
		global $wp_query;
		if($wp_query->get('blog')) {
			include(STYLESHEETPATH . '/blog.php');
			exit;
		}
	}
}

$GLOBALS['vl_blog'] = new VL_Blog();

function vl_get_blog_archive_url() {
	return home_url('/blog/');
}