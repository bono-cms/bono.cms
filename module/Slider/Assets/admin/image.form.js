
$(function() {
	
	$.setFormGroup('image');
	
	function add(callback){
		$("form").send({
			url	: "/admin/module/slider/image/add.ajax",
			success : callback
		});
	}
	
	function update(success){
		$("form").send({
			url : "/admin/module/slider/image/edit.ajax",
			success : success
		});
	}
	
	
	if (jQuery().preview) {
		$("[name='file']").preview(function(data){
			$("[data-image='preview']").fadeIn(1000).attr('src', data);
		});
	}
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/slider';
	});
	
	
	$("[data-button='add']").click(function(){
		add(function(response){
			if ($.isNumeric(response)) {
				window.location = '/admin/module/slider/image/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	$("[data-button='add-upload']").click(function(){
		add(function(response) {
			if ($.isNumeric(response)) {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	$("[data-button='save']").click(function(){
		update(function(response) {
			if (response == "1") {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});

	$("[data-button='save-upload']").click(function() {
		update(function(response) {
			if (response == "1") {
				window.location = '/admin/module/slider/image/add';
			} else {
				$.showErrors(response);
			}
		});
	});	
});
