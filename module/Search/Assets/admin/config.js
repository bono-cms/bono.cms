
$(function(){
	
	$("form").submit(function(event) {
		event.preventDefault();
		var data = $(this).serialize();
		
		$.ajax({
			url		: "/admin/module/search/save.ajax",
			data	: data,
			success	: function(response){
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