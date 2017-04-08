/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	config.toolbar = [
		{ items: [ 'Maximize', 'Source', 'Cut', 'Paste', 'PasteText'] },
		{ items: [ 'Undo', 'Redo', 'RemoveFormat', 'Bold', 'Italic', 'Underline', 'Subscript', 'Superscript'] },
		{ items: [ 'NumberedList', 'BulletedList', 'Outdent', 'Indent' ] },
		{ items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight' ] },
		{ items: [ 'Link', 'Unlink', 'Anchor', 'Image', 'Flash', 'Table', 'SpecialChar' ] },
		{ items: [ 'TextColor', 'BGColor' ] },
		{ items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
		
	];
};
