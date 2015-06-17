
$(function() {
	
	$.wysiwyg.init(['full', 'intro']);
	
	
	function add(callback) {
		$("form").send({
			url : '/admin/module/announcement/announce/add.ajax',
			success : callback
		});
	}
	
	function edit(callback) {
		$("form").send({
			url : "/admin/module/announcement/announce/edit.ajax",
			success: callback
		});
	}
	
	
	$("[data-button='add']").click(function(event) {
		add(function(response) {
			if ($.isNumeric(response)) {
				window.location = '/admin/module/announcement/announce/edit/' + response;
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
	
	
	$("[data-button='save']").click(function(event) {
		edit(function(response) {
			if (response == "1") {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save-create']").click(function(event) {
		edit(function(response) {
			if (response == "1") {
				window.location = '/admin/module/announcement/announce/add';
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

