<?php

// Can't rely on $_SERVER['ROOT'], that's why building path to the root folder manually
$root = dirname(dirname(dirname(dirname(dirname(__DIR__)))));

// Grab service locator
$app = require($root.'/config/bootstrap.php');
$sl = $app->bootstrap();

// Grab required services
$authManager = $sl->get('authManager');
$response = $sl->get('response');

// Finally do validate rights
if (!$authManager->isAllowed(array('dev', 'user'))) {
	$response->setStatusCode(403)
			 ->send('You have no rights to access File Manager');
	exit;
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>File Manager</title>
		
		<script src="/module/Cms/Assets/plugins/jquery/1.7.2/jquery.min.js"></script>
		<script src="/module/Cms/Assets/plugins/jquery-ui/1.8.18/jquery-ui.min.js"></script>
		<script src="/module/Cms/Assets/plugins/elfinder2/js/elfinder.min.js"></script>
		<script src="/module/Cms/Assets/plugins/elfinder2/js/i18n/elfinder.ru.js"></script>
		
		<link rel="stylesheet" media="screen" href="/module/Cms/Assets/plugins/jquery-ui/1.8.18/themes/pepper-grinder/jquery-ui.css" />
		<link rel="stylesheet" href="/module/Cms/Assets/plugins/elfinder2/css/elfinder.min.css" />
		
	</head>
	
	<body>
		
		<!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>
		
		<script>
			
			$(function() {
				
				// Helper function to get parameters from the query string.
				function getUrlParam(paramName) {
					var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
					var match = window.location.search.match(reParam) ;
					
					return (match && match.length > 1) ? match[1] : '' ;
				}
				
				var funcNum = getUrlParam('CKEditorFuncNum');
				var elf = $('#elfinder').elfinder({
					url		: '/module/Cms/Assets/plugins/elfinder2/php/connector.php?url=/uploads/',
					height : "500",
					getFileCallback : function(file) {
						window.opener.CKEDITOR.tools.callFunction(funcNum, file);
						window.close();
					}
					
				}).elfinder('instance');
			});
			
		</script>
		
	</body>
</html>
