(function($) {

	$(document).ready(function() {

		$.post(vl_views.ajaxurl, {
			action: 'vl_view',
			post_id: vl_views.postid,
			nonce: vl_views.nonce
		});

	});

})(jQuery);