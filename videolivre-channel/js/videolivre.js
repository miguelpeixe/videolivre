(function($) {

	/*
	 * Tech crew toggler
	 */
	$(document).ready(function() {

		if($('#tech-crew').length) {

			var height = $('#tech-crew').height();
			$('#tech-crew').height(0);
			$('.video-specs .tech-crew.toggler').toggle(function() {
				$(this).addClass('active');
				$('#tech-crew').css({'border-width' : '1px'});
				$('#tech-crew').animate({
					height: height,
					paddingTop: 20,
					paddingBottom: 20
				}, 200);
			}, function() {
				$(this).removeClass('active');
				$('#tech-crew').css({'border-width' : '0px'});
				$('#tech-crew').animate({
					height: 0,
					paddingTop: 0,
					paddingBottom: 0
				}, 200);
			});

		}

		if($('#program-meta').length) {
			var $container = $('#program-meta .program-description');
			var toggledHeight = $container.height();
			var descriptionHeight = $container.find('> div').height();

			var closedText = $('#program-meta .readmore').text();
			var openedText = $('#program-meta .readmore').data('opentext');

			if(toggledHeight >= descriptionHeight) {

				$('#program-meta .readmore').hide();

			} else {

				$('#program-meta .readmore').toggle(function() {
					var $el = $(this);
					$container.animate({
						'height': descriptionHeight
					}, function() {
						$el.text(openedText);
					});
					return false;
				}, function() {
					var $el = $(this);
					$container.animate({
						'height': toggledHeight
					}, function() {
						$el.text(closedText);
					});				
					return false;
				});

			}
		}

	});

})(jQuery);