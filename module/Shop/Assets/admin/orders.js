
$(function(){

	$.delete({
		categories : {
			order : {
				url : "/module/shop/basket/order/delete.ajax"
			}
		}
	});
	
	$("[data-button='approve']").click(function(event){
		event.preventDefault();
		
		var id = $(this).data('id');
		
		$.ajax({
			url : "/module/shop/basket/order/approve.ajax",
			data : {
				id : id
			},
			success : function(response){
				if (response == "1"){
					window.location.reload();
				} else {
					$.showErrors(response);
				}
			}
		});
	});
	
});