
$(function() {
	
	$.delete({
		categories : {
			main : {
				url : "/admin/module/pages/delete.ajax"
			}
		}
	});
	
	
	$("[data-button='remove-selected']").click(function(event) {
		event.preventDefault();
		
		var data = $("form").serialize();
		
		$.ajax({
			url		: "/admin/module/pages/delete-selected.ajax",
			data	: data,
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
			url		: "/admin/module/pages/save.ajax",
			data	: $("form").serialize(),
			success : function(response) {
				if (response != "1") {
					$.showErrors(response);
				} else {
					window.location.reload();
				}
			}
		});
	});
	
});
