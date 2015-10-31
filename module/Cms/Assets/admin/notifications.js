$(function(){
	$.delete({
		categories : {
			main : {
				url : "/admin/notifications/delete.ajax"
			},
			all : {
				url : "/admin/notifications/clear.ajax"
			}
		}
	});
});