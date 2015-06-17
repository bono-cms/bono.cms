
$(function(){
	
	$.delete({
		categories : {
			banner : {
				url : "/admin/module/banner/delete.ajax"
			}
		}
	});
	
	
	$("[data-button='remove-selected']").click(function() {
		$.ajax({
			url : "/admin/module/banner/delete-selected.ajax",
			data : $("form").serialize(),
			success : function(response){
				if (response == "1") {
					window.location.reload();
				} else {
					console.log(response);
				}
			}
		});
	});
	
	
});
