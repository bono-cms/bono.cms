
$(function() {
	
	$.wysiwyg.init(['qa[answer]']);
	
	
	function update(callback) {
		$("form").send({
			url : "/admin/module/qa/edit.ajax",
			success : callback,
			before : function(){
				$.wysiwyg.update();
			}
		});
	}
	
	
	function add(callback) {
		$("form").send({
			url : "/admin/module/qa/add.ajax",
			success : callback,
			before : function(){
				$.wysiwyg.update();
			}
		});
	}
	
	
	$("[data-button='add']").click(function(event) {
		add(function(response) {
			if ($.isNumeric(response)) {
				window.location = '/admin/module/qa/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='add-create']").click(function(event) {
		add(function(response) {
			if ($.isNumeric(response)) {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save']").click(function(event) {
		update(function(response) {
			if ($.isNumeric(response)) {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save-create']").click(function(event) {
		update(function(response) {
			if (response == "1") {
				window.location = '/admin/module/qa/add';
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/qa';
	});
	
});

