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

	});

})(jQuery);