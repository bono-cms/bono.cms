
This little plugin allows to show thumbnails of 


// Just attach a listener, and you're ready!

$("[type='file']").preview(function(data) {
	$("[data-image='preview']").attr('src', data);
});


