
$(function() {
	
	function update(callback) {
		$("form").send({
			url : "/admin/module/shop/category/edit.ajax",
			success : callback
		});
	}
	
	function add(callback) {
		$("form").send({
			url : "/admin/module/shop/category/add.ajax",
			success : callback
		});
	}
	
	
	$.wysiwyg.init(['category[description]']);
	
	
	if (jQuery().preview) {
		$("[name='file']").preview(function(data) {
			$("[data-image='preview']").fadeIn(1000).attr('src', data);
		});
	}	
	
	$("[data-button='slug']").off('click').click(function(event){
		event.preventDefault();
		$.refreshSlug($("[name='category[title]']"), $("[name='category[slug]']"));
	});
	
	
	$("[data-button='add']").click(function(){
		add(function(response){
			if ($.isNumeric(response)) {
				window.location = '/admin/module/shop/category/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save']").click(function(){
		update(function(response){
			if (response == "1") {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/shop';
	});
	
});
