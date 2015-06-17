
$(function() {
	
	$.delete({
		categories : {
			category : {
				url : "/admin/module/slider/category/delete.ajax"
			},
			image : {
				url : "/admin/module/slider/image/delete.ajax"
			}
		}
	});
	
	
	$("[data-button='save-changes']").click(function(event) {
		event.preventDefault();
		$.ajax({
			url		: "/admin/module/slider/save.ajax",
			data	: $("form").serialize(),
			success	: function(response) {
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
			url		: "/admin/module/slider/image/delete-selected.ajax",
			data	: $("form").serialize(),
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
