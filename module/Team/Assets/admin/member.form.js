
$(function() {
	
	$.wysiwyg.init(['team[description]']);
	$.setFormGroup('team');
	
	
	function add(success) {
		$("form").send({
			url	   : "/admin/module/team/add.ajax",
			before : function(){
				$.wysiwyg.update();
			},
			success : success
		});
	}
	
	
	function update(success) {
		$("form").send({
			url	   : "/admin/module/team/edit.ajax",
			before : function(){
				$.wysiwyg.update();
			},
			success : success
		});
	}
	
	
	$("[name='file']").preview(function(data) {
		$("[data-image='preview']").fadeIn(1000).attr('src', data);
	});
	
	
	$("[data-button='save']").click(function() {
		update(function(response) {
			if (response == "1") {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[update-button='save-create']").click(function() {
		update(function(response) {
			if (response == "1") {
				window.location = '/admin/module/team/add';
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='add']").click(function() {
		add(function(response) {
			if ($.isNumeric(response)) {
				window.location = '/admin/module/team/edit/' + response;
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
		window.location = '/admin/module/team';
	});
	
});
