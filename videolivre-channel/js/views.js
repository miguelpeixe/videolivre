(function($) {

	$(document).ready(function() {

		$.post(vlchannel_views.ajaxurl, {
			action: 'vlchannel_view',
			post_id: vlchannel_views.postid,
			nonce: vlchannel_views.nonce
		});

	});

})(jQuery);