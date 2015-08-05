
$(function() {
	
	$.wysiwyg.init(['config[site_down_reason]']);
	$.setFormGroup('config');
	
	
	$("[data-button='save']").click(function(){
		$("form").send({
			url : "/admin/tweaks.ajax",
			success : function(response){
				if (response == "1"){
					window.location.reload();
				} else {
					$.showErrors(response);
				}
			},
			before : function(){
				$.wysiwyg.update();
			}
		});
	});
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin';
	});
	
	
	function alterSmptElements(state){
		// Do the batch for required elements
		$("[name='config[smtp_secure_layer]'], [name='config[smtp_host]'], [name='config[smtp_username]'], [name='config[smtp_password]'], [name='config[smtp_port]']")
		.prop('disabled', state);
	}
	
	alterSmptElements($("[name='config[use_smtp_driver]']").prop('checked'));
	
	$("[name='config[use_smtp_driver]']").click(function(){
		alterSmptElements($(this).prop('checked'));
	});
	
});
