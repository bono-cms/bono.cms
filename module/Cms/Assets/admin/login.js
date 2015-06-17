
$(function() {
	
	$("form").submit(function(event) {
		event.preventDefault();
		var data = $(this).serialize();
		
		$.ajax({
			url : "/admin/login.ajax",
			data : data,
			success : function(response) {
				if (response == "1") {
					window.location = '/admin';
				} else {
					$.showErrors(response);
				}
			}
		});
	});
	
});
