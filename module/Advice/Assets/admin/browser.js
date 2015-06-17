
$(function(){
	
	$.delete({
		categories : {
			advice : {
				url : "/admin/module/advice/delete.ajax"
			}
		}
	});
	
	
	$("[data-button='save-changes']").click(function(event){
		event.preventDefault();
		
		$.ajax({
			url : "/admin/module/advice/save.ajax",
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
	
	
	$("[data-button='remove-selected']").click(function(event){
		event.preventDefault();
		
		$.ajax({
			url : "/admin/module/advice/delete-selected.ajax",
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
