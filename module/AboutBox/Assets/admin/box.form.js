
$(function() {
	
	$.wysiwyg.init(['content']);
	
	$("[name='content']").css({
		width	 : 1283,
		height	 : 500,
	});
	
	
	$("form").submit(function(event) {
		event.preventDefault();
		$.wysiwyg.update();
		
		var data = $(this).serialize();
		
		$.ajax({
			url		: "/admin/module/about-box/save.ajax",
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
	
});
