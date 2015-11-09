/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
CKEDITOR.lang.languages = {en:1,zh:1,'zh-cn':1};
CKEDITOR.editorConfig = function( config ){
	//get by "ckeditor.js" file path
	var _08cms_abs = CKEDITOR.getUrl('../../');
	// Define changes to default configuration here. For example:
	//config.language = 'en';
	// config.uiColor = '#AADC6E';
	config.skin = 'v2';

	config.extraPlugins = '08cms_media,08cms_pagebreak';
	config.removePlugins = 'pagebreak';

//	config.filebrowserBrowseUrl      = '/ckfinder/ckfinder.html';
	config.filebrowserImageUploadUrl = _08cms_abs + 'upload.php?action=upload&type=image&mode=cke';
	config.filebrowserFlashUploadUrl = _08cms_abs + 'upload.php?action=upload&type=flash&mode=cke';
	config.filebrowserMediaUploadUrl = _08cms_abs + 'upload.php?action=upload&type=media&mode=cke';
	config.filebrowserUploadUrl      = _08cms_abs + 'upload.php?action=upload&type=file&mode=cke';
//	config.allowUploadMediaType      = 'rm,rmvb,mpg,wmv,asf,avi';

/*
	config.toolbar_Full = [
		['Source','-','Save','NewPage','Preview','-','Templates'],
		['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
		'/',
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink','Anchor'],
		['Image','Flash','Media','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		'/',
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Maximize', 'ShowBlocks','-','About']
	];*/
	config.toolbar = '08CMS';
	config.toolbar_08CMS = [
		['Source','Preview','Print'],
		['Cut','Copy','Paste','PasteText','PasteFromWord'],
		['Undo','Redo','Find','Replace','SelectAll','RemoveFormat'],
		['Link','Unlink','Anchor'],
		['Image','Flash','Media','Table','HorizontalRule','Smiley','SpecialChar','08cms_PageBreak','ShowBlocks'],
		'/',
		['Font','FontSize'],
		['TextColor','BGColor'],
		['Bold','Italic','Underline','Strike','Subscript','Superscript'],
		['NumberedList','BulletedList','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']
	];
	config.toolbar_simple = [
		['Source','Preview'],
		['Bold','Italic','Underline','Strike','Subscript','Superscript'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink','Anchor','08cms_PageBreak'],
	];
	config.font_names = '宋体/宋体;黑体/黑体;仿宋/仿宋_GB2312;楷体/楷体_GB2312;隶书/隶书;幼圆/幼圆;' + config.font_names;
};
