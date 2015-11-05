$(function(){
	$.setFormGroup('db');

	$("form").submit(function(event){
		event.preventDefault();
		var data = $(this).serialize();

		$.ajax({
			url : '/install.ajax',
			data : data,
			type : 'POST',
			success : function(response){
				if (response == "1") {
                    window.location = '/install/ready';
				} else {
					$.showErrors(response);
				}
			}
		});
	});
});
