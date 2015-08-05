
$(function() {
	
	$.setFormGroup('category');
	
	function add(success){
		$("form").send({
			url : '/admin/module/slider/category/add.ajax',
			success : success
		});
	}
	
	
	function update(success){
		$("form").send({
			url : "/admin/module/slider/category/edit.ajax",
			success : success
		});
	}
	
	
	$("[data-button='add']").click(function(){
		add(function(response){
			if ($.isNumeric(response)) {
				window.location = '/admin/module/slider/category/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save']").click(function(){
		update(function(response){
			if (response == "1"){
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/slider';
	});
	
});
