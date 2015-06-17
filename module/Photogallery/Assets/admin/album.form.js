
$(function() {
	
	$.wysiwyg.init(['description']);


	function update(success) {
		$("form").send({
			url : "/admin/module/photogallery/album/edit.ajax",
			before : function(){
				$.wysiwyg.update();
			},
			
			success : success
		});
	}
	
	
	function add(success){
		$("form").send({
			url	   : "/admin/module/photogallery/album/add.ajax",
			before : function(){
				$.wysiwyg.update();
			},
			success : success
		});
	}
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/photogallery';
	});
	
	
	$("[data-button='add']").click(function(){
		add(function(response){
			if ($.isNumeric(response)){
				window.location = '/admin/module/photogallery/album/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='add-create']").click(function(){
		add(function(response){
			if ($.isNumeric(response)){
				window.location = '/admin/module/photogallery/album/add';
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save']").click(function(){
		update(function(response){
			if (response =="1") {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save-create-new']").click(function(){
		update(function(response){
			if (response == "1"){
				window.location = '/admin/module/photogallery/album/add';
			} else {
				$.showErrors(response);
			}
		});
	});
	
});
