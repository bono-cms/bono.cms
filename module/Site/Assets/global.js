/**
 * Global options for the whole site must be defined here
 */

// Do set global AJAX options in case jquery has been loaded
if (window.jQuery){
	$(function(){
		$.ajaxSetup({
			cache : false,
			charset : "UTF-8",
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		
		$.validator = {
			/**
			 * Builds a selector for a target element
			 * 
			 * @param string name Element's name
			 */
			buildElementSelector : function(name){
				return selector = '[name="' + name + '"]';
			},
			
			/**
			 * Finds container element by a child's name inside it
			 * 
			 * @param string name Child element's name
			 * @return HTMLElement
			 */
			getContainerElementByClosestName : function(name){
				var selector = this.buildElementSelector(name);
				return $(selector).closest('div.form-group');
			},
			
			/**
			 * Returns parent element's container
			 * 
			 * @param string name Child element's name
			 */
			getParentContainer : function(name){
				var selector = this.buildElementSelector(name);
				return $(selector).parent();
			},

			/**
			 * Creates message block
			 * 
			 * @param string text Text to be appeared within container
			 */
			createMessageElement : function(text){
				var element = document.createElement('span');

				// Configure element for bootstrap
				$span = $(element).attr('class', 'help-block')
								  .text(text);
				
				return $span;
			},

			/**
			 * Checks whether parent element has a helper block
			 * 
			 * @param $container
			 */
			hasHelpBlock : function($container) {
				return $container.length > 0;
			},

			/**
			 * Shows an error which belongs to a field
			 * 
			 * @param string name Element's name
			 * @param string message To be appended
			 */
			showErrorOn : function(name, message){
				$container = this.getContainerElementByClosestName(name);
				
				if ($container.hasClass('has-success')) {
					$container.removeClass('has-success');
				}
				
				$container.addClass('has-error');

				$span = this.createMessageElement(message);

				$parent = this.getParentContainer(name);
				$parent.append($span);
			},

			// Resets control elements to their initial state
			resetAll : function(){
				// Classes we'd like to remove when reseting all
				var classes = ['has-error', 'has-warning', 'has-success'];

				$('div.form-group').each(function(){
					for (var key in classes) {
						// Value represents class name
						var value = classes[key];

						if ($(this).hasClass(value)) {
							$(this).removeClass(value);
						}
					}
				});
				
				// Now we'd assume that everything is okay, and later remove this class on demand
				$('div.form-group').addClass('has-success');

				// Remove all helper spans
				$("span.help-block").remove();
			},

			/**
			 * Shows error messages
			 * 
			 * @param string response Server's response
			 */
			handleAll : function(response){
				// Clear all previous messages and added classes
				this.resetAll();

				// if its not JSON, but "1" then we'd assume success
				if (response == "1") {
					// Since we might have a flash messenger, we'd simply reload current page
					window.location.reload();
				} else {
					// Otherwise, try to handle JSON data
					try {
						var data = $.parseJSON(response);

						for (var name in data){
							var message = data[name];
							this.showErrorOn(name, message);
						}

					} catch(e) {
						// Otherwise we'd assume that something went wrong
						console.log(response);
					}
				}
			}
		};
	});
}
