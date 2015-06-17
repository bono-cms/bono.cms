/**
 * Menu widget UI logic
 */

$(function(){
	
	if ($("[data-button='save']").length > 0) {
		var webPageId = $("[name='web_page_id']").val();
		
		//@TODO Hack
		if (!webPageId){
			webPageId = $("[name='webPageId']").val()
		}
		
		$.ajax({
			url : "/admin/module/menu/widget/load/" + webPageId,
			beforeSend : function(){
				// Override with empty
			},
			success : function(response){
				// response is a whole block
				$("[data-container='menu-widget']").append(response);
			}
		});
	}
	
	
	$("[data-button='add-item']").click(function(event){
		event.preventDefault();
		$.ajax({
			url : "/admin/module/menu/widget/load-empty.ajax",
			beforeSend : function(){
				// Override with empty
			},
			success : function(response){
				// response is a whole block
				$("[data-container='menu-widget']").append(response);
			}
		});
	});
	
	
	// On changing category
	$(document).on('change', "[data-widget='menu-category']", function(){
		var categoryId = $(this).val();

		// Find corresponding container element
		$container = $(this).parent()
							.parent()
							.parent()
							.find("[data-widget='menu-parent-container']")
							.find("[data-widget='menu-parent-item']");
		
		$.ajax({
			url : "/admin/module/menu/category/load-items.ajax",
			data : {
				category_id : categoryId
			},
			success : function(response){
				try {
					var items = $.parseJSON(response);
					
					// Remove all previous nested elements
					$container.empty();
					
					for (var key in items) {
						var value = items[key];
						// Create and tweak option DOM element, so that we can easily append it
						var option = document.createElement('option');
						$(option).attr('value', key).text(value);
						
						$container.append(option);
					}
					
				} catch(e) {
					// Should never happen, but anyway
					console.log(e.message);
				}
			}
		});
	});
	
	
	
	// On clicking remove block
	$(document).on('click', "[data-widget-button='menu-remove']", function(event){
		event.preventDefault();
		
		var $target = $(this).parent()
							.parent()
							.parent()
							.parent()
							.parent();
		
		// Remove everything inside container
		$target.hide(500, function(){
			$(this).empty();
		});
	});
	
	
});
