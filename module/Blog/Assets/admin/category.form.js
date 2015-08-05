
$(function() {
	
	$.wysiwyg.init(['category[description]']);
	$.setFormGroup('category');
	
	
	function update(callback){
		$("form").send({
			url : "/admin/module/blog/category/edit.ajax",
			success : callback,
			before : function(){
				$.wysiwyg.update();
			}
		});
	}
	
	function add(callback){
		$("form").send({
			url : "/admin/module/blog/category/add.ajax",
			success : callback,
			before : function(){
				$.wysiwyg.update();
			}
		});
	}
	
	
	$("[data-button='save']").click(function(event){
		update(function(response){
			if (response == "1") {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='add']").click(function(event){
		add(function(response){
			if ($.isNumeric(response)) {
				window.location = '/admin/module/blog/category/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/blog';
	});
	
});

