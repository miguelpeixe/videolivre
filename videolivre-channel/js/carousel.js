var generateCarousel;

jQuery(function($) {

	$(document).ready(function($) {
		var $carouselContainers = $('.carousel-container');
		if($carouselContainers) {
			$carouselContainers.each(function() {
				generateCarousel($(this));
			});
		}
	});

	generateCarousel = function($carouselContainer) {
		var $carousel,
			$next,
			$prev,
			$items,
			itemCount,
			carouselWidth,
			itemWidth,
			currentLocation,
			maxLocation,
			itemOffset;

		$carousel = $carouselContainer.find('.carousel');
		$next = $carouselContainer.find('.carousel-nav.next');
		$prev = $carouselContainer.find('.carousel-nav.prev');
		$items = $carousel.find('.carousel-item');
		itemCount = $items.length;
		itemWidth = $items.width();
		itemOffset = parseInt($items.css('margin-right').replace('px', '')) + parseInt($items.css('margin-left').replace('px', ''));
		carouselWidth = (itemCount * itemWidth) + (itemOffset * itemCount);

		// prepare carousel
		$carousel.width(carouselWidth);

		// navigation

		currentLocation = 0;
		maxLocation = itemCount - 4;
		$next.live('click', function() {
			goNext();
			return false;
		});
		$prev.live('click', function() {
			goPrev();
			return false;
		});

		var goNext = function() {
			if(currentLocation >= maxLocation)
				return false;
			var itemArea = itemWidth + itemOffset;
			$carousel.animate({
				'left': '-=' + itemArea
			}, 100);
			currentLocation++;
		}

		var goPrev = function() {
			if(currentLocation == 0)
				return false;
			var itemArea = itemWidth + itemOffset;
			$carousel.animate({
				'left': '+=' + itemArea
			}, 100);
			currentLocation--;
		}

	}
});