
$(function(){

	// Global settings for the whole panel
	$.ajaxSetup({
		cache : false,
		charset : "UTF-8",
		type : "POST",
		beforeSend	: function() {
			$("#loader").modal();
		},
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
		complete : function() {
			$("#loader").modal("hide");
		},
		error : function(res) {
			console.log(res);
		}
	});

	// Simple WYSIWYG wrapper
	$.wysiwyg = {
		// Indicated whether a WYSIWYG editor is initialized
		started : false,
		isStarted : function(){
			return this.started;
		},
		// This method should be invoked on each AJAX request
		update : function(){
			if (typeof(CKEDITOR) != 'undefined'){
				for (instance in CKEDITOR.instances){
					CKEDITOR.instances[instance].updateElement();
				}
			} else if (typeof(tinyMCE) != 'undefined'){
				tinyMCE.triggerSave();
			} else {
				// add more later
			}
		},
		// The array of elements to be replaced
		init : function(elements, options){
			// Interface language
			var language = $("input[name='language']").val();

			if (typeof(CKEDITOR) != 'undefined'){
				for (key in elements) {
					var value = elements[key];
					CKEDITOR.replace(value, {
						language : language
					});
				}
				
				this.started = true;

			} else if ((typeof tinyMCE != 'undefined')){
				$.tinyMCE({
					elements : elements.join(', ')
				});

				this.started = true;
			}
		}
	};

	// Error handler class written for Bootstrap 3.x
	var errorHandler = {
		/**
		 * Builds element name
		 * 
		 * @param string Field name
		 * @param string
		 */
		buildName : function(name){
			// If group is specified, then gotta build a selector for that
			if ($.group) {
				return $.group + "[" + name + "]";
			} else {
				return name;
			}
		},

		/**
		 * Builds appropriate element selector
		 * 
		 * @param string name
		 * @return string Prepared selector name
		 */
		buildSelector : function(name){
			// If group is specified, then gotta build a selector for that
			if ($.group) {
				return "[name='" + $.group + "[" + name + "]" + "']";
			} else {
				return "[name='" + name + "']";
			}
		},

		/**
		 * Remove all previous error classes if present
		 * 
		 * @return void
		 */
		resetAll : function(){
			$("div.form-group").removeClass('has-error').addClass('has-success');
		},

		/**
		 * Creates populated UL element
		 * 
		 * @param object messages JSON object with error messages
		 * @return HTMLElement
		 */
		createUl : function(messages){
			var ul = document.createElement('ul');

			for (var key in messages) {
				var li = document.createElement('li');
				var text = messages[key];

				$(li).text(text);
				$(ul).append(li);
			}

			// Return populated UL element
			return ul;
		},

		/**
		 * Displays modal dialog with error messages
		 * 
		 * @return void
		 */
		displayModal : function(messages){
			var $modal = $("#errors-modal");
			var text = this.createUl(messages);

			$modal.find(".modal-body").empty().html(text);
			$modal.modal("show");
		},
		
		/**
		 * Renders error messages highlighting fields
		 * 
		 * @param object data
		 * @return void
		 */
		render : function(data){
			// To access errorHandler instance inside functions
			var self = this;

			for (var key in data.names) {
				// Name represents a name of an Element, that contains an error
				var name = data.names[key];
				// OK, we got an instance of current element which contain an error
				var $targetElement = $(self.buildSelector(name));
				// That's not fast, but reliable at least
				var $row = $targetElement.closest('div.form-group');

				$row.each(function(){
					if ($targetElement.attr('name') == self.buildName(name)){
						if ($(this).hasClass('has-success')) {
							$(this).removeClass('has-success');
						}

						$(this).addClass('has-error');
					}
				});
			}
		},
		
		/**
		 * Handles server response
		 * 
		 * @param string response
		 * @return void
		 */
		handleResponse : function(response){
			this.resetAll();

			try {
				var data = $.parseJSON(response);
				var messages = data.messages;

				this.render(data);

			} catch(e) {
				var messages = response;
			}

			this.displayModal(messages);
		}
	};
	
	$.setFormGroup = function(group){
		$.group = group;
	};

	$.showErrors = function(response){
		errorHandler.handleResponse(response);
		$("#scroller").click();
	}

	$("a.mode-link").click(function(event){
		event.preventDefault();
		var mode = $(this).data('mode-id');
		
		$.ajax({
			url : "/admin/kernel/mode-change",
			data : {
				mode : mode
			},
			success : function(response) {
				if (response == "1") {
					window.location.reload();
				} else {
					console.log(response);
				}
			}
		});
	});
	
	$("[data-button='refresh']").click(function(event){
		event.preventDefault();
		window.location.reload();
	});
	
	
	$("[data-button='change-content-language']").click(function(event){
		event.preventDefault();
		// Get selected language id
		var id = $(this).data('language-id');
		
		$.ajax({
			url : "/admin/languages/change.ajax",
			data : {
				id : id
			},
			success : function(response) {
				if (response == "1") {
					window.location.reload();
				} else {
					console.log(response);
				}
			}
		});
	});
	
	
	$("[data-toggle='tooltip']").tooltip({
		placement: $(this).data('placement')
	});
	
	
	$("[data-button='slug']").click(function(event){
		event.preventDefault();
		$.ajax({
			url : "/admin/kernel/generate-slug",
			data : {
				title : $("[data-input='title']").val()
			},
			beforeSend : function(){
				// Cancel global beforeSend() with this empty function
			},
			success : function(response){
				$("[data-input='slug']").val(response);
			}
		});
	});
	
	
	$("[data-button='per-page-changer']").change(function(event){
		var value = $(this).val();
		$.ajax({
			url : "/admin/kernel/items-per-page",
			data : {
				count : value,
			},
			success : function(response) {
				if (response == "1") {
					window.location.reload();
				} else {
					console.log(response);
				}
			}
		});
	});
	
	
	$("[data-button='options']").click(function(event) {
		event.preventDefault();
		$("div.options").slideToggle(1000);
	});
	
	
	// ------------------------------------ Delete on tables ---------------------

	$.delete = function(options){
		var modalSelector = '#myModal';
		
		// Sometimes CSS styles might conflict, so it would be nice to add alternate name
		var btnSelector = '[data-button="delete"], [data-button="remove"]';
		
		var ajaxHandler = function(url, id, callback){
			// In future version we might need a promise
			return $.ajax({
				url : url,
				data : {
					id : id
				},
				success : function(response) {
					if (typeof(callback) != "undefined") {
						callback(response);
					} else {
						if (response == "1") {
							window.location.reload();
						} else {
							$.showErrors(response);
						}
					}
				}
			});
		};
		
		$(btnSelector).click(function(event) {
			event.preventDefault();
			$(modalSelector).modal();
			
			var category = $(this).data('category');
			var id = $(this).data('id');
			var message = $(this).data('message');
			
			// If there's a custom message, then display it instead of default one
			if (message){
				$(modalSelector).find('.modal-body').html(message);
			}
			
			// Ensure both are provided
			if (category == "undefined" || id == "undefined") {
				throw new Error('A button should provide both id and category');
			}
			
			// Detach and attach onclick listener to avoid appending
			$("#delete-yes-modal-btn").off('click').click(function(event){
				if (typeof(options.categories) != "undefined") {
					for (var key in options.categories) {
						var url = options.categories[key].url;
						if (key == category) {
							// By default
							var callback = undefined;
							
							if (typeof(options.categories[key].success) != "undefined"){
								callback = options.categories[key].success;
							}
							
							ajaxHandler(url, id, callback);
							break;
						}
					}
				}
			});
		});
	};

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
