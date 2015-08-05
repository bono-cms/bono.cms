
$(function() {
	
	$.setFormGroup('category');
	
	function add(callback){
		$("form").send({
			url : '/admin/module/announcement/category/add.ajax',
			success : callback
		});
	}
	
	function update(callback){
		$("form").send({
			url : '/admin/module/announcement/category/edit.ajax',
			success : callback
		});
	}
	
	
	$("[data-button='save']").click(function(){
		update(function(response){
			if (response == "1") {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='add']").click(function(){
		add(function(response){
			if ($.isNumeric(response)) {
				window.location = '/admin/module/announcement/category/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/announcement';
	});
	
});
