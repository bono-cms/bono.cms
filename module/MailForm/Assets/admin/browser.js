

$(function(){
	
	$.delete({
		categories : {
			main : {
				url : "/admin/module/mail-form/delete.ajax"
			}
		}
	});
	
	
	$("[data-button='remove-selected']").click(function(event){
		event.preventDefault();
		$.ajax({
			url : "/admin/module/mail-form/delete-selected.ajax",
			data : $("form").serialize(),
			success : function(response){
				// 1 means success
				if (response == "1") {
					window.location.reload();
				} else {
					$.showErrors(response);
				}
			}
		});
	});
	
	
	$("[data-button='save-changes']").click(function(event){
		event.preventDefault();
		
		$.ajax({
			
			url : "/admin/module/mail-form/save-changes.ajax",
			data : $("form").serialize(),
			success : function(response){
				// 1 means success
				if (response == "1") {
					window.location.reload();
				} else {
					$.showErrors(response);
				}
			}
		});
	});
	
});

