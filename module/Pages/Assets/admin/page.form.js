
$(function(){
	
	$.wysiwyg.init(['page[content]']);
	$.setFormGroup('page');
	
	
	function add(callback) {
		$("form").send({
			url : "/admin/module/pages/add.ajax",
			success : callback,
			before : function(){
				$.wysiwyg.update();
			}
		});
	}
	
	
	function update(callback) {
		$("form").send({
			url : "/admin/module/pages/edit.ajax",
			success : callback,
			before : function(){
				$.wysiwyg.update();
			}
		});
	}
	
	
	$("[name='controller']").change(function(){
		$("[name='protected']").prop('checked', true);
	});
	
	
	$("[data-button='add']").click(function(event) {
		add(function(response) {
			if ($.isNumeric(response)) {
				window.location = '/admin/module/pages/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/pages';
	});
	
	
	$("[data-button='add-create']").click(function() {
		add(function(response) {
			if ($.isNumeric(response)) {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save']").click(function(){
		update(function(response) {
			if (response == "1") {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save-create']").click(function(){
		update(function(response) {
			if (response == "1") {
				window.location = '/admin/module/pages/add';
			} else {
				$.showErrors(response);
			}
		});
	});
	
});

