/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
 
CKEDITOR.editorConfig = function(config){
	config.language = 'en';
	// config.uiColor = '#AADC6E';
	config.skin = 'bootstrapck';
	config.filebrowserBrowseUrl = '/module/Cms/Assets/plugins/ckeditor/elfinder.php';
	config.height = 400;
	config.allowedContent = true;
    config.extraPlugins = 'youtube';
	config.entities_processNumerical = 'force';
};