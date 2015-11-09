/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

!function(){
var flashFilenameRegex = /\.swf(?:$|\?)/i,
	numberRegex = /^\d+(?:\.\d+)?$/,
	key,lang,langs = {
		'zh-cn':{
			src : '源地址',
			title : '视频',
			width : '宽度',
			height : '高度',
			hSpace : '水平间距',
			vSpace : '垂直间距',
			toolbar : '插入视频',
			properties : '视频属性',
			errorFile : '视频地址必需填写！',
			errorType : '视频地址必需填写，并且只能为 “%s” 类型！',
			validateWidth : '视频宽度应该是一个正整数',
			validateHeight : '视频高度应该是一个正整数',
			validateHSpace : '水平间距应该是一个正整数',
			validateVSpace : '垂直间距应该是一个正整数',
			fakeobjects : '视频',
			player : '播放器',
			mediaplayer : 'Windows Media 播放器',
			realplayer : 'RealPlayer 播放器',
			autoplayer : '自动选择'
		},
		zh:{
			src : 'URL',
			title : '视讯',
			width : '宽度',
			height : '高度',
			hSpace : '水平间距',
			vSpace : '垂直间距',
			toolbar : '插入视讯',
			properties : '视讯属性',
			errorFile : '视讯地址必需填写！',
			errorType : '视讯地址必需填写，并且只能为 “%s” 类型！',
			validateWidth : '视讯宽度应该是一个正整数',
			validateHeight : '视讯高度应该是一个正整数',
			validateHSpace : '水平间距应该是一个正整数',
			validateVSpace : '垂直间距应该是一个正整数',
			fakeobjects : '视讯',
			player : '播放器',
			mediaplayer : 'Windows Media 播放器',
			realplayer : 'RealPlayer 播放器',
			autoplayer : '自动选择'
		},
		en:{
			src : 'URL',
			title : 'Media',
			width : 'Width',
			height : 'Height',
			hSpace : 'Horizontal spacing',
			vSpace : 'Vertical spacing',
			toolbar : 'Insert media',
			properties : 'Media properties',
			errorFile : 'Media addresses must fill in!',
			errorType : 'Media addresses must fill in, and file type must in "%s" !',
			validateWidth : 'Media width should be a positive integer',
			validateHeight : 'Media height should be a positive integer',
			validateHSpace : 'Horizontal spacing should be a positive integer',
			validateVSpace : 'Vertical spacing should be a positive integer',
			fakeobjects : 'Media',
			player : 'Player',
			mediaplayer : 'Windows Media player',
			realplayer : 'RealPlayer player',
			autoplayer : 'Auto select'
		}
	};
for(var key in CKEDITOR.lang){if(key in CKEDITOR.lang.languages){
	lang = key;
	(key in langs) || (key = 'en');
	CKEDITOR.lang[lang] || (CKEDITOR.lang[lang] = CKEDITOR.lang.en);
	CKEDITOR.lang[key].media = langs[key];
	CKEDITOR.lang[key].fakeobjects.media = langs[key].fakeobjects;
	break;
}}
function cssifyLength( length ){
	if ( numberRegex.test( length ) )
		return length + 'px';
	return length;
}
function createFakeElement( editor, realElement ){
	var fakeElement = editor.createFakeParserElement( realElement, 'cke_media', 'media', true ),
		fakeStyle = fakeElement.attributes.style || '';

	var width = realElement.attributes.width,
		height = realElement.attributes.height;

	if ( typeof width != 'undefined' )
		fakeStyle = fakeElement.attributes.style = fakeStyle + 'width:' + cssifyLength( width ) + ';';

	if ( typeof height != 'undefined' )
		fakeStyle = fakeElement.attributes.style = fakeStyle + 'height:' + cssifyLength( height ) + ';';

	return fakeElement;
}
CKEDITOR.plugins.add( '08cms_media',{
	init : function( editor ){
		// Add the link and unlink buttons.
		editor.addCommand( 'media', new CKEDITOR.dialogCommand( 'media' ) );
		editor.ui.addButton( 'Media',{
				icon : this.path + 'images/icon.gif',
				label : editor.lang.media.toolbar,
				command : 'media'
			} );
		CKEDITOR.dialog.add( 'media', this.path + 'dialogs/media.js' );

		// Add the CSS styles for anchor placeholders.
		editor.addCss(
			'img.cke_media' +
			'{' +
				'background-image: url(' + CKEDITOR.getUrl( this.path + 'images/media.jpg' ) + ');' +
				'background-position: center center;' +
				'background-repeat: no-repeat;' +
				'border: 1px solid #a9a9a9;' +
				'width: 80px;' +
				'height: 80px;' +
			'}'
		   	);

		// If the "menu" plugin is loaded, register the menu items.
		if ( editor.addMenuItems ){
			var m = editor._.menuGroups;
			if(!('media' in m)){
				m.media = 1;
				for(k in m)m.media++;
			}
			editor.addMenuItems({
					media :{
						label : editor.lang.media.properties,
						command : 'media',
						group : 'media'
					}
				});
		}

		// If the "contextmenu" plugin is loaded, register the listeners.
		if ( editor.contextMenu ){
			editor.contextMenu.addListener( function( element, selection ){
				if ( element && element.is( 'img' ) && element.getAttribute( '_cke_real_element_type' ) == 'media' )
					return { media : CKEDITOR.TRISTATE_OFF };
			});
		}
	},

	afterInit : function( editor ){
		var dataProcessor = editor.dataProcessor,
			dataFilter = dataProcessor && dataProcessor.dataFilter;

		if ( dataFilter ){
			dataFilter.addRules({
					elements :{
						'cke:embed' : function( element ){
							if (/mplayer|realaudio/i.test(element.attributes.type))
								return createFakeElement( editor, element );
						}
					}
				},
				3);
		}
	},

	requires : [ 'fakeobjects' ]
} );
}();