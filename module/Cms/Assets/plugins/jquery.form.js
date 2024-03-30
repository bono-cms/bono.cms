/**
 * Small jquery plugin to simplify sending forms
 */
(function($){
	/**
	 * Sends a form, including files
	 * 
	 * @param object options
	 * @return void
	 */
	$.fn.send = function(options){
		if (options.url == undefined){
            options.url = '';
		}

		if ($(this)[0].tagName.toLowerCase() != "form"){
            throw new Error('You can not send anything but form!');
		}

		$(this).off('submit').submit(function(event){
			event.preventDefault();
			var data = new FormData($(this)[0]);

			if (typeof options.before != undefined && $.isFunction(options.before)) {
				options.before();
			}

			$.ajax({
				type: "POST",
				cache: false,
				url: options.url,
				processData: false,
				contentType: false,
				data: data,
                error: function(jqXHR, textStatus, errorThrown){
                    console.log(textStatus + ' : ' + errorThrown);
                },
				success: function(response){
					if (typeof options.success != undefined && $.isFunction(options.success)) {
						options.success(response);
					}
				}
			});
		});
	};

})(jQuery);
