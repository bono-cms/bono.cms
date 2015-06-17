
$(function(){
	
	$.wysiwyg.init(['notepad']);
	
	$("form").submit(function(event) {
		event.preventDefault();
		$.wysiwyg.update();
		
		var data = $(this).serialize();
		
		$.ajax({
			url		: "/admin/notepad.ajax",
			data	: data,
			success	: function(response) {
				if (response == "1") {
					window.location.reload();
				} else {
					$.showErrors(response);
				}
			}
		});
	});
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin';
	});
	
});
