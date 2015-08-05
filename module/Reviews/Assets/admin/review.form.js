
$(function(){
	
	$.wysiwyg.init(['review[content]']);
	$("[name='review[date]']").datepicker();
	$.setFormGroup('review');
	
	
	function update(callback) {
		$("form").send({
			url : "/admin/module/reviews/edit.ajax",
			success : callback,
			before : function(){
				$.wysiwyg.update();
			}
		});
	}
	
	
	function add(callback) {
		$("form").send({
			url : "/admin/module/reviews/add.ajax",
			success : callback,
			before : function(){
				$.wysiwyg.update();
			}
		});
	}
	
	
	$("[data-button='save']").click(function() {
		update(function(response) {
			if (response == "1") {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save-create']").click(function() {
		update(function(response) {
			if (response == "1") {
				window.location = '/admin/module/reviews/add';
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='add']").click(function() {
		add(function(response) {
			if ($.isNumeric(response)) {
				window.location = '/admin/module/reviews/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
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
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/reviews';
	});
	
});
