<?php
/*
 * Video Livre Share Counter
 */

class VLChannel_Shares {

	var $url = false;

	var $post_id = false;

	function __construct($url = false) {

		if(is_int($url)) {
			$this->url = get_permalink($url);
			$this->post_id = $url;
		} else {
			$this->url = get_permalink();
		}

	}

	function get_count() {

		$url = $this->url;

		$url_ref = md5($url);

		$shares = get_transient('shares_' . $url_ref);

		if($shares === false) {

			$shares = array('total' => 0);

			/*
			 * Facebook
			 */

			$fb = json_decode(file_get_contents('http://graph.facebook.com/?ids=' . $url), true);
			$shares['facebook'] = intval($fb[$url]['shares']);

			$shares['total'] = $shares['total'] + $shares['facebook'];

			/*
			 * Twitter
			 */
			$tw = json_decode(file_get_contents('https://cdn.api.twitter.com/1/urls/count.json?url=' . $url), true);
			$shares['twitter'] = intval($tw['count']);

			$shares['total'] = $shares['total'] + $shares['twitter'];

			/*	
			 * Google Plus
			 */
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
			$curl_results = curl_exec ($curl);
			curl_close ($curl);
			$json = json_decode($curl_results, true);

			$shares['gplus'] = intval($json[0]['result']['metadata']['globalCounts']['count']);

			$shares['total'] = $shares['total'] + $shares['gplus'];

			$shares = apply_filters('vlchannel_share_count', $shares, $post_id, $url);

			$this->update_post($shares);

			set_transient('shares_' . $url_ref, $shares, 60*60);

		}

		return $shares;

	}

	function update_post($shares = false) {

		$post_id = $this->post_id;

		if($post_id && $shares) {
			update_post_meta($post_id, '_vlchannel_share_count_total', $shares['total']);
			update_post_meta($post_id, '_vlchannel_share_count', $shares);
		}

		return $post_id;

	}

}

function vlchannel_get_shares($url = false) {
	$shares = new VLChannel_Shares($url);
	return $shares->get_count();
}