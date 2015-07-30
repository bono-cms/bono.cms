
$(function() {
	
	$.wysiwyg.init(['category[description]']);
	
	
	function update(callback){
		$("form").send({
			url : "/admin/module/news/category/edit.ajax",
			success : callback
		});
	}
	
	
	function add(callback){
		$("form").send({
			url : "/admin/module/news/category/add.ajax",
			success : callback
		});
	}
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/news';
	});
	
	
	$("[data-button='add']").click(function(event){
		add(function(response){
			if ($.isNumeric(response)) {
				window.location = '/admin/module/news/category/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save']").click(function(event){
		update(function(response){
			if (response == "1") {
				window.location.reload();
			} else {
				$.showErrors(response)
			}
		});
	});
	
	
});
