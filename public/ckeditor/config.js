/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

// CKEDITOR.editorConfig = function( config ) {
// 	// Define changes to default configuration here. For example:
// 	// config.language = 'fr';
// 	// config.uiColor = '#AADC6E';
// 	config.toolbar = [
// 		// { name: 'document', items: [ 'Source', '-', 'Save', 'NewPage', 'ExportPdf', 'Preview', 'Print', '-', 'Templates' ] },
// 		{ name: 'document', items: [ 'Source', '-', 'Preview', 'Print', '-', 'Templates' ] },
// 		{ name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
// 		{ name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
// 		{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
// 		'/',
// 		{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
// 		{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
// 		{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
// 		{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
// 		'/',
// 		{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
// 		{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
// 		{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
// 		{ name: 'about', items: [ 'About' ] }
// 	];
// 	var lite = config.lite = config.lite || {};
// 	config.extraPlugins = 'lite';
// 	config.removePlugins ="registered,pagebreakCmd,pagebreak,indentblock,indent,indentlist,list,pastefromword,flash,showblocks,specialchar,colordialog,div,divarea,templates";
// 	lite.includes_debug = ["js/rangy/rangy-core.js", "js/ice.js", "js/dom.js", "js/selection.js", "js/bookmark.js","lite-interface.js"];
// 	// set to false if you want change tracking to be off initially
// 	lite.isTracking = true;
// 	lite.userStyles = {
// 			"21": 3,
// 			"15": 1,
// 			"18": 2
// 		};

// 	// these are the default tooltip values. If you want to use this default configuration, just set lite.tooltips = true;
// 	lite.tooltips = {
// 		show: true,
// 		path: "js/opentip-adapter.js",
// 		classPath: "OpentipAdapter",
// 		cssPath: "css/opentip.css",
// 		delay: 500
// 	};
// 	lite.tooltipTemplate = "%a by %u, first edit %t, last edit %T";
// //	lite.commands = [/*LITE.Commands.TOGGLE_TRACKING, */LITE.Commands.TOGGLE_SHOW/*, LITE.Commands.ACCEPT_ALL, LITE.Commands.REJECT_ALL, LITE.Commands.ACCEPT_ONE, LITE.Commands.REJECT_ONE */];
// 	config.enterMode = CKEDITOR.ENTER_BR;
// 	config.autoParagraph = false;
// 	config.title = false;
// };
