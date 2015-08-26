$(function(){
	$.setFormGroup('config');
	
	$("form").submit(function(event) {
		event.preventDefault();
		var data = $(this).serialize();
		
		$.ajax({
			url	: "/admin/module/team/config.ajax",
			data : data,
			success : function(response) {
				if (response == "1"){
					window.location.reload();
				} else {
					$.showErrors(response);
				}
			}
		});
	});	
});