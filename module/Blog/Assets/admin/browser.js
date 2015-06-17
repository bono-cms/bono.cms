
$(function() {
	
	
	$.delete({
		categories : {
			category : {
				url : "/admin/module/blog/category/delete.ajax"
			},
			post : {
				url : "/admin/module/blog/post/delete.ajax"
			}
		}
	});
	
	
	$("[data-button='save-changes']").click(function(event) {
		event.preventDefault();
		$.ajax({
			url : "/admin/module/blog/save.ajax",
			data : $("form").serialize(),
			success : function(response){
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
			url : "/admin/module/blog/post/delete-selected.ajax",
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
