
$(function(){
	
	$.ajaxSetup({
		beforeSend : function(){
			$("#ajax-modal").modal('show');
		},
		complete : function(){
			$("#ajax-modal").modal('hide');
		}
	});

	$("[data-captcha='button-refresh']").click(function(event){
		event.preventDefault();

		// Grab image's element
		var $image = $("[data-captcha='image']");
		var link = $image.attr('src');

		$image.attr('src', link + Math.random());
	});
	
});
