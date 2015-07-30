
$(function() {
	
	function add(callback){
		$("form").send({
			url : "/admin/languages/add.ajax",
			success : callback
		});
	}
	
	
	function update(callback){
		$("form").send({
			url : "/admin/languages/edit.ajax",
			success : callback
		});
	}
	
	
	function flag($element, useVal){
		useVal = useVal || false;
		
		var $current = $element.find('option:selected');
		var flag = $current.data('flag');
		
		$("[data-container='flag']").attr('class', flag);
		
		if (useVal){
			$("[name='language[code]']").val($current.val());
		}
	}
	
	
	$("[name='language[flag]']").ready(function(){
		flag($(this), false);
	}).change(function(){
		flag($(this), true);
	});
	
	
	
	$("[data-button='add']").click(function(){
		add(function(response){
			if ($.isNumeric(response)) {
				window.location = '/admin/languages/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save']").click(function(){
		update(function(response){
			if (response == "1") {
				window.location.reload();
				
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/languages';
	});
	
	
});
