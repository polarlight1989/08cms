/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.dialog.add( 'media', function( editor ){

	return {
		title : editor.lang.media.title,
		minWidth : 450,
		minHeight : 200,
		onOk : function(){
			// Always create a new media, because of IE BUG.
			var src = this.getValueOf( 'info', 'src' ),
				element = element = editor.document.createElement(CKEDITOR.env.ie ? '<embed name="MediaPlayer">' : 'embed'),
				width  = parseInt(this.getValueOf( 'info', 'width' )),
				height = parseInt(this.getValueOf( 'info', 'height')),
				hspace = parseInt(this.getValueOf( 'info', 'hSpace')),
				vspace = parseInt(this.getValueOf( 'info', 'vSpace')),
				player = this.getValueOf( 'info', 'player');
			isNaN(width ) && (width  = 320);
			isNaN(height) && (height = 240);
			if(player == '-'){
				player  = src.lastIndexOf('.');
				player == -1 || (player = src.substr(player + 1, player + 3).toLowerCase());
				if(player == 'rm'){
					player = 'realplayer';
				}else{
					player = 'mediaplayer';
				}
			}
			element.setAttribute('src', src);
			element.setAttribute('name', 'MediaPlayer');
			element.setAttribute('align', 'baseline');
			element.setAttribute('border', 0);
			element.setAttribute('width', width);
			element.setAttribute('height', height);
			isNaN(hspace) || element.setAttribute('hspace', hspace);
			isNaN(vspace) || element.setAttribute('vspace', vspace);
			element.setAttribute('wmode', 'transparent');
			if(player == 'realplayer'){
				element.setAttribute('quality', 'hight');
				element.setAttribute('type', 'audio/x-pn-realaudio-plugin');
				element.setAttribute('autostart', 'true');
				element.setAttribute('controls', 'IMAGEWINDOW,ControlPanel,StatusBar');
				element.setAttribute('console', 'Clip1');
			}else if(player == 'mediaplayer'){
				element.setAttribute('type', 'application/x-mplayer2');
				element.setAttribute('pluginspage', 'http://www.microsoft.com/isapi/redir.dll?prd=windows&amp;sbp=mediaplayer&amp;ar=media&amp;sba=plugin&amp;');
				element.setAttribute('showcontrols', 1);
				element.setAttribute('showdisplay', 0);
				element.setAttribute('showstatusbar', 1);
				element.setAttribute('autosize', 0);
				element.setAttribute('showgotobar', 0);
				element.setAttribute('showcaptioning', 0);
				element.setAttribute('autostart', 1);
				element.setAttribute('autorewind', 0);
				element.setAttribute('animationatstart', 0);
				element.setAttribute('transparentatstart', 0);
				element.setAttribute('allowscan', 1);
				element.setAttribute('enablecontextmenu', 1);
				element.setAttribute('clicktoplay', 0);
				element.setAttribute('invokeurls', 1);
				element.setAttribute('defaultframe', 'datawindow');
			}

			// Move contents and attributes of old media to new media.
			if ( this.editMode )
			{
				this.editObj.copyAttributes( element, { name : 1 } );
				this.editObj.moveChildren( element );
			}

			// Apply or remove flash parameters.
			var extraStyles = {
				width  : width + 'px',
				height : height + 'px'
			},extraAttr = {};
			isNaN(hspace) || (extraAttr.hspace = hspace + 'px');
			isNaN(vspace) || (extraAttr.vspace = vspace + 'px');

			var newFakeImage = editor.createFakeElement( element, 'cke_media', 'media', true );
			newFakeImage.setStyles( extraStyles );
//			newFakeImage.setAttributes( extraAttr );//element: newFakeImage.$
//			if ( this.fakeImage )
//				newFakeImage.replace( this.fakeImage );
//			else
				editor.insertElement( newFakeImage );
			return true;
		},
		onShow : function(){
			// Clear previously saved elements.
			this.fakeImage = this.objectNode = this.embedNode = null;

			// Try to detect any embed tag that has Media parameters.
			var fakeImage = this.getSelectedElement();
			if ( fakeImage && fakeImage.getAttribute( '_cke_real_element_type' ) && fakeImage.getAttribute( '_cke_real_element_type' ) == 'media' ){
				this.fakeImage = fakeImage;
				var realElement = editor.restoreRealElement( fakeImage ),
					embedNode = null, paramMap = {};
				if (realElement.getName() == 'embed' || realElement.getName() == 'cke:embed'){
					var hspace,vspace,type,match,html,reg = /\b(?:hspace|vspace|type)\s*=\s*(?:\w+|"[^"\\]*(?:\\.[^"\\]*)*"|'[^'\\]*(?:\\.[^'\\]*)*')/gi;
					html = decodeURIComponent(fakeImage.$.getAttribute('_cke_realelement'));
					while(match = reg.exec(html))eval(match[0]);
					this.getContentElement( 'info', 'src' ).setValue(realElement.getAttribute('src'));
					this.getContentElement( 'info', 'width' ).setValue(realElement.getAttribute('width'));
					this.getContentElement( 'info', 'height' ).setValue(realElement.getAttribute('height'));
					this.getContentElement( 'info', 'hSpace' ).setValue(hspace);
					this.getContentElement( 'info', 'vSpace' ).setValue(vspace);
					this.getContentElement( 'info', 'player' ).setValue(/mplayer/i.test(type) ? 'mediaplayer' : 'realplayer');
				}
				this.embedNode = embedNode;
			}
		},
		contents : [
			{
				id : 'info',
				label : editor.lang.common.generalTab,
				accessKey : 'I',
				elements :
				[
					{
						type : 'text',
						id : 'src',
						label : editor.lang.media.src,
						validate : function(){
							var val = this.getValue(), pos = val.lastIndexOf('.');
							if (!val || (editor.config.allowUploadMediaType && (pos == -1 || !(new RegExp('^(' + editor.config.allowUploadMediaType.replace(/,/g, '|') + ')$')).test(val.substr(pos + 1))))){
								alert(editor.config.allowUploadMediaType ? editor.lang.media.errorType.replace('%s', editor.config.allowUploadMediaType) : editor.lang.media.errorFile);
								return false;
							}
							return true;
						}
					},
					{
						type : 'hbox',
						widths : [ '25%', '25%', '25%', '25%', '25%' ],
						children :
						[
							{
								type : 'text',
								id : 'width',
								style : 'width:95px',
								label : editor.lang.media.width,
								'default' : 320,
								validate : CKEDITOR.dialog.validate.integer( editor.lang.media.validateWidth )
							},
							{
								type : 'text',
								id : 'height',
								style : 'width:95px',
								label : editor.lang.media.height,
								'default' : 240,
								validate : CKEDITOR.dialog.validate.integer( editor.lang.media.validateHeight )
							},
							{
								type : 'text',
								id : 'hSpace',
								style : 'width:95px',
								label : editor.lang.media.hSpace,
								validate : CKEDITOR.dialog.validate.integer( editor.lang.media.validateHSpace )
							},
							{
								type : 'text',
								id : 'vSpace',
								style : 'width:95px',
								label : editor.lang.media.vSpace,
								validate : CKEDITOR.dialog.validate.integer( editor.lang.media.validateVSpace )
							}
						]
					},
					{
						type : 'hbox',
						widths : [ '33%', '33%', '33%'],
						children :
						[
							{
								id : 'player',
								type : 'radio',
								label : editor.lang.media.player,
								'default' : '-',
								items :
								[
									[ editor.lang.media.mediaplayer, 'mediaplayer'],
									[ editor.lang.media.realplayer, 'realplayer'],
									[ editor.lang.media.autoplayer, '-']
								]
							}
						]
					}
				]
			},
			{
				id : 'Upload',
				hidden : true,
				filebrowser : 'uploadButton',
				label : editor.lang.common.upload,
				elements :
				[
					{
						type : 'file',
						id : 'upload',
						style:'height:40px',
						label : editor.lang.common.upload,
						size : 38
					},
					{
						type : 'fileButton',
						id : 'uploadButton',
						label : editor.lang.common.uploadSubmit,
						filebrowser : 'info:src',
						'for' : [ 'Upload', 'upload' ]
					}
				]
			}
		]
	};
} );
