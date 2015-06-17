
$(function(){
	
	// Class with common utilities
	var category = {
		/**
		 * Updates global per page count for all categories
		 * 
		 * @param integer count New per page count
		 * @param function done Callback function to be invoked when it's done
		 * @return void
		 */
		updatePerPageCount : function(count, done){
			$.ajax({
				type : "POST",
				url : "/module/shop/category/change-per-page-count.ajax",
				data : {
					count : count
				},
				beforeSend : function(){
					// Override with empty function
				},
				complete : function(){
					// Override with empty function
				},
				success : function(response){
					if (response == "1"){
						done();
					} else {
						// Considered as error if response isn't 1
						console.log(response);
					}
				}
			});
		},
		
		/**
		 * @param string sort Sort constant (represented via numeric value)
		 * @param function done Callback on success to be invoked
		 * @return void
		 */
		updateSort : function(sort, done){
			$.ajax({
				type : "POST",
				url : "/module/shop/category/change-sort-action.ajax",
				data : {
					sort : sort
				},
				beforeSend : function(){
					// Override with empty function
				},
				complete : function(){
					// Override with empty function
				},
				success : function(response){
					if (response == "1"){
						done();
					} else {
						// Considered as error if response isn't 1
						console.log(response);
					}
				}
			});
		}
	};
	
	
	$("[data-category-option='per-page-count']").change(function(event){
		// New count
		var count = $(this).val();
		
		category.updatePerPageCount(count, function(){
			window.location.reload();
		});
	});
	
	
	$("[data-category-option='sort']").change(function(event){
		var sort = $(this).val();
		category.updateSort(sort, function(){
			window.location.reload();
		});
	});
});
