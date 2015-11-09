/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Horizontal Page Break
 */
!function(){
var key,lang,langs = {
	'zh-cn':{
		title : '分页符',
		subject : '标题',
		toolbar : '插入分页符',
		properties : '分页符属性',
		fakeobjects : '分页\n标题：%s'
	},
	zh:{
		title : '分页符',
		subject : '标题',
		toolbar : '插入分页符',
		properties : '分页符属性',
		fakeobjects : '分页\n标题：%s'
	},
	en:{
		title : 'pagebreak',
		subject : 'subject',
		toolbar : 'insert pagebreak',
		properties : 'pagebreak properties',
		fakeobjects : 'pagebreak\nsubject：%s'
	}
};
for(var key in CKEDITOR.lang){if(key in CKEDITOR.lang.languages){
	lang = key;
	(key in langs) || (key = 'en');
	CKEDITOR.lang[lang] || (CKEDITOR.lang[lang] = CKEDITOR.lang.en);
	CKEDITOR.lang[key].pagebreak = langs[key];
	CKEDITOR.lang[key].fakeobjects.pagebreak = langs[key].fakeobjects;
	break;
}}
// Register a plugin named "pagebreak".
CKEDITOR.plugins.add( '08cms_pagebreak', {
	init : function( editor ){
		editor.addCommand( 'pagebreak', new CKEDITOR.pagebreakCommand() );
		// Register the toolbar button.
		editor.ui.addButton( '08cms_PageBreak', {
				label : editor.lang.pagebreak.toolbar,
				command : 'pagebreak'
			});
	},
	requires : [ 'fakeobjects' ]
})}();
CKEDITOR.pagebreakCommand=function(){};
CKEDITOR.pagebreakCommand.prototype={
	exec:function(editor){
		editor.insertHtml('[##]');
	}
};