
$(function(){
	
	$('ul.sf-menu').superfish();
	
	$.ajaxSetup({
		beforeSend : function(){
			$("#ajax-modal").modal('show');
		},
		complete : function(){
			$("#ajax-modal").modal('hide');
		}
	});
	
	
	// If zoom plugin is loaded, then init it
	if ($.fn.elevateZoom) {
		$("[data-product-image='cover']").elevateZoom({
			gallery :'gallery_01', 
			cursor : 'pointer', 
			galleryActiveClass : 'active', 
			imageCrossfade : true, 
			easing : true,
			tint : true,
			tintColour : 'black',
			tintOpacity : 0.5,
			scrollZoom : true,
			loadingIcon : '/module/Site/View/Template/bono-shop/img/ajax.gif'
		});
	}
});
