@extends('layouts.app')

@section('template_title')
    {{$text}}
@endsection

@section('head')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <!-- HEADER -->
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <!-- <h6 class="nk-block-title page-title">{{$text}}</h6> -->
                        </div>
                    </div>
                </div> 
                <div class="mt-2" id="modalform">
                    <div class="modal-dialog-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-gray">
                                <!-- <h5 class="modal-title text-white">{{ substr($text, strrpos($text, ' ') + 1) }}</h5>  -->
                                <h5 class="modal-title text-white">{{$text}}</h5> 
                            </div>
                            <div class="modal-body">
                                <form action="/gen/save/<?php echo $id ?>" method="post" enctype="" id="form_">
                                    @csrf
                                    <div class="form-group">  
                                        <label class="form-label" for="status_remarks">Content</label>
                                        <div class="form-control-wrap">
                                            <input type="hidden" name="text" value="{{$text}}">
                                            <textarea id="konten" class="form-control" name="body" rows="10" cols="50">{{ $konten }}</textarea>
                                        </div> 
                                    </div><br>
                                    </form>
                                    <div class="row">
                                        <div class="col-md-6"> 
                                            <!-- <button href="{{ url('aipsubmission')}}"class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Cancel</button> -->
                                            <button onclick="history.back()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Cancel</button>
                                            <button type="submit" class="btn btn-dim btn-dark" id="btn_form"><i class="icon ni ni-save-fill" ></i> Save</button>
                                        </div>
                                    </div>
                                
                            </div>
                            <div class="modal-footer bg-light"><span class="sub-text">{{$text}}</span> </div>
                            </div>
                            </div>
                        </div> 
            </div>
        </div>
    </div>
    

@endsection
@section('footer_scripts')     
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script> 
<script type="text/javascript" src="{{ asset('ckeditor/plugins/lite/lite-interface.js') }}"></script>
<script type="text/javascript"> 
$('#btn_form').click(function(){
    $('#form_').submit();
});
var konten = document.getElementById("konten");
CKEDITOR.replace(konten,{
     language:'en-gb',
     filebrowserUploadUrl: "{{route('uploadCK', ['_token' => csrf_token() ])}}",
     filebrowserUploadMethod: 'form'
});
CKEDITOR.config.allowedContent = true; 
   
CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.toolbar = [
		// { name: 'document', items: [ 'Source', '-', 'Save', 'NewPage', 'ExportPdf', 'Preview', 'Print', '-', 'Templates' ] },
		{ name: 'document', items: [ 'Source', '-', 'Preview', 'Print', '-', 'Templates' ] },
		{ name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
		{ name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
		{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
		'/',
		{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
		{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
		{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
		{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
		'/',
		{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
		{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
		{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
		// { name: 'about', items: [ 'About' ] }
	];
	var lite = config.lite = config.lite || {};
	config.extraPlugins = 'lite'; 
	config.removePlugins ="registered,pagebreakCmd,pagebreak,list,pastefromword,flash,showblocks,specialchar,colordialog,div,divarea,templates";
	lite.includes_debug = ["js/rangy/rangy-core.js", "js/ice.js", "js/dom.js", "js/selection.js", "js/bookmark.js","lite-interface.js"];
	// set to false if you want change tracking to be off initially
	lite.isTracking = true;
	lite.userStyles = {
			"21": 3,
			"15": 1,
			"18": 2
		};

	// these are the default tooltip values. If you want to use this default configuration, just set lite.tooltips = true;
	lite.tooltips = {
		show: true,
		path: "js/opentip-adapter.js",
		classPath: "OpentipAdapter",
		cssPath: "css/opentip.css",
		delay: 100
	};
	lite.tooltipTemplate = "%a by {{Auth::user()->name}}, first edit %t, last edit %T"; 
	lite.commands = [LITE.Commands.TOGGLE_TRACKING, LITE.Commands.TOGGLE_SHOW, LITE.Commands.ACCEPT_ALL, LITE.Commands.REJECT_ALL, LITE.Commands.ACCEPT_ONE, LITE.Commands.REJECT_ONE ];
	config.enterMode = CKEDITOR.ENTER_BR;
	config.autoParagraph = false;
	config.title = false;
    config.lite.userId ='{{Auth::user()->id}}';
    config.lite.userName ='{{Auth::user()->name}}'; 
}; 

</script>  
@endsection