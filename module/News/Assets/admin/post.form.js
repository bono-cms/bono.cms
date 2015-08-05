
$(function() {
	
	$("[name='post[date]'").datepicker();
	$.wysiwyg.init(['post[full]', 'post[intro]']);
	$.setFormGroup('post');

	
	function update(callback) {
		$("form").send({
			url : "/admin/module/news/post/edit.ajax",
			success : callback
		});
	}
	
	
	function add(callback) {
		$("form").send({
			url : "/admin/module/news/post/add.ajax",
			success : callback
		});
	}

	if (jQuery().preview) {
		$("[name='file']").preview(function(data) {
			$("[data-image='preview']").fadeIn(1000).attr('src', data);
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
	
	
	$("[data-button='add']").click(function(event) {
		add(function(response) {
			if ($.isNumeric(response)) {
				window.location = '/admin/module/news/post/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='add-create']").click(function(event){
		add(function(response) {
			if ($.isNumeric(response)) {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});	
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/news';
	});
	
});

