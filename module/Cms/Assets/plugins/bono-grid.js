
/**!
 * Bono Grid Jquery Plugin
 * 
 * Comes in handy when browsing tables
 * Bootstrap must be loaded
 */

$(function(){

	/**
	 * Applies sortable
	 * 
	 * @return void
	 */
	function applySortable(){

		// Default options
		var options = {
			sortableActivationData : 'sortable',
			sortableColumnData : 'column',
			upClass : 'glyphicon-arrow-up',
			downClass : 'glyphicon-arrow-down',
			indention : ' ',
			start : 'up',
			selector : 'thead > tr:first-child > th'
		};

		jQuery.fn.outerHTML = function(){
			return this.get().map(function(v){
				return v.outerHTML;
			})
		};

		// Grab a referrer
		var $row = $(options.selector);

		$row.each(function(){
			// Apply only to sortable elements
			if ($(this).data(options.sortableActivationData) == true) {

				// Determine the default class on show
				var defaultClass = options.start == 'up' ? options.upClass : options.downClass;

				var i = document.createElement('i');
				$(i).attr('class', 'glyphicon ' + defaultClass);

				var val = $(this).html() + options.indention + $(i).outerHTML();
				$(this).html(val);
			}
		});

		$row.click(function(event){
			//event.preventDefault();
			
			var $i = $(this).find('i');
			var column = $(this).data(options.sortableColumnData);
			var direction = null;

			// Down is clicked
			if ($i.hasClass(options.upClass)) {

				$i.removeClass(options.upClass).addClass(options.downClass);
				direction = 'down';

			} else if ($i.hasClass(options.downClass)) {

				// Up is clicked
				$i.removeClass(options.downClass).addClass(options.upClass);
				direction = 'up';

			} else {
				// Fail
				console.log('Fail');
				return false;
			}
			
			
			$tbl = $row.parent().parent().parent();
			$tbl.find('tbody').hide(300, function(){
				$("#l").show();
				
			});

			//setTimeout(100, function(){
				$tbl.find('tbody').show(500);
				$("#l").hide();
			//});

			return;
			
			//@TODO
			$.ajax({
				url : url,
				data : {
					column : column,
					direction : direction
				},
				success : function(response){
					
				}
			});
			
		});
	}
	
	applySortable();
	
	
	function applyCheckboxes(){
		// Highlight a row on selecting
		$("table > tbody > tr > td > input[type='checkbox']").change(function(){
			var hg = 'success';
			var $row = $(this).parent().parent();

			if ($(this).prop('checked') == true) {
				$row.addClass(hg);
			} else {
				$row.removeClass(hg);
			}
		});


		$("table > thead > tr > th > input[type='checkbox']").change(function(){
			var state = $(this).prop('checked');
			var $children = $(this).parent().parent().parent().parent().find("tbody > tr > td:first-child > input[type='checkbox']");

			$children.prop('checked', state);
		});
	}
	
	applyCheckboxes();
	
	
	$("td > a.view").click(function(event) {
		event.preventDefault();
	});
	
	
});