$(function(){
	
	$("td img").elevateZoom({
		
		zoomWindowFadeIn: 500, 
		zoomWindowFadeOut: 500, 
		lensFadeIn: 500, 
		lensFadeOut: 500,
		easing : true
		
	});
	
	
	// Handle delete buttons
	$.delete({
		categories : {
			photo : {
				url : "/admin/module/photogallery/photo/delete.ajax"
			},
			category : {
				url : "/admin/module/photogallery/album/delete.ajax"
			}
		}
	});
	
	
	$("[data-button='save-changes']").click(function(event) {
		event.preventDefault();
		$.ajax({
			url		: "/admin/module/photogallery/save.ajax",
			data	: $("form").serialize(),
			success : function(response) {
				if (response == "1") {
					window.location.reload();
				} else {
					$.showErrors(response);
				}
			}
		});
	});
	
	
	$("[data-button='remove-selected']").click(function(event){
		event.preventDefault();
		
		$.ajax({
			url : "/admin/module/photogallery/delete-selected.ajax",
			data : $("form").serialize(),
			success : function(response) {
				if (response == "1") {
					window.location.reload();
				} else {
					$.showErrors(response);
				}
			}
		});
	});
	
});
