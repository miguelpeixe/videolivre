google.load('swfobject', '2.1');

var ytplayer;

(function($) {

	$(document).ready(function() {

		if($('input#video_url').val()) {
			getVideo($('input#video_url').val());
		}

		$('#video_url').keypress(function(e) {
			if(e.keyCode == 10 || e.keyCode == 13) {
				getVideo($('input#video_url').val());
				return false;
			}
		});

		$('.locate-video').live('click', function() {
			getVideo($('input#video_url').val());
		});

		$('.html5-fallbacks').hide();
		$('#video_url').keyup(function() {
			var html5 = checkForHTML5($('#video_url').val());
			console.log(html5);
			if(html5) {
				$('.html5-fallbacks, .html5-fallbacks input').show();
				if(html5 == 'mp4') {
					$('.html5_mp4').hide();
				} else if(html5 == 'ogv') {
					$('.html5_ogv').hide();
				} else if(html5 == 'webm') {
					$('.html5_webm').hide();
				}
			} else {
				$('.html5-fallbacks').hide();
			}
		});

	});

	function checkForHTML5(url) {
		//var ext = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).substr(url.lastIndexOf(".");
		var ext = url.split('.').pop().toLowerCase();
		if(ext) {
			if(ext.indexOf('mp4') != -1)
				return 'mp4';
			else if(ext.indexOf('ogv') != -1 || ext.indexOf('ogv') != -1)
				return 'ogv';
			else if(ext.indexOf('webm') != -1)
				return 'webm';
			else
				return false;
		} else
			return false;
	}

	function getVideo(videoURL) {

		// clean up previous
		$('.video-container').empty().append($('<div id="apiplayer"></div>'));

		/*
		 * Validate and extract video ID
		 * Supports:
		 * * YouTube
		 * * Vimeo
		 */

		if(!videoURL) {
			alert(video_metabox_messages.empty_url);
			return false;
		}

		// try youtube url
		var videoSrc = videoURL.split('v=')[1];
		if(videoSrc) {
			var ampersandPosition = videoSrc.indexOf('&');
			if(ampersandPosition != -1) {
			  videoSrc = videoSrc.substring(0, ampersandPosition);
			  loadVideo(videoSrc, 'youtube');
			}
		} else {
			// try vimeo url
			var vimeoRegExp = /http:\/\/(www\.)?vimeo.com\/(\d+)($|\/)/;
			var match = videoURL.match(vimeoRegExp);
			if(match) {
				videoSrc = match[2];
				loadVideo(videoSrc, 'vimeo');
			} else {
				alert(video_metabox_messages.empty_url);
				return false;
			}
		}

		// store video src
		$('input[name="video_src"]').val(videoSrc);

	}

	function loadVideo(videoSrc, videoSrv) {

		if(videoSrv == 'youtube') {

			/* Embed video with API */

			var params = { allowScriptAccess: 'always' };
			var atts = { id: 'videoplayer' };

			swfobject.embedSWF('http://www.youtube.com/v/' + videoSrc + '?version=3&enablejsapi=1&playerapiid=ytplayer&showinfo=0', 'apiplayer', '480', '295', '9', null, null, params, atts);

		} else if(videoSrv == 'vimeo') {

			$('#apiplayer').append('<iframe src="http://player.vimeo.com/video/' + videoSrc + '" width="480" height="295" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');
		}
	}

})(jQuery);