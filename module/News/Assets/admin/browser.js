
$(function(){
	
	$.delete({
		categories : {
			category : {
				url : "/admin/module/news/category/delete.ajax"
			},
			post : {
				url : "/admin/module/news/post/delete.ajax"
			}
		}
	});
	
	
	$("[data-button='remove-selected']").click(function(event) {
		event.preventDefault();
		$.ajax({
			url : "/admin/module/news/delete-selected.ajax",
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
	
	
	$("[data-button='save-changes']").click(function(event) {
		event.preventDefault();
		$.ajax({
			url : "/admin/module/news/save.ajax",
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
	
});
