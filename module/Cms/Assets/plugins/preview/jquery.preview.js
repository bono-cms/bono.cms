
/**!
 * Small jquery plugin to work with previews
 * version 1.0
 * 
 * Copyright (c) No Global State Lab
 */

(function($){
	
	$.fn.preview = function(success) {
		if (!window.FileReader) {
			throw new Error("Preview requires FileReader class available in HTML5 only");
		}
		
        // Ensure the attribute exists before calling toLowerCase
        var attr = $(this).attr('type');
        var type = attr ? attr.toLowerCase() : null;

        if (type !== "file") {
            throw new Error("You can attach a preview to file inputs only! Found: " + this.tagName);
        }

		$(this).change(function() {
			// We're safe to attach a listener now
			for (var i = 0; i < this.files.length; i++ ) {
				var file = this.files[i];
				
				// We only want images
				if (file.type.match(/image.*/)) {
					var reader = new FileReader();
					
					// First of all attach the even handler
					reader.onloadend = function(event) {
						// file - is optional, can be used when working with FormData
						success(event.target.result, file);
					};
					
					// Now ready to execute the event handler
					reader.readAsDataURL(file);
				}
			}
		});
	};
	
})(jQuery);
