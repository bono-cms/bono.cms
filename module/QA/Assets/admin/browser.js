
$(function() {
	
	$.delete({
		categories : {
			main : {
				url : "/admin/module/qa/delete.ajax"
			}
		}
	});
	
	
	$("[data-button='remove-selected']").click(function(event) {
		event.preventDefault();
		$.ajax({
			url		: "/admin/module/qa/delete-selected.ajax",
			data	: $("form").serialize(),
			success : function(response) {
				if (response == "1") {
					window.location.reload();
				} else {
					console.log(response);
				}
			}
		});
	});
	
	
	$("[data-button='save-changes']").click(function(event) {
		event.preventDefault();
		
		$.ajax({
			url		: "/admin/module/qa/save.ajax",
			data	: $("form").serialize(),
			success : function(response) {
				if (response != "1") {
					console.log(response);
				} else {
					window.location.reload();
				}
			}
		});
	});
	
	
});
