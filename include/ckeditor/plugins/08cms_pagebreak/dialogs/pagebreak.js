/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.dialog.add( 'pagebreak', function( editor ){

	return {
		title : editor.lang.pagebreak.title,
		minWidth : 320,
		minHeight : 50,
		onOk : function(){
			var subject = this.getValueOf( 'info', 'subject' );
			// Create the element that represents a print break.
			var breakObject = CKEDITOR.dom.element.createFromHtml( '<p rev="pagebreak">[#' + subject + '#]</p>' );

			editor.lang.fakeobjects.pagebreak = editor.lang.pagebreak.fakeobjects.replace('%s', subject);
			// Creates the fake image used for this element.
			breakObject = editor.createFakeElement( breakObject, 'cke_pagebreak', 'pagebreak', true );
	
			
			if ( !this.fakeImage ) editor.getSelection().getRanges()[0].splitBlock( 'p' );
			editor.insertElement( breakObject );
			return true;
		},
		onShow : function(){
			this.fakeImage = null;
			// Try to detect any embed tag that has Media parameters.
			var fakeImage = this.getSelectedElement(), html;
			if ( fakeImage && fakeImage.getAttribute( '_cke_real_element_type' ) && fakeImage.getAttribute( '_cke_real_element_type' ) == 'pagebreak' ){
				this.fakeImage = fakeImage;
				html = /\[#(.*)#\]/.exec(decodeURIComponent(fakeImage.$.getAttribute('_cke_realelement')));
				this.getContentElement( 'info', 'subject' ).setValue(html ? html[1] : '');
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
						id : 'subject',
						label : editor.lang.pagebreak.subject
					}
				]
			}
		]
	};
} );
