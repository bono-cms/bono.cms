$(function(){

	$.wysiwyg.init(['form[description]']);
	$.setFormGroup('form');

	/**
	 * Adds a form
	 */
	function add(callback){
		$("form").send({
			url : "/admin/module/mail-form/add.ajax",
			success : callback,
			before : function(){
				$.wysiwyg.update();
			}
		});
	}

	/**
	 * Updates a form
	 */
	function update(callback){
		$("form").send({
			url : "/admin/module/mail-form/edit.ajax",
			success : callback,
			before : function(){
				$.wysiwyg.update();
			}
		});
	}
	
	
	$("[data-button='add']").click(function(event){
		add(function(response){
			// Numeric response means last id
			if ($.isNumeric(response)) {
				window.location = '/admin/module/mail-form/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save']").click(function(){
		update(function(response){
			if (response == "1"){
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/mail-form';
	});
	
	
});

