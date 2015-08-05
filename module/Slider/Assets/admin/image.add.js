
$(function() {
	
	$.setFormGroup('image');
	
	function add(callback){
		$("form").send({
			url	: "/admin/module/slider/image/add.ajax",
			success : callback
		});
	}
	
	
	$("[name='file']").preview(function(data){
		$("[data-image='preview']").fadeIn(1000).attr('src', data);
	});
	
	
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

});
