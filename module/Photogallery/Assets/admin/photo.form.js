
$(function() {
	
	$.setFormGroup('photo');
	
	function update(success) {
		$("form").send({
			url : "/admin/module/photogallery/photo/edit.ajax",
			success : success
		});
	}
	
	
	function add(success) {
		$("form").send({
			url	   : "/admin/module/photogallery/photo/add.ajax",
			success : success
		});
	}
	
	
	// Make sure that plugin is loaded before applying it
	if (jQuery().elevateZoom) {
		$("div.col-lg-10 img").elevateZoom({
			zoomWindowFadeIn : 500, 
			zoomWindowFadeOut : 500, 
			lensFadeIn : 500, 
			lensFadeOut : 500,
			easing : true
		});
	}
	
	
	if (jQuery().preview) {
		$("[name='file']").preview(function(data) {
			$("[data-image='preview']").fadeIn(1000).attr('src', data);
		});
	}
	
	
	$("[data-button='add']").click(function() {
		add(function(response) {
			if ($.isNumeric(response)) {
				window.location = '/admin/module/photogallery/photo/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='add-upload']").click(function() {
		add(function(response) {
			if ($.isNumeric(response)) {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save-upload']").click(function() {
		update(function(response) {
			if (response == "1") {
				window.location = '/admin/module/photogallery/photo/add';
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save']").click(function() {
		update(function(response) {
			if (response == "1") {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/photogallery';
	});
	
	
});
