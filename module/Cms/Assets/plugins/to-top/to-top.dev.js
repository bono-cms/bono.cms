
$(function() {

	var id = 'scroller';

	// Create an empty div
	var div = document.createElement(id);

	$(div).attr('id', id);

	// Now append created dummy div
	$("body").append(div);

	var $element = $(div);

	$(window).scroll(function() {
		if ($(this).scrollTop() != 0) {
			$element.fadeIn('slow');
		} else {
			$element.fadeOut();
		}
	});

	$element.click(function() {
		$('body, html').animate({
			scrollTop : 0
		}, 1000);
	});
});
