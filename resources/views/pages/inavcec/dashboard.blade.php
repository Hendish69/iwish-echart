@extends('layouts.app')

@section('template_title')
    INAVCEC/{{$page->title}}
@endsection

@section('head') 
  <style type="text/css">
  	 table.dataTable .table th, .table td {
		  max-width: 120px;
		  vertical-align: unset;
		}
		table.dataTable td  {
		  white-space: nowrap;
		  text-overflow: ellipsis;
		  overflow: hidden;
		}
		.nk-ecwg8-ck {
			overflow-y: auto!important;
		    overflow-x: hidden!important;
		}
  </style>
 
@endsection

@section('content') 
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
        	 <div class="nk-block ">    
            	<div class="row g-gs" style="min-height: 100%;">
            		<div class="col-md-4 col-xxl-4">
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-title-group align-start mb-2">
                                    <div class="card-title">
                                        <h6 class="title">Emission Total</h6>
                                        <p>Last week</p>
                                    </div>
                                    <div class="card-tools">
                                        <a href="/inavcec/report"><em class="card-hint icon ni ni-calendar-alt-fill" data-toggle="tooltip" data-placement="left" title="" data-original-title="View Detail"></em></a>
                                    </div>
                                </div>
                                 
                                <div class="nk-ecwg8-ck">
                                    <canvas id="myChart" style="height: 100%;"></canvas>
                                </div> 
                            </div>
                        </div>
                    </div>
	                <div class="col-md-4">
		        	  	<div class="card card-preview">
	                        <div class="card-inner">
	                        	<div class="card-title-group align-start mb-2">
                                    <div class="card-title">
                                        <h6 class="title">Emisi City Pair</h6>
                                        <p>Last week</p>
                                    </div>
                                    <div class="card-tools">
                                        <a href="/inavcec/reportcity"><em class="card-hint icon ni ni-block-over" data-toggle="tooltip" data-placement="left" title="" data-original-title="View Detail"></em></a>
                                    </div>
                                </div>
                                 
                                <div class="nk-ecwg8-ck">
                                    <table class="table nk-tb-list nk-tb-ulist" data-auto-responsive="true"  id="table_city">
                                    	 <thead>
	                                    <tr class="nk-tb-item nk-tb-head"> 
	                                        <th class="nk-tb-col" ><span class="sub-text">City Pair</span></th>	
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Amount (Kg)</span></th>
	                                    </tr>
	                                </thead>
	                                <tbody>
 											
	                                </tbody>
	                            </table>
                                </div> 
	                        </div>
	                    </div>
	                </div>
	                <div class="col-md-4">
		        	  	<div class="card card-preview">
	                          <div class="card-inner">
	                        	<div class="card-title-group align-start mb-2">
                                    <div class="card-title">
                                        <h6 class="title">Emisi Domestik & Internasional</h6>
                                        <p>Last week</p>
                                    </div>
                                    <div class="card-tools">
                                        <a href="/inavcec/reportdomin"><em class="card-hint icon ni ni-block-over" data-toggle="tooltip" data-placement="left" title="" data-original-title="View Detail"></em></a>
                                    </div>
                                </div>
                                 
                                <div class="nk-ecwg8-ck">
                                    <table class="table nk-tb-list nk-tb-ulist" data-auto-responsive="true"  id="edom_inter">
                                    	 <thead>
	                                    <tr class="nk-tb-item nk-tb-head"> 
	                                        <th class="nk-tb-col" ><span class="sub-text">Type of Flights</span></th>	
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Amount (Kg)</span></th>
	                                    </tr>
	                                </thead>
	                                <tbody>
 											
	                                </tbody>
	                            </table>
                                </div> 
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>

        	<div class="nk-block ">    
            	<div class="row g-gs" style="min-height: 100%;">
            		<div class="col-md-12">
		        	  	<div class="card card-preview">
	                        <div class="card-inner" style="overflow-y: auto; width:100%">
	                        	<div class="card-title-group mb-3">
                                    <div class="card-title">
                                        <h6 class="title">Latest Data</h6>
                                    </div>
                                    <div class="card-tools">
                                       <!--  <div class="dropdown">
                                            <a href="#" class="dropdown-toggle link link-light link-sm dropdown-indicator" data-toggle="dropdown">Weekly</a>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                <ul class="link-list-opt no-bdr">
                                                    <li><a href="#"><span>Daily</span></a></li>
                                                    <li><a href="#" class="active"><span>Weekly</span></a></li>
                                                    <li><a href="#"><span>Monthly</span></a></li>
                                                </ul>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
	                            <table class="table nk-tb-list nk-tb-ulist " data-auto-responsive="true" id="last_data_table" style="width:100%">
	                                <thead>
	                                    <tr class="nk-tb-item nk-tb-head"> 
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">ACID</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">ADEP</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">ATD</span></th> 
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">ADES</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">ATA</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">EmStart</span></th>  
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">EmTaxiOut</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">EmGndHolding</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">EmTakeOff</span></th> 
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">EmClimb</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">EmCruise</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">EmDescend At</span></th>  
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">EmHolding</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">EmApproach</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">EmLanding</span></th> 
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">EmTaxiIn</span></th>
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">EmTotal</span></th>
	                                    </tr>
	                                </thead>
	                                <tbody>
 
	                                </tbody>
	                            </table>
	                        </div>
	                        
	                    </div><!-- .card-preview -->
	                   
                	</div>
            	</div> 
    		</div>
	    </div>
	</div>
</div>
@endsection
@section('footer_scripts') 
<script defer src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>  

<script type="text/javascript"> 
var TableLastData ;  
var TableCityPair ;  
var TableEmDomInter; 
      // DataTable
let Emlabel, Emvalue, Emday;
let graph= @json($graph);
<?php
$emLabel = []; $emValue = []; $emDay =[];
foreach ($graph as $grp){
	foreach($grp as $key => $value){
		switch ($key) {
			case 'day': array_push($emDay, $value);break;
			case 'labelton': array_push($emLabel, $value);break;
			case 'emton': array_push($emValue, $value);break;
		}
	}
}
?>
function initDataTable() {
    TableLastData = $('#last_data_table').DataTable({
         processing: true,
         serverSide: true,
         scrollX: true, 
         language: { 
         	search: '', 
         	searchPlaceholder: "Search...",
		 },
		 // "oLanguage": {
   //             "sInfo" : "View _START_ to _END_ of _TOTAL_ Rows",// text you want show for info section
   //        },
	 
		 lengthMenu: [ 10, 25, 50, 75, 100 ],
         ajax: "{{ route('dashboard.getLastData') }}",
         columns: [
			{ data: 'acid' }, 				// 0
			{ data: 'adep_id' }, 			// 1
			{ data: 'atd' },				// 2
			{ data: 'ades_id' },			// 3
			{ data: 'ata' },				// 4
			{ data: 'emissionstart' },		// 5
			{ data: 'emissiontaxiout' },	// 6
			{ data: 'emissiongndholding' },	// 7
			{ data: 'emissiontakeoff' },	// 8
			{ data: 'emissionclimb' },		// 9
			{ data: 'emissioncruise' },		// 10
			{ data: 'emissiondescend' },	// 11
			{ data: 'emissionholding' },	// 12
			{ data: 'emissionapproach' },	// 13
			{ data: 'emissionlanding' },	// 14
			{ data: 'emissiontaxiin' },		// 15
			{ data: 'emissiontotal' }, 		// 16
			{ data: 'fpl_id' }				// 17
			
         ],
          "columnDefs": [
          	{ className: "nk-tb-col tb-col-md", "targets": [ 0 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md", "targets": [ 1 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md", "targets": [ 2 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md", "targets": [ 3 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md", "targets": [ 4 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md", "targets": [ 5 ],  "searchable": false},
		    { className: "nk-tb-col tb-col-md", "targets": [ 6 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md", "targets": [ 7 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md", "targets": [ 8 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md", "targets": [ 9 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md", "targets": [ 10 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md", "targets": [ 11 ],  "searchable": false},
		    { className: "nk-tb-col tb-col-md", "targets": [ 12 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md", "targets": [ 13 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md", "targets": [ 14 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md", "targets": [ 15 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md", "targets": [ 16 ], "searchable": false },
		    { "targets": [ 17 ], "searchable": false,"visible": false }
		  ],
		   "order": [[ 2, "desc" ]], 
           dom: '<"row justify-between g-2" <"col-7 col-sm-6 text-left" f><"col-5 col-sm-6 text-right"l> > <"datatable-wrap my-3"t> <"row align-items-center" <"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"i>><"clear">',
           // dom: 'Bfrtip',
           
      });
        return TableLastData;
} 
function initDataTable1() {
TableCityPair = $('#table_city').DataTable({
         processing: true,
         serverSide: true,  
	 
		 lengthMenu: [ 5, 10, 25, 50],
         ajax: "{{ route('dashboard.getCityPair') }}",
         columns: [
			{ data: 'city_pair' }, 			// 0
			{ data: 'sub_total' }, 			// 1  
         ],
          "columnDefs": [
          	{ className: "nk-tb-col tb-col-md", "targets": [ 0 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 1 ], "searchable": false },  
		  ],
		   
           dom: '<"row justify-between g-2" <"col-7 col-sm-6 text-left" ><"col-5 col-sm-6 text-right"> > <"datatable-wrap my-3"t> <"row align-items-center" <"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right">><"clear">',
           // dom: 'Bfrtip',
           
      });
       
    return TableCityPair;
}
function initDataTable2() {
TableEmDomInter = $('#edom_inter').DataTable({
         processing: true,
         serverSide: true,  
	 
		 lengthMenu: [ 5, 10, 25, 50],
         ajax: "{{ route('dashboard.getEmDomInter') }}",
         columns: [
			{ data: 'typeofflight' }, 			// 0
			{ data: 'sub_total' }, 			// 1  
         ],
          "columnDefs": [
          	{ className: "nk-tb-col tb-col-md", "targets": [ 0 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 1 ], "searchable": false },  
		  ],
		   
           dom: '<"row justify-between g-2" <"col-7 col-sm-6 text-left" ><"col-5 col-sm-6 text-right"> > <"datatable-wrap my-3"t> <"row align-items-center" <"col-7 col-sm-12 col-md-9"><"col-5 col-sm-12 col-md-3 text-left text-md-right">><"clear">',
           // dom: 'Bfrtip',
           
      });
       
    return TableEmDomInter;
}  
$(document).ready(function(){
	initDataTable(); 
	initDataTable1();
	initDataTable2();
	
});

var ctx = document.getElementById('myChart').getContext('2d');

var gradient = ctx.createLinearGradient(0, 166, 0, 0);
	  	gradient.addColorStop(0, "lime"); 
		gradient.addColorStop(0.33, "yellow");
		gradient.addColorStop(0.67, "orange");
		gradient.addColorStop(1, "red");
	   
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($emLabel),
        datasets: [{
            label: false,
            data: @json($emValue),
            backgroundColor: gradient,
            borderColor: gradient,
            pointBorderColor: gradient,
            pointBackgroundColor: gradient,
            fill: false,
            borderWidth: 3,
            hoverBackgroundColor: 'rgba(255,99,132,0.4)',
            hoverBorderColor: 'rgba(255,99,132,1)',
        }]
    },
    options: {
        scales: {
        	  yAxes: [{
		        ticks: {
		            beginAtZero: true,
		            callback: function(value, index, values) {
		                return '';
		            },
		        },
		        gridLines: {
		            display: false,
		            drawBorder: false,
		        },
		    }],
		    xAxes: [{
		        ticks: {
		            beginAtZero: true,
		            callback: function(value, index, values) {
		                return value;
		            },
		        },
		        gridLines: {
		            display: false,
		            drawBorder: false,
		        },
		    }],
        },
        legend: {
            display: false
        }
    }
});
</script>
@endsection