
$(function(){
	
	$.delete({
		categories : {
			language : {
				url : "/admin/languages/delete.ajax"
			}
		}
	});
	
	
	$("[data-button='save']").click(function(event){
		event.preventDefault();
		var data = $("form").serialize();
		
		$.ajax({
			url : "/admin/languages/save.ajax",
			data : data,
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
