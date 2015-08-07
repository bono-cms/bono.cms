/**
 * Jquery plugin for bootstrap
 */
$(function(){

	if ($("form").length > 0 && $("[data-button='submit']") == 0){
		console.log('Warning: A submit button must contain an attribute with submit property like this: data-button="submit"');
	}

	$("[data-button='submit']").click(function(){
		// Find its parent form
		var $form = $(this).closest('form');

		$form.submit(function(){
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
});
