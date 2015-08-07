
/**
 * Jquery plugin for bootstrap
 */

$(function(){
	
	$("form").submit(function(event){
		event.preventDefault();
		
		var data = $(this).serialize();
		
		$.ajax({
			type : "POST",
			data : data,
			success : function(response){
				$.validator.handleAll(response);
			}
		});
	});
	
});
