
$(function() {
	
	// Handle delete button
	$.delete({
		categories : {
			main : {
				url : "/admin/module/reviews/delete.ajax"
			}
		}
	});
	
	
	$("[data-button='remove-selected']").click(function(event) {
		event.preventDefault();
		$.ajax({
			url		: "/admin/module/reviews/delete-selected.ajax",
			data	: $("form").serialize(),
			success : function(response) {
				if (response == "1") {
					window.location.reload();
				} else {
					$.showErrors(response);
				}
			}
		});
	});
	
	$("[data-button='save-changes']").click(function(event) {
		event.preventDefault();
		$.ajax({
			url		: "/admin/module/reviews/save.ajax",
			data	: $("form").serialize(),
			success : function(response) {
				if (response != "1") {
					console.log(response);
				} else {
					$.showErrors(response);
				}
			}
		});
	});
	
	
});
