
$(function() {

	$("[name='filter[date]']").datepicker({
		format : 'yyyy-mm-dd'
	});
	
	
	$.delete({
		categories : {
			category : {
				url : "/admin/module/shop/category/do/delete.ajax"
			},
			product : {
				url : "/admin/module/shop/product/delete.ajax"
			}
		}
	});
	
	
	$("[data-button='save-changes']").click(function(event) {
		event.preventDefault();
		
		$.ajax({
			url : "/admin/module/shop/save.ajax",
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
			url : "/admin/module/shop/delete-selected.ajax",
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
