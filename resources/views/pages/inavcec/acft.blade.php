@extends('layouts.app')

@section('template_title')
    INAVCEC/ACFT
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
<?php
// dd($airports->content());
?>
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
                                  <!--   <li>
                                        <div class="drodown">
                                            <a href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light" data-toggle="dropdown"><em class="d-none d-sm-inline icon ni ni-calender-date"></em><span><span class="d-none d-md-inline">Last</span> 30 Days</span><em class="dd-indc icon ni ni-chevron-right"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <ul class="link-list-opt no-bdr">
                                                    <li><a href="#"><span>Last 30 Days</span></a></li>
                                                    <li><a href="#"><span>Last 6 Months</span></a></li>
                                                    <li><a href="#"><span>Last 1 Years</span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li> -->
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
	                            <table class="table nk-tb-list nk-tb-ulist" data-auto-responsive="false" id="acftTable">
	                                <thead>
	                                    <tr class="nk-tb-item nk-tb-head"> 
	                                        <th class="nk-tb-col"><span class="sub-text">Icao</span></th>
	                                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">ErIdle</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">ErFull</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">ErTaxi</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">ErClimb</span></th> 
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">ErDescend</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">ErHolding</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">ErCruise</span></th> 
	                                        <th class="nk-tb-col nk-tb-col-tools text-right"></th>
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
							            	<div class="col-md-6">
								                <div class="form-group mb-1">
								                    <label class="form-label" for="icao">Icao</label> 
									                   <input name="icao" id="icao" type="text" maxlength="4" value="" class="form-control form-control-sm" placeholder="Aircraft Icao" required=""> 
								                </div> 
							                </div>
							                <div class="col-md-6"> 
								                  <div class="form-group mb-1">
								                    <label class="form-label" for="eridle">ErIdle</label>
								                    <input name="eridle" id="eridle" type="number" step="0.01"  value="" class="form-control form-control-sm" placeholder="">
								                </div>
							                </div>
						                </div>
						                <div class="row">
						                	<div class="col-md-4">
						                		 <div class="form-group mb-1">
								                    <label class="form-label" for="erfull">ErFull</label>
								                    <input name="erfull" id="erfull" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
								                 <div class="form-group mb-1">
								                    <label class="form-label" for="erdescend">ErDescend</label>
								                    <input name="erdescend" id="erdescend" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
						                	</div>
						                	<div class="col-md-4">
						                		<div class="form-group mb-1">
								                    <label class="form-label" for="ertaxi">ErTaxi</label>
								                    <input name="ertaxi" id="ertaxi" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
								                 <div class="form-group">
								                    <label class="form-label" for="erholding">ErHolding</label>
								                    <input name="erholding" id="erholding" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
						                	</div>
						                	<div class="col-md-4">
						                		 <div class="form-group mb-1">
								                    <label class="form-label" for="ertaxi">ErClimb</label>
								                    <input name="erclimb" id="erclimb" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
								                 <div class="form-group mb-1">
								                    <label class="form-label" for="ercruise">ErCruise</label>
								                    <input name="ercruise" id="ercruise" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
						                	</div>
						                </div>
						                <div class="row">
						                	<div class="col-md-12">
						                		<div class="form-group mb-1">
								                    <label class="form-label" for="description">Description</label>
								                    <textarea name="description" id="description" type="text" value="" class="form-control form-control-sm" ></textarea>
								                </div>
						                	</div>
						                </div>
						                <div class="row">
						                	<div class="col-md-4">
						                		<div class="form-group mb-1">
								                    <label class="form-label" for="erlanding">ErLanding</label>
								                    <input name="erlanding" id="erlanding" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
								                <div class="form-group mb-1">
								                    <label class="form-label" for="tlanding">TLanding</label>
								                    <input name="tlanding" id="tlanding" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
								                <div class="form-group mb-1">
								                    <label class="form-label" for="tidle">TIdle</label>
								                    <input name="tidle" id="tidle" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
						                	</div>
						                	<div class="col-md-4">
						                		<div class="form-group mb-1">
								                    <label class="form-label" for="tstartup">TStartup</label>
								                    <input name="tstartup" id="tstartup" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
								                <div class="form-group mb-1">
								                    <label class="form-label" for="rateclimb">RateClimb</label>
								                    <input name="rateclimb" id="rateclimb" type="number" step="1" value="" class="form-control form-control-sm" placeholder="">
								                </div>
								                 <div class="form-group mb-1">
								                    <label class="form-label" for="erapch">ErApch</label>
								                    <input name="erapch" id="erapch" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
						                	</div>
						                	<div class="col-md-4">
						                		 <div class="form-group mb-1">
								                    <label class="form-label" for="ttakeoff">TTakeoff</label>
								                    <input name="ttakeoff" id="ttakeoff" type="number" step="0.01" value="" class="form-control form-control-sm" placeholder="">
								                </div>
								                 <div class="form-group mb-1">
								                    <label class="form-label" for="ratedescend">RateDescend</label>
								                    <input name="ratedescend" id="ratedescend" type="number" step="1" value="" class="form-control form-control-sm" placeholder="">
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
<script type="text/javascript">
var TableAcft ;
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
	var row = TableAcft.row(tr_el);
	var row_data = row.data();
	$('#eridle').val(row_data.eridle);
	$('#erfull').val(row_data.erfull);
	$('#ertaxi').val(row_data.ertaxi);
	$('#erclimb').val(row_data.erclimb);
	$('#erdescend').val(row_data.erdescend);
	$('#erholding').val(row_data.erholding);
	$('#ercruise').val(row_data.ercruise);
	$('#description').val(row_data.description);
	$('#erlanding').val(row_data.erlanding);
	$('#tstartup').val(row_data.tstartup);
	$('#ttakeoff').val(row_data.ttakeoff);
	$('#tlanding').val(row_data.tlanding);
	$('#rateclimb').val(row_data.rateclimb);
	$('#ratedescend').val(row_data.ratedescend);
	$('#tidle').val(row_data.tidle);
	$('#erapch').val(row_data.erapch);
}
      // DataTable
function initDataTable() {
    TableAcft = $('#acftTable').DataTable({
         processing: true,
         serverSide: true,
         language: { 
         	search: '', 
         	searchPlaceholder: "Search..."
		 },
		 lengthMenu: [ 10, 25, 50, 75, 100 ],
         ajax: "{{ route('acft.getAcft') }}",
         columns: [
            { data: 'icao' },
            { data: 'eridle' },
            { data: 'erfull' },
            { data: 'ertaxi' },
            { data: 'erclimb' },
            { data: 'erdescend' },
            { data: 'erholding' },
            { data: 'ercruise' },
            { data: 'tooli'},
            { data: 'description' },
			{ data: 'erlanding'},
			{ data: 'tstartup'},
			{ data: 'ttakeoff'},
			{ data: 'tlanding'},
			{ data: 'rateclimb'},
			{ data: 'ratedescend'},
			{ data: 'tidle'},
			{ data: 'erapch'}
            
         ],
          "columnDefs": [
		    { className: "nk-tb-col tb-col-md", "targets": [ 0 ] },
		    { className: "nk-tb-col tb-col-md", "targets": [ 1 ] },
		    { className: "nk-tb-col tb-col-md", "targets": [ 2 ] },
		    { className: "nk-tb-col tb-col-md", "targets": [ 3 ] },
		    { className: "nk-tb-col tb-col-md", "targets": [ 4 ] },
		    { className: "nk-tb-col tb-col-md", "targets": [ 5 ] },
		    { className: "nk-tb-col tb-col-md", "targets": [ 6 ] },
		    { className: "nk-tb-col tb-col-md", "targets": [ 7 ] }, 
		    {
                'targets': 8,
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
		    { "targets": [ 9 ], "visible": false, "searchable": false },
		    { "targets": [ 10 ], "visible": false, "searchable": false },
		    { "targets": [ 11 ], "visible": false, "searchable": false },
		    { "targets": [ 12 ], "visible": false, "searchable": false },
		    { "targets": [ 13 ], "visible": false, "searchable": false },
		    { "targets": [ 14 ], "visible": false, "searchable": false },
		    { "targets": [ 15 ], "visible": false, "searchable": false },
		    { "targets": [ 16 ], "visible": false, "searchable": false },
		    { "targets": [ 17 ], "visible": false, "searchable": false }
		    
		  ],
		   
           "dom": '<"row justify-between g-2" <"col-7 col-sm-6 text-left" f><"col-5 col-sm-6 text-right"l> > <"datatable-wrap my-3"t> <"row align-items-center" <"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"i>><"clear">',
           
      });
       
    return TableAcft;
}
$(document).ready(function(){
	var DataTableAcft = initDataTable();
	$('#delete_action').on('click', function (e) {
        e.preventDefault();
        let id = $('#icao').val();
        $.ajax({
            url: "{{ url('inavcec/acft/') }}/"+id,
            data: {
                '_token': "{{ csrf_token() }}"
            },
            type: "DELETE",
            success: function (data) {
                $('#modal_delete').modal('hide');
                TableAcft.ajax.reload(null, false); 
                NioApp.Toast('Deleting data Success.', 'success');
            }
        })
    });
    $('#edit_action').on('click', function (e) {
        e.preventDefault();
        let id = $('#icao').val();
        $.ajax({
            url: "{{ url('inavcec/acft/') }}/"+id,
            data: {
                'icao': $('#icao').val(),
                'eridle': $('#eridle').val(),
				'erfull':$('#erfull').val(),
				'ertaxi':$('#ertaxi').val(),
				'erclimb':$('#erclimb').val(),
				'erdescend':$('#erdescend').val(),
				'erholding':$('#erholding').val(),
				'ercruise':$('#ercruise').val(),
				'description':$('#description').val(),
				'erlanding':$('#erlanding').val(),
				'tstartup':$('#tstartup').val(),
				'ttakeoff':$('#ttakeoff').val(),
				'tlanding':$('#tlanding').val(),
				'rateclimb':$('#rateclimb').val(),
				'ratedescend':$('#ratedescend').val(),
				'tidle':$('#tidle').val(),
				'erapch':$('#erapch').val(),
                '_token': "{{ csrf_token() }}"
            },
            type: "PATCH",
            success: function (response) {
                $('#modal_edit').modal('hide');
                TableAcft.ajax.reload(null, false); 
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
        $('#eridle').val(0);
        $('#erfull').val(0);
		$('#ertaxi').val(0);
		$('#erclimb').val(0);
		$('#erdescend').val(0);
		$('#erholding').val(0);
		$('#ercruise').val(0);
		$('#description').val('');
		$('#erlanding').val(0);
		$('#tstartup').val(0);
		$('#ttakeoff').val(0);
		$('#tlanding').val(0);
		$('#rateclimb').val(0);
		$('#ratedescend').val(0);
		$('#tidle').val(0);
		$('#erapch').val(0);
		
		var modal = $('#modal_edit');
		modal.find('.modal-title').text('Edit {{ $page->title }}');
    }); 
});

</script>
@endsection