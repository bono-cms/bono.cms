
$(function() {
	
	
	function update(success){
		$("form").send({
			url : "/admin/module/slider/image/edit.ajax",
			success : success
		});
	}
	
	
	$("[data-button='save']").click(function(){
		update(function(response) {
			if (response == "1") {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/slider';
	});
	
});

