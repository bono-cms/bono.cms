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
	});
}
