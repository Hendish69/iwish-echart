@extends('layouts.app')

@section('template_title')
    INAVCEC/PREDICTION TOOL
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
	</style>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
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
                                   <!--  <li>
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
                                    <!-- <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span></a></li> -->
                                </ul>
                            </div>
                        </div>
                    </div><!-- .nk-block-head-content -->
                </div><!-- .nk-block-between -->
            </div><!-- .nk-block-head --> 
        	<div class="nk-block">    
            	<div class="row g-gs" style="min-height: 100%;">
		        	<div class="col-xxl-5 col-md-12 col-lg-5">
			            <div class="card h-100">
			            	<div class="card-header text-white bg-primary"><h6>FPL Data</h6></div>
			                <div class="card-inner">
			                     <form action="" method="POST" id="planningForm">
			                     	@csrf
			                     	<div class="row">
			                     		<div class="col-6">
	                                    	<div class="mb-3 row">
		                                        <label class="col-sm-5 form-label" for="departure">Departure</label>
		                                        <div class="col-sm-7"> 
	                                        		<div class="form-control-wrap"  x-data="{{ $airports->content() }}" >
													  <select x-model="data" class="form-select form-control form-control-lg airportlist" data-search="on" name="departure">
													    <option value="" selected="selected"></option>
													    <template x-for='airport in data'>
													      <option :value="airport.value" x-text="airport.label"></option>
													    </template>
													  </select>   
                                                    </div> 
		                                        </div>
		                                    </div>
		                                   
		                                    <div class="mb-3 row">
		                                        <label class="col-sm-5 form-label" for="aircraft">Aircraft</label>
		                                        <div class="col-sm-7">
		                                        	<div class="form-control-wrap" x-data="{{ $aircrafts->content() }}">
													  <select x-model="data" class="form-select form-control form-control-lg" data-search="on" name="aircraft">
													    <option value="" selected="selected"></option>
													    <template x-for="aircraft in data">
													      <option :value="aircraft.value" x-text="aircraft.label"></option>
													    </template>
													  </select>  
													  
                                                    </div>
		                                            <!-- <input type="text" class="form-control" name="aircraft"> -->
		                                        </div>
		                                    </div>	
	                                    </div>
	                                    <div class="col-6">
	                                    	<div class="mb-3 row">
		                                        <label class="col-sm-5 form-label" for="destiny">Destination</label>
		                                        <div class="col-sm-7">
		                                            <div class="form-control-wrap" x-data="{{ $airports->content() }}">
													  <select x-model="data" class="form-select form-control form-control-lg airportlist" data-search="on" name="destination">
													  	<option value="" selected="selected"></option>
													    <template x-for="airport in data">
													      <option :value="airport.value" x-text="airport.label"></option>
													    </template>
													  </select>  
													  
                                                    </div>
		                                        </div>
		                                    </div>
		                                   
		                                    <div class="mb-3 row">
		                                        <label class="col-sm-5 form-label" for="cruise_speed">Cruise Speed(Kts)</label>
		                                        <div class="col-sm-7">
		                                            <input type="number" class="form-control text-right" name="cruise_speed" type="number" step="0.01" value="0" id="cruise_speed">
		                                        </div>
		                                    </div>	
	                                    </div> 
			                     	</div>
			                     	<div class="row">
			                     		<div class="col-6">
				                     		<div class="mb-3 row">
				                     			<label class="col-sm-3 form-label" for="atd_date">ATD</label>
		                                        <div class="col-sm-9 input-group">
                                                    <input type="text"  autocomplete="off" class="form-control date-picker" data-date-format="yyyy-mm-dd" id="atd_date">
                                                	<input type="text" name="atd_time" id="atd_time" class="form-control timepicker">
                                                </div>
		                                    </div>
				                     	</div>
				                     	<div class="col-6">
				                     		<div class="mb-3 row">
				                     			<label class="col-sm-5 form-label" for="cruise_fl">Cruise FL</label>
		                                        <div class="col-sm-7">
                                                    <input type="number" name="cruise_fl" maxlength="3" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" class="form-control text-right" id="cruise_fl" value="0">
                                                </div>
		                                    </div>
				                     	</div>
				                     </div>
			                     	<div class="form-group pt-2 text-center">
                                        <button  id="planning_btn" class="btn btn-md btn-primary btn-wider" ><span>Show Routes</span><em class="icon ni ni-arrow-right"></em></button>
                                    </div>
                                </form>
                                	<!-- <div class="form-group pt-2 text-center">
                                        <button type="button" id="planning_btn" class="btn btn-sm btn-primary" onclick="drawPoly(flightPlanCoordinates)">Planning</button>
                                    </div> -->
			                    
			                </div><!-- .card-inner -->
			                <div class="nk-tb-list mt-n2 p-2"> 
			                    <div class="row pt-2" style="display:none;" id="pre_table"> 
									<div class="col">
										<table class="table table-hover">
									  <thead>
									   <tr class="text-center">
									     <th scope="col">#</th> <th scope="col">Route</th><th scope="col">Distance(Nm)</th><!-- <th scope="col">Action</th> -->
									    </tr>
									  </thead>
									  <tbody id="pre_table_body"> 
									     
									  </tbody>
									   
									</table>
									</div>
								</div>
		                    </div> 
			            </div><!-- .card -->
			        </div><!-- .col -->
		        	<div class="col-xxl-7  col-md-12 col-lg-7" style="min-height:400px!important">
		                <div class="card card-full" style="display: none;" id="map_box"> 
                			<div class="card-body">

								<iframe id="myMap" name="myMap" frameborder="0" width="100%" height="100%"></iframe>
                				<!-- <div  id="map" class="h-100"></div> -->
                				<!-- <div id="info-pane" class="leaflet-bar">Hover to inspect</div> -->
                			</div>    
		                </div><!-- .card -->
		            </div>
            	</div>
            	<div class="row g-gs" style="display:none;" id="em_table"> 
					<div class="col">
						<div class="card card-full">
							<div class="card-header bg-light">Emissions generated</div>
							 
                			<div class="card-body">
								<table class="table table-hover">
								  	<thead>
								   	<tr class="text-center">
								     	<th scope="col">EmStart</th>
										<th scope="col">EmTaxiOut</th>
										<th scope="col">EmGndHolding</th>
										<th scope="col">EmTakeOff</th>
										<th scope="col">EmClimb</th>
										<th scope="col">EmCruise</th>
										<th scope="col">EmDescend</th>
										<th scope="col">EmHolding</th>
										<th scope="col">EmApproach</th>
										<th scope="col">EmLanding</th>
										<th scope="col">EmTaxiIn</th>
										<th scope="col"><span class="text-info">EmTotal</span></th>
								    </tr>
								  	</thead>
								  	<tbody id="em_table_body"> 
								     
								  	</tbody>							   
								</table>
								 <div class="row border mt-2" style="margin:1px">
                                    <div class="col-md-6 text-left">
                                        <p style="color: green;" class="">
                                            <span>*Emissions in Kilograms</span><br>
                                            <span>*Date and time in UTC</span>
                                        </p>        
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <p style="color: green;" class="">
                                            <span>*Carbon emission is calculated based on the aircraft's engine emission rate<br> and flight time of each flight segments.</span> 
                                        </p>
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
<!-- <script src="{{ asset ('/js/geolib.min.js') }}" type="text/javascript"></script> -->
<script src="{{ asset ('/images/marker/geolib.js') }}" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var atd_pic = $("#atd_date").datepicker( {
	    format: "yyyy-mm-dd",
	    todayHighlight: true,
        autoclose: true
	});
	var date = new Date();
	var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
	$( '#atd_date, #ata_date' ).datepicker( 'setDate', today );
	var atd_time= $('#atd_time').timepicker({
	    timeFormat: 'HH:mm:ss',
	    interval: 10,
	    minTime: '00:01',
	    maxTime: '23:59',
	    defaultTime: 'now',
	    startTime: '10:00',
	    dynamic: true,
	    dropdown: true,
	    scrollbar: false
	});
 
});
function objectifyForm(formArray) {
    //serialize data function
    var returnArray = {};
    for (var i = 0; i < formArray.length; i++){
        returnArray[formArray[i]['name']] = formArray[i]['value'];
    }
    return returnArray;
} 
$(function() { 
	// disable when other is choice
	var previousOption = null;
	$('.airportlist').on('change', function(){
		const selectedOption = $(this).find('option:selected').val();
		$(`[value="${previousOption}"]:disabled`).attr('disabled', false);
		previousOption = null;
		$(`[value="${selectedOption}"]:not(:selected)`).attr('disabled', true);
	})
	$('.airportlist').on('click', function(){
		previousOption = $(this).find('option:selected').val();
	});

	$('#planningForm').on('submit', function(e) {
		var formdata = $(this).serialize();
		var aircraft = $('select[name="aircraft"]').val() 
		var depart = $('select[name="departure"]').val();
		
		var destiny = $('select[name="destination"]').val(); 
	 
	    e.preventDefault();
	    $.ajax({
	        type: "POST",
	        url: "{{ route('predictool.getRoute') }}",
	        data: formdata,
	        success: function(response) {
	        	if(response.status=='success'){
	        		$('#pre_table_body').empty();
	        		let routes = response.msg.routes;
	        		if(routes.length > 0){ 
		        		$.each(routes, function(i, item) {
		        		// console.log('item : '+Object.keys(item).length); 
			        		if(Object.keys(item).length > 0){ 
						        var $tr = $('<tr>').append(
						        	$('<td scope="row" style="vertical-align:middle" class="text-center">').text(i+1),
						        	$('<td>').html('<button class="btn btn-outline-primary btn_route" data-route="/map.php?table=_fpl&acft='+aircraft+'&adep='+depart+'&ades='+destiny+'&route='+item.nroute+'&id='+i+'" data-id="'+i+'"><span>'+item.nroute+'</span></button>'),
						        	$('<td>').html('<div class="form-control-wrap"><input type="number" class="form-control text-right" id="route_dist'+i+'" readonly></div>')
						        	// $('<td>').html('<button class="btn btn-outline-primary text-right"onclick="calcEmission('+i+')"><span>Calc</span></button>'),
						        ).appendTo('#pre_table_body');
						    } 
					    });
				    }else{
				    	var $tr = $('<tr>').append(
					        	$('<td colspan=3  class="text-center">').html('<span>No route found</span>')
					          ).appendTo('#pre_table_body');
				    }
				    $('#pre_table').show();

	        	}else{
	        		console.log(response.status);
	        	} 
	        	
	        },
	        error: function() {
	            alert('Error');
	        }
	    });
	    return false;
	});
}); 
$('#pre_table_body').bind('click', function(e) {
	$('#pre_table_body tr').children('td,th').css('background-color','unset');
    $(e.target).closest('tr').children('td,th').css('background-color','#e6fcf6');
});
$('#pre_table_body').on('click', 'button', function () {
    let url 	= $( this ).attr('data-route');
    let row_id  = $( this ).attr('data-id');
	// console.log('row_id : '+row_id);
	$('#em_table_body').empty();
	 var showMap =  new Promise((resolve, reject) => {
			        	loadMap(url);   
			        	setTimeout(() => { 	
			            	resolve(row_id);
			            }, 3000);
			        });
	    showMap.then(val =>{
	    	var acft = $('select[name="aircraft"]').val() 
			var depart = $('select[name="departure"]').val();
			var destiny = $('select[name="destination"]').val();
			var cruise_spd = $('#cruise_speed').val();
			var cruise_fl  = $('#cruise_fl').val(); 
			var atd = $('#atd_date').val() +' '+ $('#atd_time').val();
			var dist = $('#route_dist'+val).val(); 
		    $.post("{{ route('predictool.calcEmission') }}",{
			    _token: "{{ csrf_token() }}",
			    acft: acft,
			    adep: depart,
			    ades: destiny,
			    cruise_spd : cruise_spd,
			    cruise_fl : cruise_fl,
			    atd : atd,
			    distance : dist
			  },
			  function(data, status){
			    if(status=='success'){
	        		$('#em_table_body').empty();
	        		let item =  eval(JSON.parse(data)); 

	        		if(Object.keys(item).length > 0){
				        var $tr = $('<tr class="text-center">').append(
				        	$('<td>').text(item.emissionstart.toFixed(2)),
							$('<td>').text(item.emissiontaxiout.toFixed(2)),
							$('<td>').text(item.emissiongndholding.toFixed(2)),
							$('<td>').text(item.emissiontakeoff.toFixed(2)),
							$('<td>').text(item.emissionclimb.toFixed(2)),
							$('<td>').text(item.emissioncruise.toFixed(2)),
							$('<td>').text(item.emissiondescend.toFixed(2)),
							$('<td>').text(item.emissionholding.toFixed(2)),
							$('<td>').text(item.emissionapproach.toFixed(2)),
							$('<td>').text(item.emissionlanding.toFixed(2)),
							$('<td>').text(item.emissiontaxiin.toFixed(2)),
							$('<td>').html('<span class="badge badge-md badge-info">'+item.emissiontotal.toFixed(2)+'</span>')
				        ).appendTo('#em_table_body');
	        		}
	        		$('#em_table').show();  
	        	}
	    });
	});
});  

function loadMap(url){
	$('#map_box').show();  
	$('#myMap').attr('src', url); 
}
function calcEmission(id){
	console.log('id :'+id);
}
//listener callback from iframe
window.addEventListener('message', function(e){
	const data= e.data;
	const decode = JSON.parse(data); 
	$('#route_dist'+decode.id).val(decode.data); 
});

</script>
@endsection