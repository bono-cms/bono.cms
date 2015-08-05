
$(function() {

	$.wysiwyg.init(['product[description]']);
	$.setFormGroup('product');

	/**
	 * Updates a product
	 * 
	 * @param callable callback
	 * @return void
	 */
	function update(callback) {
		$("form").send({
			url : "/admin/module/shop/product/edit.ajax",
			success : callback
		});
	}

	/**
	 * Adds a product
	 * 
	 * @param callable callback
	 * @return void
	 */
	function add(callback) {
		$("form").send({
			url : "/admin/module/shop/product/add.ajax",
			success : callback
		});
	}

	/**
	 * Creates row in the image table
	 * 
	 * @param string img blob image data
	 * @return void
	 */
	function createRow(img) {
		// Temporary solution
		var content = 
		'<tr>' + 
			'<td><img data-image="preview" src="' + img + '"'  + '></td>' + 
			'<td></td>' + 
			'<td></td>' +
			'<td></td>' + 
			'<td>' +
				'<a data-button="delete" href="#"><i class="glyphicon glyphicon-remove"></i></a>' +
			'</td>' +
		'</tr>';

		return content;
	}

	/**
	 * Create a new file input element
	 * 
	 * @return DOMElement
	 */
	function createFileElement(){
		var input = document.createElement('input');

		$(input).attr({
			type : "file",
			name : "file[]",
			accept : 'image/x-png, image/gif, image/jpeg'
		});

		// Attach the click listener now to the created file element
		$(input).click(function(){
			$(this).preview(function(data){
				$("[data-container='image']").append(createRow(data));
			});
		});

		return input;
	}
	
	$('[data-button="upload"]').click(function(event){
		event.preventDefault();
		
		var input = createFileElement();
		
		// Now append prepared DOM element into our container
		$("#file-input-container").append(input);
		
		input.click();
	});
	
	
	$("[data-button='edit']").click(function(event){
		event.preventDefault();
		
		// Current image file
		var id = $(this).data('image');
		
		// Get current DOMElement
		var $file = $(this).parent().find("input[type='file']");
		
		// Current image
		var $img = $(this).parent().parent().find("td img");
		
		$file.preview(function(imgData){
			$img.attr({
				'data-image' : 'preview',
				'src' : imgData
			});
		});
		
		$file.click();
	});
	
	
	$(document).on('click', "[data-button='delete']", function(event){
		event.preventDefault();
		var $row = $(this).parent().parent();
		
		// Remove last input from container
		$("#file-input-container > input:last-child").remove();
		
		$row.empty();
	});
	
	
	$("[data-button='save']").click(function(){
		update(function(response){
			if (response == "1") {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	
	$("[data-button='add-create']").click(function(){
		add(function(response){
			if ($.isNumeric(response)) {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='add']").click(function() {
		add(function(response) {
			if ($.isNumeric(response)) {
				window.location = '/admin/module/shop/product/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/shop';
	});
	
});

