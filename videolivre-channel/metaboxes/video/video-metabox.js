google.load('swfobject', '2.1');

var ytplayer;
var html5;

(function($) {

	$(document).ready(function() {

		$('.html5-extras').hide();

		if($('input#video_url').val()) {
			enableHTML5();
			getVideo($('input#video_url').val());
		}

		$('#video_url').keyup(function() {
			enableHTML5();
		});

		$('#video_url').keypress(function(e) {
			if(e.keyCode == 10 || e.keyCode == 13) {
				getVideo($('input#video_url').val());
				return false;
			}
		});

		$('.locate-video').live('click', function() {
			getVideo($('input#video_url').val());
		});

	});

	function checkHTML5(url) {
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

	function enableHTML5() {
		html5 = checkHTML5($('#video_url').val());
		if(html5) {
			$('.html5-extras, .html5-extras input').show();
			if(html5 == 'mp4') {
				$('.html5_mp4').hide();
			} else if(html5 == 'ogv') {
				$('.html5_ogv').hide();
			} else if(html5 == 'webm') {
				$('.html5_webm').hide();
			}

			subtitleLength = $('.subtitle-list li').length;

			$('.add-subtitle').live('click', function() {
				addSubtitle();
				return false;
			});

			$('.remove-subtitle').live('click', function() {
				var $item = $(this).parents('.list-item');
				removeSubtitle($item);
				return false;
			});

			updateSubtitleList();

		} else {
			$('.html5-extras').hide();
		}
	}

	var subtitleLength;

	function addSubtitle() {
		var $subtitleList = $('.subtitle-list');
		var $item = $('.subtitle-list .model').clone();

		$item.find('.subtitle_url input').attr('name', 'subtitles[' + subtitleLength + '][url]');
		$item.find('.subtitle_lang_code input').attr('name', 'subtitles[' + subtitleLength + '][lang-code]');
		$item.find('.subtitle_lang_label input').attr('name', 'subtitles[' + subtitleLength + '][lang-label]');

		$item.removeClass('model').appendTo($subtitleList);

		subtitleLength++;

		updateSubtitleList();
	}

	function removeSubtitle($item) {
		$item.remove();
		updateSubtitleList();
	}

	function updateSubtitleList() {
		var $subtitleContainer = $('.subtitle-container');
		var $subtitleList = $('.subtitle-list');
		var listSize = $subtitleList.find('li').length;

		if(listSize === 1)
			$subtitleContainer.hide();
		else
			$subtitleContainer.show();
	}

	function getVideo(videoURL) {

		var videoSrc, videoSrv;

		// clean up previous
		$('.video-container').empty().append($('<div id="apiplayer"></div>'));

		/*
		 * Validate and extract video ID
		 * Supports:
		 * * YouTube
		 * * Vimeo
		 * * HTML5
		 */

		if(!videoURL) {
			alert(video_metabox_messages.empty_url);
			return false;
		}

		// try html5

		if(html5) {

			$('input.html5_' + html5).val(videoURL);
			videoSrc = [
				$('input.html5_webm').val(),
				$('input.html5_ogv').val(),
				$('input.html5_mp4').val()
			];
			videoSrv = 'html5';

		} else {

			// try youtube url
			var videoSrc = videoURL.split('v=')[1];
			if(videoSrc) {
				var ampersandPosition = videoSrc.indexOf('&');
				if(ampersandPosition != -1) {
				  videoSrc = videoSrc.substring(0, ampersandPosition);
				  videoSrv = 'youtube';
				}
			} else {
				// try vimeo url
				var vimeoRegExp = /http:\/\/(www\.)?vimeo.com\/(\d+)($|\/)/;
				var match = videoURL.match(vimeoRegExp);
				if(match) {
					videoSrc = match[2];
					videoSrv = 'vimeo';
				} else {
					alert(video_metabox_messages.empty_url);
					return false;
				}
			}

		}

		// store video src
		$('input[name="video_src"]').val(videoSrc);
		// store video srv type
		$('input[name="video_srv"]').val(videoSrv);

		loadVideo(videoSrc, videoSrv);

	}

	function loadVideo(videoSrc, videoSrv) {

		var $videoContainer = $('#apiplayer');

		if(videoSrv == 'youtube') {

			/* Embed video with API */

			var params = { allowScriptAccess: 'always' };
			var atts = { id: 'videoplayer' };

			swfobject.embedSWF('http://www.youtube.com/v/' + videoSrc + '?version=3&enablejsapi=1&playerapiid=ytplayer&showinfo=0', 'apiplayer', '480', '295', '9', null, null, params, atts);

		} else if(videoSrv == 'vimeo') {

			$videoContainer.append('<iframe src="http://player.vimeo.com/video/' + videoSrc + '" width="480" height="295" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');

		} else if(videoSrv == 'html5') {

			$('#apiplayer').append('<video width="480" height="295" controls />');

			jQuery.each(videoSrc, function(i, videoUrl) {
				if(videoUrl)
					$videoContainer.find('video').append('<source src="' + videoUrl + '" />');
			});

		}
	}

})(jQuery);