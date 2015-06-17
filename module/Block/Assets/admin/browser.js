
$(function(){
	
	$("[data-button='remove-selected']").click(function(){
		$.ajax({
			url : "/admin/module/block/delete-selected.ajax",
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
	
	
	$.delete({
		categories : {
			main : { url : "/admin/module/block/delete.ajax" }
		}
	});
	
});

