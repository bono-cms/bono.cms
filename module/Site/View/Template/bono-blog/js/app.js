
$(function(){
	
	$.ajaxSetup({
		beforeSend : function(){
			$("#ajax-modal").modal('show');
		},
		complete : function(){
			$("#ajax-modal").modal('hide');
		}
	});

});
  