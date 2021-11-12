@extends('layouts.app')

@section('template_title')
    INAVCEC/AIRPORT
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
	                            <table class="table nk-tb-list nk-tb-ulist" data-auto-responsive="false" id="airport_table">
	                                <thead>
	                                    <tr class="nk-tb-item nk-tb-head"> 
	                                        <th class="nk-tb-col"><span class="sub-text">Icao</span></th> 
	                                        <th class="nk-tb-col"><span class="sub-text">TaxiOut</span></th>
	                                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">GndHolding</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">ArrHolding</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Approach</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">TaxiIn</span></th> 
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Location</span></th>
	                                        <!-- <th class="nk-tb-col tb-col-md"><span class="sub-text">DepFeatures</span></th> -->
	                                        <!-- <th class="nk-tb-col tb-col-md"><span class="sub-text">ArrFeatures</span></th>  -->
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
					                    <h5 class="modal-title" id="mdl_title">Edit Airport</h5>
					                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
					                        <em class="icon ni ni-cross"></em>
					                    </a>
					                </div>
						         
						            <div class="modal-body">
						            	<div class="row">
							            	<div class="col-md-8 ">
								                <div class="form-group mb-1">
								                    <label class="form-label" for="icao">Icao</label> 
									                   <input name="icao" id="icao" type="text" maxlength="4" value="" class="form-control form-control-sm" placeholder="Icao Airport" > 
								                </div> 
							                </div>
							                <div class="col-md-4"> 
								                <div class="form-group mb-1">
								                    <label class="form-label" for="taxiout">TaxiOut</label>
								                    <input name="taxiout" id="taxiout" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div> 
							                </div>
						                </div>
						                <div class="row">
						                	<div class="col-md-4">
						                		<div class="form-group mb-1">
								                    <label class="form-label" for="gndholding">GndHolding</label>
								                    <input name="gndholding" id="gndholding" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
						                	</div>
						                	<div class="col-md-4">
						                		<div class="form-group mb-1">
								                    <label class="form-label" for="arrholding">ArrHolding</label>
								                    <input name="arrholding" id="arrholding" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
						                	</div>
						                	<div class="col-md-4">
						                		<div class="form-group mb-1">
								                    <label class="form-label" for="approach">Approach</label>
								                    <input name="approach" id="approach" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
						                	</div>
						                </div>
						                <div class="row">
						                	<div class="col-md-4">
						                		<div class="form-group mb-1">
								                    <label class="form-label" for="taxiin">TaxiIn</label>
								                    <input name="taxiin" id="taxiin" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div> 
						                	</div>
						                	<div class="col-md-8">
						                		<div class="form-group mb-1">
								                    <label class="form-label" for="location">Location</label>
								                    <textarea name="location" id="location" type="text" value="" class="form-control form-control-sm" placeholder="" style="color: #839496;background-color:#002b36;text-shadow: #002b36 0 1px;"></textarea>
								                </div>
						                	</div>
						                </div>
						                <div class="row">
						                	<div class="col-md-6">
						                		<div class="form-group mb-1">
                                                    <label class="form-label">Dep Features</label>
                                                    <div class="form-control-wrap" x-data="depFeature()">
                                                        <select x-model="selectedOption" multiple="multiple" class="form-select form-control form-control-sm" id="dep_features" data-placeholder="Select Features">
                                                            <template x-for="(option,idx) in options">
									                    		<option :key="option.id" :value="option.id" x-text="option.name"></option>
									                    	</template>

                                                        </select>
                                                    </div>
                                                </div>
						                	</div>
						                
							               	<div class="col-md-6">
							               		<div class="form-group mb-1">
                                                    <label class="form-label">Arr Features</label>
                                                     <div class="form-control-wrap" x-data="arrFeature()">
                                                        <select x-model="selectedOption" multiple="multiple" class="form-select form-control form-control-sm" id="arr_features" data-placeholder="Select Features">
                                                            <template x-for="(option,idx) in options">
									                    		<option :key="option.id" :value="option.id" x-text="option.name"></option>
									                    	</template>

                                                        </select>
                                                    </div>
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
						                <h5 class="modal-title">Delete Airport</h5>
					                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
					                        <em class="icon ni ni-cross"></em>
					                    </a>
						            </div>
						            <div class="modal-body">
						                <p>Are You sure You want to delete this Airport?</p>
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
<!-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>  -->
 <script type="text/javascript" src="{{ url('js/alpine.min.js') }}"></script>
<script type="text/javascript"> 

var TableAirport ; 
 
function depFeature(){
	let data = @json($depFeat); 
	return {
    	selectedOption: '',
    	options: data, 
  	};
}
function arrFeature(){
	let data = @json($arrFeat); 
	return {
    	selectedOption: '',
    	options: data, 
  	};
}
function add_action(){
    var modal = $('#modal_edit');
	modal.find('.modal-title').text('Add New');
	modal.modal();
}
function delete_action(_id){
    $('#icao').val(_id);
}
function edit_action(this_el, item_id){
	$('#icao').val(item_id);
	var tr_el = this_el.closest('tr');
	var row = TableAirport.row(tr_el);
	var row_data = row.data();
	$('#icao').val(row_data.icao);
	$('#taxiout').val(row_data.taxiout);
	$('#gndholding').val(row_data.gndholding);
	$('#arrholding').val(row_data.arrholding);
	$('#approach').val(row_data.approach);
	$('#taxiin').val(row_data.taxiin);
	$('#location').val(row_data.location); 
	// update selected features
 	$('#dep_features').val(eval(row_data.dep_features));
    $('#dep_features').select2().trigger('change');
 	
 	$('#arr_features').val(eval(row_data.arr_features));
    $('#arr_features').select2().trigger('change');
}
      // DataTable
function initDataTable() {
    TableAirport = $('#airport_table').DataTable({
         processing: true,
         serverSide: true,
         language: { 
         	search: '', 
         	searchPlaceholder: "Search..."
		 },
		 lengthMenu: [ 10, 25, 50, 75, 100 ],
         ajax: "{{ route('airport.getAirport') }}",
         columns: [
            { data: 'icao' }, //0
            { data: 'taxiout' }, //1
            { data: 'gndholding' }, //2
            { data: 'arrholding' }, //3
            { data: 'approach' }, //4
            { data: 'taxiin' },  //5
            { data: 'location' },  //6
            { data: 'tooli'}, //7
            { data: 'dep_features' }, //8
            { data: 'arr_features' }, //9 
			
         ],
          "columnDefs": [
          	{ className: "nk-tb-col tb-col-md", "targets": [ 0 ] },
		    { className: "nk-tb-col tb-col-md", "targets": [ 1 ] },
		    { className: "nk-tb-col tb-col-md", "targets": [ 2 ] },
		    { className: "nk-tb-col tb-col-md", "targets": [ 3 ] },
		    { className: "nk-tb-col tb-col-md", "targets": [ 4 ] },
		    { className: "nk-tb-col tb-col-md", "targets": [ 5 ] }, 
		    { className: "nk-tb-col tb-col-md", "targets": [ 6 ] }, 
		    {
                'targets': 7,
                'defaultContent': '-',
                'searchable': false,
                'orderable': false,
                'width': '10%',
                'className': 'nk-tb-col nk-tb-col-tools',
                'render': function (data, type, full_row, meta){
                    return '<ul class="nk-tb-actions gx-1">' +
                        '<li class="nk-tb-action"><button onclick="delete_action(\'' + full_row.icao + '\')" type="button"  class="btn btn-trigger btn-icon" data-toggle="modal" data-target="#modal_delete" style="margin:3px"><em class="icon ni ni-trash-fill"></em></button></li>' +
                        '<li class="nk-tb-action"><button onclick="edit_action(this, \'' + full_row.icao + '\')" type="button"  class="btn btn-trigger btn-icon" data-toggle="modal" data-target="#modal_edit" style="margin:3px"><em class="icon ni ni-edit-fill"></em></button></li>' +
                        '</ul>';
                }

            },
            { "targets": [ 8 ], "visible": false, "searchable": false },
            { "targets": [ 9 ], "visible": false, "searchable": false },
            
		  ],
		   
           "dom": '<"row justify-between g-2" <"col-7 col-sm-6 text-left" f><"col-5 col-sm-6 text-right"l> > <"datatable-wrap my-3"t> <"row align-items-center" <"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"i>><"clear">',
           
      });
       
    return TableAirport;
}


$(document).ready(function(){
	var DataTableAirport = initDataTable();
	$('#delete_action').on('click', function (e) {
        e.preventDefault();
        let id = $('#icao').val();
        $.ajax({
            url: "{{ url('inavcec/airport/') }}/"+id,
            data: {
                '_token': "{{ csrf_token() }}"
            },
            type: "DELETE",
            success: function (data) {
                $('#modal_delete').modal('hide');
                TableAirport.ajax.reload(null, false); 
                NioApp.Toast('Deleting data Success.', 'success');
            }
        })
    });
    $('#edit_action').on('click', function (e) {
        e.preventDefault(); 
        // autoincrement id default to patch
        let id 		= $('#icao').val(); 
        // let method 	= 'POST';
        // let url  	= "{{ url('inavcec/airport/create') }}/";
        // if(id.length > 0){
        	method 	= 'PATCH';
        	url 	=  "{{ url('inavcec/airport/') }}/"+id;
        // }
        $.ajax({
            url: url,
            data: {
                'icao': $('#icao').val(),
                'taxiout': $('#taxiout').val(),
				'gndholding':$('#gndholding').val(),
				'arrholding':$('#arrholding').val(),
				'approach':$('#approach').val(),
				'taxiin':$('#taxiin').val(),
				'location':$('#location').val(),
				'dep_features':$('#dep_features').val(),  
				'arr_features':$('#arr_features').val(),
                '_token': "{{ csrf_token() }}"
            },
            type: method,
            success: function (response) {
                $('#modal_edit').modal('hide');
                TableAirport.ajax.reload(null, false); 
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
        $('#icao').val(0);
    });
    $('#modal_edit').on('hidden.bs.modal', function () {
    	$('#icao').val('');
    	$('#taxiout').val(0);
    	$('#gndholding' ).val(0);
    	$('#arrholding').val(0);
    	$('#approach').val(0);
    	$('#taxiin').val(0);
    	$('#location').val('');
    	$('#dep_features').val([]);
    	$('#dep_features').select2().trigger('change');
 	
 		$('#arr_features').val([]);
    	$('#arr_features').select2().trigger('change');

    	var modal = $('#modal_edit');
		modal.find('.modal-title').text('Edit {{ $page->title }}');
    });
      
    
});


</script>
@endsection