$(function(){
	var handle = function(state){
        // Do the batch for email elements
        $("[name='config[smtp_secure_layer]'], [name='config[smtp_host]'], [name='config[smtp_username]'], [name='config[smtp_password]'], [name='config[smtp_port]']")
        .prop('disabled', state);
	};

	var $useDriver = $("[name='config[use_smtp_driver]']");
	handle($useDriver.is(':checked'));

	$useDriver.click(function(){
		handle($(this).is(':checked'));
	});	
});