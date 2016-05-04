$(function(){
	
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
});