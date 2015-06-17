
/**
 * Product cart page should load this file
 */

$(function(){
	
	$("a[data-product-large-image]").click(function(event){
		event.preventDefault();
		
		// Get link to that larger image
		var src = $(this).data('product-large-image');
		var $cover = $("[data-product-image='cover']");
		
		// Ensure cover element exists
		if ($cover.length == 0) {
			console.log('You need to provide data attribute to the cover image');
		} else {
			
			// Now simply change src attribute value in cover image
			$cover.attr('src', src);
		}
	});
	
});
