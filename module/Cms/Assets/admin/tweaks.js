
$(function() {
	
	$.wysiwyg.init(['site_down_reason']);
	
	
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
		$("[name='smtp_secure_layer'], [name='smtp_host'], [name='smtp_username'], [name='smtp_password'], [name='smtp_port']").prop('disabled', state);
	}
	
	alterSmptElements($("#smtpDriver").prop('checked'));
	
	$("#smtpDriver").click(function(){
		alterSmptElements($(this).prop('checked'));
	});
	
});
