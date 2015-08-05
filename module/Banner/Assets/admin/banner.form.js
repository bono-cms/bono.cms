
$(function() {
	
	$.setFormGroup('banner');
	
	function update(callback){
		$("form").send({
			url : "/admin/module/banner/edit.ajax",
			success : callback
		});
	}
	
	
	function add(callback){
		$("form").send({
			url : "/admin/module/banner/add.ajax",
			success : callback
		});
	}
	
	
	$("[data-button='add']").click(function(){
		add(function(response){
			if ($.isNumeric(response)) {
				window.location = '/admin/module/banner/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save']").click(function(){
		update(function(response){
			if ($.isNumeric(response)) {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/banner';
	});
	
});

