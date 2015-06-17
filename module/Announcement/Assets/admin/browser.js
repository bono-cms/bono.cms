
$(function() {
	
	
	$.delete({
		categories : {
			announce : {
				url : "/admin/module/announcement/announce/delete.ajax"
			},
			category : {
				url : "/admin/module/announcement/category/delete.ajax"
			}
		}
	});
	
	
	$("[data-button='save-changes']").click(function(event) {
		event.preventDefault();
		$.ajax({
			url		: "/admin/module/announcement/announce/save.ajax",
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
	
	
	$("[data-button='remove-selected']").click(function(event) {
		event.preventDefault();
		$.ajax({
			url : "/admin/module/announcement/announce/delete-selected.ajax",
			data : $("form").serialize(),
			success : function(response) {
				if (response == "1") {
					window.location.reload();
				} else {
					$.showErrors(response);
				}
			}
		});
	});
	
	
});
