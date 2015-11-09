/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Horizontal Page Break
 */

// Register a plugin named "pagebreak".
CKEDITOR.plugins.add( '08cms_pagebreak', {
	init : function( editor ){
		// Register the command.
		editor.addCommand( 'pagebreak', {
			exec : function( editor ){
				// Create the element that represents a print break.
				var breakObject =  editor.document.createText('[##]');
	
				var ranges = editor.getSelection().getRanges();
//
//				for ( var range, i = 0 ; i < ranges.length ; i++ ){
//					range = ranges[ i ];
//		
//					if ( i > 0 )
//						breakObject = breakObject.clone( true );
//		
//					range.splitBlock( 'p' );
//					range.insertNode( breakObject );
//				}
					ranges[0].splitBlock( 'p' );
					var p = ranges[0].startContainer.getChild( ranges[0].startOffset );
					p.$.parentNode.insertBefore(editor.document.$.createTextNode('[##]'), p.$);
//					alert(breakObject.insertBefore);
//					ranges[0].insertNode( breakObject );
//					editor.insertElement( breakObject );
			}
		});

		// Register the toolbar button.
		editor.ui.addButton( '08cms_PageBreak', {
				label : editor.lang.pagebreak,
				command : 'pagebreak'
			});
	}
});

