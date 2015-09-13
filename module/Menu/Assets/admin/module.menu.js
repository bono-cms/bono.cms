
$(function(){
	
	$('.chosen-select').chosen();
	
	/**
	 * Redirects to current menu category
	 * 
	 * @return void
	 */
	function toCurrentCategory(){
		// We always have a category id
		var categoryId = $("[name='category_id']").val();
		// We must stay on the same category id
		window.location = '/admin/module/menu/browse/category/' + categoryId;
	}

	/**
	 * Redirect to item by its id
	 * 
	 * @param string id Item id
	 * @return void
	 */
	function toItem(id){
		window.location = '/admin/module/menu/item/view/' + id;
	}

	/**
	 * Logs out and redirects back to login
	 * 
	 * @return void
	 */
	function toExit(){
		window.location = '/admin/logout';
	}
	
	/**
	 * Redirect to menu module home page
	 * 
	 * @return void
	 */
	function toHome(){
		window.location = '/admin/module/menu';
	}
	
	
	function toAddingChild(categoryId, parentId){
		window.location = '/admin/module/menu/item/add/category/' + categoryId + '/parent/' + parentId;
	}
	
	/**
	 * Returns maximal allowed nested level depth
	 * 
	 * @return integer
	 */
	function getDepthLevel(){
		var $target = $("[name='max_depth']");
		
		if ($target.length > 0) {
			return parseInt($target.val());
		} else {
			// By default
			return 5;
		}
	}
	
	
	// Handle delete buttons
	$.delete({
		categories : {
			item : {
				url : "/admin/module/menu/item/delete.ajax",
				success : function(response){
					if (response == "1"){
						toCurrentCategory();
					} else {
						$.showErrors(response);
					}
				}
			},
			
			category : {
				url : "/admin/module/menu/category/delete.ajax",
				success : function(response){
					if (response == "1") {
						toHome();
					} else {
						$.showErrors(response);
					}
				}
			}
		}
	});
	
	
	var $contextMenu = $("#contextMenu");
	
	$("body").on("contextmenu", "li.dd-item", function(e){
		e.preventDefault();

		$contextMenu.css({
			display: "block",
			left: e.pageX,
			top: e.pageY
		});

		// Each li element has an id
		var id = $(this).data('id');

		$("[data-button='item-edit']").click(function(event){
			event.preventDefault();
			toItem(id);
		});

		// This event is attached only once, so there's no need to cancel it
		$("[data-button='add-child']").click(function(event){
			event.preventDefault();
			// Grab a category id
			var categoryId = $("[name='category_id']").val();
			
			toAddingChild(categoryId, id);
		});
		
		// Update an id to make it aware of last called item
		$("[data-button='remove']").data('id', id);
		
		return false;
	});
	
	
	
	$("body").click(function(){
		$contextMenu.hide();
	});
	
	
	$("[data-button='expand-all']").click(function(event){
		event.preventDefault();
		$('.dd').nestable('expandAll');
	});
	
	
	$("[data-button='collapse-all']").click(function(event){
		event.preventDefault();
		$('.dd').nestable('collapseAll');
	});
	
	
	// Add & Create new event listener
	$("[data-button='create-new']").click(function(event){
		event.preventDefault();
		$.ajax({
			url : "/admin/module/menu/item/add.ajax",
			data : $("form").serialize(),
			success : function(response) {
				if ($.isNumeric(response)) {
					toCurrentCategory();
				} else {
					$.showErrors(response);
				}
			}
		});
	});
	
	
	// Add event listener
	$("[data-button='add']").click(function(event){
		event.preventDefault();
		$.ajax({
			url : "/admin/module/menu/item/add.ajax",
			data : $("form").serialize(),
			success : function(response) {
				if ($.isNumeric(response)) {
					// On success response must represent a last id
					toItem(response);
				} else {
					$.showErrors(response);
				}
			}
		});
	});
	
	
	
	// Add & Exit event listener
	$("[data-button='create-exit']").click(function(event){
		event.preventDefault();
		$.ajax({
			url : "/admin/module/menu/item/add.ajax",
			data : $("form").serialize(),
			success : function(response) {
				if ($.isNumeric(response)) {
					toExit();
				} else {
					$.showErrors(response);
				}
			}
		});
	});
	
	
	
	// Cancel event listener. That must always redirect to current category view
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		toCurrentCategory();
	});
	
	
	// Save event listener
	$("[data-button='save']").click(function(event) {
		event.preventDefault();
		$.ajax({
			url : "/admin/module/menu/item/edit.ajax",
			data : $("form").serialize(),
			success : function(response) {
				if ($.isNumeric(response)) {
					window.location.reload();
				} else {
					$.showErrors(response);
				}
			}
		});
	});
	
	
	// Save and Create event listener
	$("[data-button='save-create']").click(function(event){
		event.preventDefault();
		$.ajax({
			url : "/admin/module/menu/item/edit.ajax",
			data : $("form").serialize(),
			success : function(response) {
				if (response == "1") {
					toCurrentCategory();
				} else {
					$.showErrors(response);
				}
			}
		});
	});
	
	
	// Save and exit event listener
	$("[data-button='save-exit']").click(function(event){
		event.preventDefault();
		$.ajax({
			url : "/admin/module/menu/item/edit.ajax",
			data : $("form").serialize(),
			success : function(response) {
				if (response == "1") {
					toExit();
				} else {
					$.showErrors(response);
				}
			}
		});
	});

	
	if ($("[name='has_link']").val() == ""){
		$("#custom-link-row").addClass('hidden');
	} else {
		$("#custom-link-row").removeClass('hidden');
	}
	
	
	$("[name='web_page_id']").change(function(event) {
		var val = $(this).val();
		var $customLink = $("#custom-link-row");
		var $hasLink = $("[name='has_link']");

		if (val == "0") {
			$customLink.removeClass('hidden');
			$hasLink.val("1");

		} else {
			$customLink.addClass('hidden');
			$hasLink.val("0");
		}

		var $name = $("[name='name']");

		if (val != "0" && val != "#") {
			var text = $(this).find("option:selected").text();
			$name.val(text);
		}
		
		return false;
	});
	
	
	// activate Nestable for list 2
	$('#nestable2').nestable({
		group: 1,
		maxDepth: getDepthLevel()
	}).on('change', function(e){
		
		var list = e.length ? e : $(e.target);
		
		if (window.JSON) {
			var value = window.JSON.stringify(list.nestable('serialize'));
			
			$.ajax({
				url : "/admin/module/menu/save.ajax",
				data : {
					"json_data" : value,
				},
				// Discard all previous AJAX listeners
				beforeSend : function(){},
				complete : function(){},
				success : function(response){
					// Don't do anything here for now, but log the response
					console.log(response);
				}
			});
		}
	});
	
});

