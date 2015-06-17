

$(function(){
	
	//@TODO
	$.bono.module.reviews.prototype = {
		
		send : function(){
			$.ajax({
				url : "/module/reviews/send.ajax",
				data : $("form").serialize(),
				success : function() {
					
				}
			});
		}
	};
	
	
});

