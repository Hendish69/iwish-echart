@extends('layouts.app')

@section('template_title')
    INAVCEC/DEPFEATURES
@endsection

@section('head') 
 
    <style> 
		#info-pane {
		position: absolute;
		bottom: 30px;
		right: 30px;
		z-index: 400;
		padding: 1em;
		background: white;
		}
		.select2-dropdown.select2-dropdown--below{
			min-width: 300px!important;
		}
		button:disabled {
		  cursor: not-allowed;
		  opacity: 0.5;
		}

		table.dataTable .table th, .table td {
		  max-width: 120px;
		  vertical-align: unset;
		}
		table.dataTable td  {
		  white-space: nowrap;
		  text-overflow: ellipsis;
		  overflow: hidden;
		}
	</style> 

@endsection

@section('content') 
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
        	<div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">{{ $page->title }}</h3>
                    </div><!-- .nk-block-head-content -->
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3"> 
                                    <li class="nk-block-tools-opt"><button class="btn btn-primary" onclick="add_action()" type="button"  class="btn btn-trigger btn-icon" data-toggle="modal" data-target="#modal_edit"><em class="icon ni ni-plus"></em><span>Add New</span></button></li>
                                </ul>
                            </div>
                        </div>
                    </div><!-- .nk-block-head-content -->
                </div><!-- .nk-block-between -->
            </div><!-- .nk-block-head --> 
        	<div class="nk-block ">    
            	<div class="row g-gs" style="min-height: 100%;">
            		<div class="col-md-12">
		        	  	<div class="card card-preview">
	                        <div class="card-inner">
	                            <table class="table nk-tb-list nk-tb-ulist" data-auto-responsive="false" id="dep_feat_table">
	                                <thead>
	                                    <tr class="nk-tb-item nk-tb-head"> 
	                                        <th class="nk-tb-col"><span class="sub-text">Id</span></th> 
	                                        <th class="nk-tb-col"><span class="sub-text">Name</span></th>
	                                        <!-- <th class="nk-tb-col tb-col-mb"><span class="sub-text">Reduction</span></th> -->
	                                        <!-- <th class="nk-tb-col tb-col-md"><span class="sub-text">Description</span></th> -->
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Fcode</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">ReductionMin</span></th> 
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">ReductionMax</span></th>
	                                        <!-- <th class="nk-tb-col tb-col-md"><span class="sub-text">Effect</span></th> -->
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Created At</span></th> 
	                                        <th class="nk-tb-col nk-tb-col-tools text-right"> </th>
	                                    </tr>
	                                </thead>
	                                <tbody>
 
	                                </tbody>
	                            </table>
	                        </div>
	                        
	                    </div><!-- .card-preview -->
	                    <div class="modal fade" id="modal_edit">
						    <div class="modal-dialog modal-dialog-bottom modal-lg">
						        <div class="modal-content">
						        	<div class="modal-header">
					                    <h5 class="modal-title" id="mdl_title">Edit {{ $page->title }}</h5>
					                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
					                        <em class="icon ni ni-cross"></em>
					                    </a>
					                </div>
						         
						            <div class="modal-body">
						            	<div class="row">
							            	<div class="col-md-6 " style="display:none">
								                <div class="form-group mb-1">
								                    <label class="form-label" for="id">id</label> 
									                   <input name="id" id="id" type="text" maxlength="4" value="" class="form-control form-control-sm" placeholder="id" > 
								                </div> 
							                </div>
							                <div class="col-md-8"> 
								                <div class="form-group mb-1">
								                    <label class="form-label" for="name">Name</label>
								                    <input name="name" id="name" type="text"  value="" class="form-control form-control-sm" placeholder="Name">
								                </div>
								                
							                </div>
						                </div>
						                <div class="row">
						                	<div class="col-md-12">
						                		<div class="form-group mb-1">
								                    <label class="form-label" for="erfull">Reduction</label>
								                    <textarea name="reduction" id="reduction" type="text" value="" class="form-control form-control-sm" placeholder="" style="color: #839496;background-color:#002b36;text-shadow: #002b36 0 1px;"></textarea>
								                </div>

								                <div class="form-group mb-1">
								                    <label class="form-label" for="description">Description</label>
								                    <textarea name="description" id="description" type="text" value="" class="form-control form-control-sm" placeholder=""></textarea>
								                </div>
						                	</div>
						                </div>
						                <div class="row">
						                	<div class="col-md-8">
						                		<div class="form-group mb-1">
								                    <label class="form-label" for="fcode">Fcode</label>
								                    <input name="fcode" id="fcode" type="text" value="" class="form-control form-control-sm" placeholder="">
								                </div>
						                	</div>
						                	<div class="col-md-4">
								                 <div class="form-group">
								                    <label class="form-label" for="reductionmin">ReductionMin</label>
								                    <input name="reductionmin" id="reductionmin" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>  
						                	</div> 
						                </div>
						                <div class="row">
						                	<div class="col-md-4">
						                		<div class="form-group">
								                    <label class="form-label" for="reductionmax">ReductionMax</label>
								                    <input name="reductionmax" id="reductionmax" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
						                	</div>
						                	<div class="col-md-8"> 
						                		<div class="form-group" x-data="page()">
								                    <label class="form-label" for="effect">Effect</label>
								                    <select x-model="selectedOption" class="form-control form-control-sm" id="effect" name="effect">
								                    	<option value="">Choose Here</option>
								                    	<template x-for="option in options">
								                    		<option :key="option.value" :value="option.value" x-text="option.text"></option>
								                    	</template>
								                    </select>
								                </div>
						                	</div>
						                </div> 
						            </div>
						            <div class="modal-footer bg-light">
						                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
						                <button id="edit_action" type="button" class="btn btn-sm btn-primary">Save</button>
						            </div>
						        </div>
						    </div>
						</div>
						<div class="modal modal-danger fade" id="modal_delete">
						    <div class="modal-dialog">
						        <div class="modal-content">
						            <div class="modal-header">
						                <h5 class="modal-title">Delete Acft</h5>
					                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
					                        <em class="icon ni ni-cross"></em>
					                    </a>
						            </div>
						            <div class="modal-body">
						                <p>Are You sure You want to delete this Acft?</p>
						            </div>
						            <div class="modal-footer">
						                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
						                <button id="delete_action" type="button" class="btn btn-outline">Submit</button>
						            </div>
						        </div>
						    </div>
						</div>
                	</div>
            	</div> 
    		</div>
	    </div>
	</div>
</div>
@endsection
@section('footer_scripts') 
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> 
<!-- <script type="text/javascript" src="{{ url('js/alpine.min.js') }}"></script> -->
<script type="text/javascript"> 
var TableDepFeat ; 
function page() {
  	return {
    	selectedOption: "",
    	options:[ 	
    			{ value: "Individual", text: "Individual" },
      		 	{ value: "Partial", text: "Partial" },
      		 	{ value: "Total", text: "Total" }
    		], 
  	};
} 

function add_action(){
    var modal = $('#modal_edit');
	modal.find('.modal-title').text('Add New');
	modal.modal();
}
function delete_action(_id){
    $('#id').val(_id);
}
function edit_action(this_el, item_id){
	$('#id').val(item_id);
	var tr_el = this_el.closest('tr');
	var row = TableDepFeat.row(tr_el);
	var row_data = row.data();
	$('#name').val(row_data.name);
	$('#reduction').val(row_data.reduction);
	$('#description').val(row_data.description);
	$('#fcode').val(row_data.fcode);
	$('#reductionmin').val(row_data.reductionmin);
	$('#reductionmax').val(row_data.reductionmax);
	$('#effect').val(row_data.effect);
}
      // DataTable
function initDataTable() {
    TableDepFeat = $('#dep_feat_table').DataTable({
         processing: true,
         serverSide: true,
         language: { 
         	search: '', 
         	searchPlaceholder: "Search..."
		 },
		 lengthMenu: [ 10, 25, 50, 75, 100 ],
         ajax: "{{ route('depfeat.getDepFeat') }}",
         columns: [
            { data: 'id' }, //0
            { data: 'name' }, //1
            
            
            { data: 'fcode' }, //4
            { data: 'reductionmin' }, //5
            { data: 'reductionmax' }, //6
            
            { data: 'created_at' }, //8
            { data: 'tooli'}, //10 
            { data: 'updated_at' },  //9
            { data: 'description' }, //3
            { data: 'reduction' }, //2
            { data: 'effect' }, //7
			
         ],
          "columnDefs": [
          	{ className: "nk-tb-col tb-col-md", "targets": [ 0 ] },
		    { className: "nk-tb-col tb-col-md", "targets": [ 1 ] },
		    
		    { className: "nk-tb-col tb-col-md", "targets": [ 2 ] },
		    { className: "nk-tb-col tb-col-md", "targets": [ 3 ] },
		    { className: "nk-tb-col tb-col-md", "targets": [ 4 ] },
		      
		    { className: "nk-tb-col tb-col-md", "targets": [ 5 ] }, 
		    
		    {
                'targets': 6,
                'defaultContent': '-',
                'searchable': false,
                'orderable': false,
                'width': '10%',
                'className': 'nk-tb-col nk-tb-col-tools',
                'render': function (data, type, full_row, meta){
                    return '<ul class="nk-tb-actions gx-1">' +
                        '<li class="nk-tb-action"><button onclick="delete_action(\'' + full_row.id + '\')" type="button"  class="btn btn-trigger btn-icon" data-toggle="modal" data-target="#modal_delete" style="margin:3px"><em class="icon ni ni-trash-fill"></em></button></li>' +
                        '<li class="nk-tb-action"><button onclick="edit_action(this, \'' + full_row.id + '\')" type="button"  class="btn btn-trigger btn-icon" data-toggle="modal" data-target="#modal_edit" style="margin:3px"><em class="icon ni ni-edit-fill"></em></button></li>' +
                        '</ul>';
                }

            },
            { "targets": [ 7 ], "visible": false, "searchable": false },
            { "targets": [ 8 ], "visible": false, "searchable": false },
            { "targets": [ 9 ], "visible": false, "searchable": false },
            { "targets": [ 10 ], "visible": false, "searchable": false }
		  ],
		   
           "dom": '<"row justify-between g-2" <"col-7 col-sm-6 text-left" f><"col-5 col-sm-6 text-right"l> > <"datatable-wrap my-3"t> <"row align-items-center" <"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"i>><"clear">',
           
      });
       
    return TableDepFeat;
}
$(document).ready(function(){
	var DataTableDepFeat = initDataTable();
	$('#delete_action').on('click', function (e) {
        e.preventDefault();
        let id = $('#id').val();
        $.ajax({
            url: "{{ url('inavcec/depfeat/') }}/"+id,
            data: {
                '_token': "{{ csrf_token() }}"
            },
            type: "DELETE",
            success: function (data) {
                $('#modal_delete').modal('hide');
                TableDepFeat.ajax.reload(null, false); 
                NioApp.Toast('Deleting data Success.', 'success');
            }
        })
    });
    $('#edit_action').on('click', function (e) {
        e.preventDefault(); 
        let id 		= $('#id').val(); 
        let method 	= 'POST';
        let url  	= "{{ url('inavcec/depfeat/create') }}/";
        if(id.length > 0){
        	method 	= 'PATCH';
        	url 	=  "{{ url('inavcec/depfeat/') }}/"+id;
        }
        $.ajax({
            url: url,
            data: {
                'id': $('#id').val(),
                'name': $('#name').val(),
				'reduction':$('#reduction').val(),
				'description':$('#description').val(),
				'fcode':$('#fcode').val(),
				'reductionmin':$('#reductionmin').val(),
				'reductionmax':$('#reductionmax').val(),
				'effect':$('#effect').val(),  
                '_token': "{{ csrf_token() }}"
            },
            type: method,
            success: function (response) {
                $('#modal_edit').modal('hide');
                TableDepFeat.ajax.reload(null, false); 
        		NioApp.Toast('Saving data Success.', 'success');
            },
            error:  function (response) {
            	let err = response.responseJSON.error;
            	let errMsg = ''; 
            	$.each( err, function( key, value ) {
				  errMsg += '<span>'+ key +' : '+ value +'</span><br>';
				});
				
        		NioApp.Toast(errMsg, 'error');	
            }
        })
    });
    $('#modal_delete').on('hidden.bs.modal', function () {
        $('#id').val(0);
    });
    $('#modal_edit').on('hidden.bs.modal', function () {
        $('#id').val('');
        $('#name').val('');
        $('#reduction').val('');
		$('#description').val('');
		$('#fcode').val('');
		$('#reductionmin').val(0);
		$('#reductionmax').val(0);
		$('#effect').val(''); 

		var modal = $('#modal_edit');
		modal.find('.modal-title').text('Edit {{ $page->title }}');
    });
       
});

</script>
@endsection