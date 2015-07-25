
$(function(){

	// Highlight a row on selecting
	$("table > tbody > tr > td > input[type='checkbox']").change(function(){
		// Bootstap class
		var hg = 'warning';
		var $row = $(this).parent().parent();

		if ($(this).prop('checked') == true) {
			$row.addClass(hg);
		} else {
			$row.removeClass(hg);
		}
	});


	$("table > thead > tr > th > input[type='checkbox']").change(function(){
		var $self = $(this);
		var $children = $(this).parent().parent().parent().parent().find("tbody > tr > td:first-child > input[type='checkbox']");
		var state = $self.prop('checked');

		$children.prop('checked', state);
		$self.prop('checked', state);
	});
	
	
	$("td > a.view").click(function(event) {
		event.preventDefault();
	});
	
});
