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
		div.DTFC_LeftBodyLiner{
			overflow: hidden;
		}
  </style> 
@endsection

@section('content') 
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
        	 <div class="nk-block ">    
            	<div class="row g-gs" style="min-height: 100%;">
            		<div class="col-md-6 col-xxl-6">
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-title-group align-start mb-2">
                                    <div class="card-title">
                                        <h5 class="title">{{$page->title}}</h5>
                                    </div>
                                </div>
                                 <!-- tool box -->
                                <div class="nk-ecwg8-ck">
                                	<div class="row">
                                		<div class="col-md-6">
                                			<div class="form-group">
	                                            <label class="form-label" for="full-name">Periode</label>
	                                            <div class="form-control-wrap">
	                                                <div class="form-control-select">
	                                                <select class="form-control form-control-lg" id="opt_period">
	                                                	<option value="" selected="selected">Select Option</option>
	                                                	<option value="Daily">Daily</option>
	                                                	<option value="Monthly">Monthly</option>
	                                                	<option value="Annual">Annual</option>
	                                                	<option value="Range">Range Filter</option>
	                                                </select>
	                                                </div>
	                                            </div>
                                        	</div>
                                		</div>
                                		<div class="col-md-6" >
                                			<div class="form-group d-none" id="day_box">
	                                            <label class="form-label" for="">&nbsp;</label>
	                                            <div class="form-control-wrap">
                                                    <div class="form-icon form-icon-left">
                                                        <em class="icon ni ni-calendar"></em>
                                                    </div>
                                                    <input type="text"  autocomplete="off" class="form-control form-control-lg date-picker" data-date-format="yyyy-mm-dd" id="date_req">
                                                </div>
	                                        </div>
	                                        <div class="form-group  d-none" id="month_box">
	                                            <label class="form-label" for="">&nbsp;</label>
	                                            <div class="form-control-wrap">
                                                    <div class="form-icon form-icon-left">
                                                        <em class="icon ni ni-calendar"></em>
                                                    </div>
                                                    <input type="text"  autocomplete="off" class="form-control form-control-lg date-picker" data-date-format="yyyy-mm" id="date_req_month">
                                                </div>
	                                        </div>
	                                        <div class="form-group  d-none" id="year_box">
	                                            <label class="form-label" for="">&nbsp;</label>
	                                            <div class="form-control-wrap">
                                                    <div class="form-icon form-icon-left">
                                                        <em class="icon ni ni-calendar"></em>
                                                    </div>
                                                    <input type="text"  autocomplete="off" class="form-control form-control-lg date-picker" data-date-format="yyyy" id="date_req_year">
                                                </div>
	                                        </div>
	                                        <div class="form-group d-none"  id="day_box1">
	                                            <label class="form-label" for="">&nbsp;</label>
	                                            <div class="form-control-wrap">
                                                    <div class="form-icon form-icon-left">
                                                        <em class="icon ni ni-calendar"></em>
                                                    </div>
                                                    <input type="text"  autocomplete="off" class="form-control form-control-lg date-picker" data-date-format="yyyy-mm-dd" id="_date_req">
                                                </div>
	                                        </div>
                                		</div>
                                	</div>
                                		 
                                    <div class="form-group mt-5">
                                        <button type="button" class="btn btn-lg btn-primary disabled" id="btn_show">Show</button>
                                    </div>
                                </div> 
                                <!-- end tool box -->
                            </div>
                        </div>
                    </div>
	                <div class="col-md-6">
	                	<div id="globe" class="position-absolute end-0 top-10 mt-sm-0 mt-7 me-lg-7 peekaboo " style="right: 0;">
			                <canvas id="can_globe" width="500" height="400" class="w-lg-100 h-lg-100 w-100 h-75 me-lg-0 me-n10 mt-lg-0 mr-0" style="float:right"></canvas>
			            </div> 
	                </div>
	                 
	            </div>
	        </div>
            <div class="nk-block d-none not-found">
                <div class="row g-gs" style="min-height: 100%;">
                    <div class="col-md-12">
                        <div class="card card-preview">
                            <div class="card-inner" style="width:100%"> 
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <span>No data for the selected period</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        	<div class="nk-block card_box d-none" id="total_em">    
            	<div class="row g-gs" style="min-height: 100%;">
            		<div class="col-md-12">
		        	  	<div class="card card-preview">
	                        <div class="card-inner" style="width:100%">
	                        	<div class="card-title-group mb-3">
                                    <div class="card-title">
                                        <h6 class="title" id="ttl_total_em">Emission Total in  </h6>
                                    </div> 
                                </div>
                                <div class="row">
                                	<div class="col-md-6">
                                		<table class="table w-100" id="tb_pie" data-auto-responsive="true" >
										  <thead>
										    <tr>
										      <th scope="col" class="text-center">City Pair</th>
										      <th scope="col" class="text-center">Amount (Kg)</th>
										    </tr>
										  </thead>
										  <tbody >
										     
										  </tbody>
										</table>
                                	</div>
                                	<div class="col-md-6">
                                		<div class="card card-preview h-100" style="min-height: 420px;">
                                            <div class="card-inner h-100"> 
                                                <div class="nk-ck-sm">
                                                    <div id="pieChart" class="w-100"></div>
                                                </div>
                                            </div>
                                        </div><!-- .card-preview -->
                                	</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nk-block card_box d-none" id="hourly_tab">    
            	<div class="row g-gs" style="min-height: 100%;">
            		<div class="col-md-12">
		        	  	<div class="card card-preview">
	                        <div class="card-inner">
                                <div class="card-title-group align-start mb-3">
                                    <div class="card-title">
                                        <h6 class="title" id="ttl_hourly"></h6>
                                    </div>
                                </div>    
                                <ul class="nav nav-tabs">
								    <li class="nav-item">
								        <a class="nav-link active" data-toggle="tab" href="#tabItem5" id="idtab5"><em class="icon ni ni-user"></em><span>Total</span></a>
								    </li>
								    <li class="nav-item">
								        <a class="nav-link" data-toggle="tab" href="#tabItem6" id="idtab6"><em class="icon ni ni-lock-alt"></em><span>Detail</span></a>
								    </li> 
								</ul>
								<div class="tab-content">
								    <div class="tab-pane active" id="tabItem5"> 
								        <div id="sbar_chart" class ="text-center w-100" ></div> 
								    </div>
								    <div class="tab-pane" id="tabItem6"> 
								        <div id="mbar_chart" class ="text-center w-100"></div> 
								    </div>
								    
								</div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        	<div class="nk-block card_box d-none" id="table_em">    
            	<div class="row g-gs" style="min-height: 100%;">
            		<div class="col-md-12">
		        	  	<div class="card card-preview">
	                        <div class="card-inner" style="width:100%">
	                        	<div class="card-title-group mb-3">
                                    <div class="card-title">
                                        <h6 class="title" id="ttl_em_table">Emission Data </h6>
                                    </div>
                                  
                                </div>
	                            <table class="table nk-tb-list nk-tb-ulist " data-auto-responsive="true" id="last_data_table" style="width:100%">
	                                <thead>
	                                    <tr class="nk-tb-item nk-tb-head"> 
	                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">City Pair</span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Flights</span></th>  
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
	                        
	                    </div><!-- .card-preview -->
	                   
                	</div>
            	</div> 
    		</div>
    		
	    </div>
	</div>
</div>
@endsection
@section('footer_scripts')   
<script src="//code.highcharts.com/highcharts.js"></script>
<script src="//code.highcharts.com/modules/data.js"></script>
<script src="//code.highcharts.com/highcharts-more.js"></script>
<script src="//code.highcharts.com/modules/series-label.js"></script>
<script src="//code.highcharts.com/modules/exporting.js"></script>
<script src="//code.highcharts.com/modules/export-data.js"></script>
<script type="text/javascript">  
const piechart =Highcharts.chart('pieChart', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    credits: {
        enabled: false
    },
    title: {
        text: ''
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'City Pair',
        colorByPoint: true,
        data: []
    }]
});

const sbarchart = Highcharts.chart('sbar_chart', { 
    chart: {
        type: 'column'
    },
    credits: {
        enabled: false
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        type: 'category',
        labels: {
            // rotation: -45,
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Emission (Kg)'
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        pointFormat: 'Emission : <b>{point.y:.1f} Kg</b>'
    },
    series: [{
        name: 'Emission',
        data: [],
        dataLabels: {
            enabled: false,
            rotation: -90,
            color: '#FFFFFF',
            align: 'right',
            format: '{point.y:.1f}', // one decimal
            y: 10, // 10 pixels down from the top
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    }]
});
var _datambar=[];
var testdata = [
  ["City Pair", "Start", "TaxiOut", "GrdHolding"],
  ["WIII-WAAA", 100, 40, 4],
  ["WARR-WADD", 500, 450, 6],
  ["WADY-WARR", 300, 50, 12]
];        
const mbarchart = Highcharts.chart('mbar_chart', {
    chart: {
        type: 'column',  //'line',
        zoomType: 'xy'
    },
    credits: {
        enabled: false
    },
    title: {
        text: ''
    },

    xAxis: {
        title: {
            text: 'City Pair'
        }
    },
    yAxis: [
        {
            title: {
                text: 'Emission (Kg)'
            }
        },
        {
            title: {
                text: ''
            },
            opposite:true
        }
    ],
    noData: {
         style: {
             fontWeight: 'bold',
             fontSize: '15px',
             color: '#303030'
         }
    },
    
    series: [{// First series 
    }, {// Second series 
    }], 
    data: {
        rows: testdata, 
    } 
});
            
var idGlobe;

if($('#globe').length > -1) runGlobe(); 

var request_data; var  choice; var  box_day = $('#day_box'); 
var  box_month = $('#month_box'); var  box_year = $('#year_box'); 
var  box_day1 = $('#day_box1'); var  ttl_em_card='';
var DataMulti=[]; var DataBar=[]; var LabelBar=[]; 
var Start=[]; var TaxiOut=[]; var GndHolding=[]; var TakeOff=[]; 
var Climb=[]; var Cruise=[]; var Descend=[]; var Holding=[]; 
var Approach=[]; var Landing=[]; var TaxiIn=[];

// optional label 
var opt_ttl; var utc_ttl; var is_range=false; 

var dp_rng=$("#date_req").datepicker( {
    format: "yyyy-mm-dd",
    todayHighlight: true,
});
var dp_month=$("#date_req_month").datepicker( {
    format: "yyyy-mm",
    startView: "months", 
    minViewMode: "months"
});
var dp_year=$("#date_req_year").datepicker( {
    format: "yyyy",
    startView: "years", 
    minViewMode: "years"
});
 
var dp_rng=$("#_date_req").datepicker( {
    format: "yyyy-mm-dd",
    multidate:true,
    multidateSeparator:" - ",
    todayHighlight: true,
});

$('#opt_period').on('change', function(e) {
	// check any chart  
	// remove canvas bar
	$('#barChartData').remove();
	$('#barChartMultiple').remove();
	
	is_range = false;
    choice = this.value;

    $('.card_box').addClass("d-none");
  	switch(choice) {
	  case 'Daily'	: 
	  		// setDatePicker('#date_req');
	  		box_day.removeClass("d-none");
	  		box_day1.addClass("d-none");
	  		box_month.addClass("d-none");
	  		box_year.addClass("d-none");
	  		request_data ='?type=daily&reqs=';
	  break;
	  case 'Monthly': 
	  		box_day.addClass("d-none");
	  		box_day1.addClass("d-none");
	  		box_month.removeClass("d-none");
	  		box_year.addClass("d-none");
	  		request_data ='?type=monthly&reqs=';
	  break;
	  case 'Annual': 
	  		box_day.addClass("d-none");
	  		box_day1.addClass("d-none");
	  		box_month.addClass("d-none");
	  		box_year.removeClass("d-none");
	  		request_data ='?type=annual&reqs=';
	  break;
	  
	  case 'Range'	: 
	  		box_day.addClass("d-none");
	  		box_day1.removeClass("d-none");
	  		box_month.addClass("d-none");
	  		box_year.addClass("d-none");
	  		request_data ='?type=range&reqs=';
	  		is_range = true;
	  break;
	  default :
	  		$("#btn_show").addClass('disabled');
	  break;
	}
	if(choice.length > 0)$("#btn_show").removeClass('disabled');
	// $(".dtpicker").focus();
});
$('#btn_show').click(function(){
	var date_req=null ;
	cancelAnimationFrame( idGlobe );
	switch(choice) {
	  case 'Daily'	: 
	  		date_req = $('#date_req').val();
	  		ttl_em_card = humanDate(date_req,'d');
	  		opt_ttl = 'Hourly';
	  		utc_ttl = 'Hours';
	  		request_data ='?type=daily&reqs=';
	  break;
	  case 'Monthly'	: 
	  		date_req = $('#date_req_month').val();
	  		opt_ttl = 'Daily';
	  		utc_ttl = 'Days';
	  		ttl_em_card = humanDate(date_req,'m');
	  		request_data ='?type=monthly&reqs=';
	  break;
	  case 'Annual'	: 
	  		date_req = $('#date_req_year').val();
	  		opt_ttl = 'Monthly';
	  		utc_ttl = 'Months';
	  		ttl_em_card = humanDate(date_req,'y');
	  		request_data ='?type=annual&reqs=';
	  break;
	  case 'Range' : 
	  		// date_req 		= $('#date_req').val();
	  		var _date_req = $('#_date_req').val();
	  		const _date_  = _date_req.split(" - ");

	  		ttl_em_card   = humanDate(_date_[0],'d') + ' - '+  humanDate(_date_[1],'d');
	  		date_req 	  = _date_[0]+'&reqs1='+_date_[1];
	  		request_data  ='?type=range&reqs=';
	  break;
	  default : NioApp.Toast('please select the option.', 'warning');
	  			$("#opt_period").focus();
	  break;
	  
	}
	
	if(date_req.length > 0){
		$('.card_box').removeClass("d-none");
		// $.when(func1(), func2(), func3()).done(function(a1, a2, a3){});
		
		function tbFunctionPie() { 
			$('#ttl_total_em').text('City Pair Emission Total in ' + ttl_em_card);
			initTablePie(request_data+date_req);
		} 

		async function funcDrawPie() {  
			const response = await fetch("{{ route('reportcity.cpPieChart') }}"+"/"+request_data+date_req);
		    const data = await response.json();
		    piechart.update({
			    series: [{
			        name: 'City Pair',
			        colorByPoint: true,
			        data: data
			    }]
		    });
		}
		async function funcDrawBar() {  
			const response = await fetch("{{ route('reportcity.cpgetBarChart') }}"+"/"+request_data+date_req);
		    const data = await response.json(); 
 			sbarchart.update({
			    series: [{
			        name: 'City Pair',
			        colorByPoint: true,
			        data: data.datasBar
			    }]
		    });
		 
			mbarchart.update({
                data : {
			        rows: data.datamBar
                }
		    });  
		     
		} 
		function func3() { 
			$('#ttl_em_table').text('City Pair Emission Data in ' + ttl_em_card);
			initDataTable(request_data+date_req);
		} 
		// if (!is_range){
			$('#hourly_tab').removeClass('d-none');
            $('#ttl_hourly').text('City Pair Emissions Chart in ' + ttl_em_card);

			tbFunctionPie();funcDrawPie();funcDrawBar();
			func3();
		// }
  //       else{
		// 	$('#hourly_tab').addClass('d-none');
		// 	tbFunctionPie();funcDrawPie();func3();
		// }
		
	}else{
		NioApp.Toast('please enter the date.', 'warning');
	}
	
});
var TablePie ;  
var TableLastData ;   
// $('#globe').on('click', function(){
// 	(new runGlobe()).animate();
	// same like this
	// let newA = new runGlobe();
	// newA.animate();
// });
function initDataTable(reqs) {
	let optional  = reqs; 
	if ( $.fn.dataTable.isDataTable( '#last_data_table' ) ) {
		let table = $('#last_data_table').DataTable();
		table.ajax.url( "{{ route('reportcity.cpgetData') }}"+'/'+optional ).load();
		table.ajax.reload();
	}else{
    	 TableLastData = $('#last_data_table').DataTable({ 
    	 destroy: true,
         processing: true,
         serverSide: true,
         scrollX: true, 
         scrollCollapse: true, 
         language: { 
         	search: '', 
         	searchPlaceholder: "Search..."
		 },
	 
		 lengthMenu: [ 10, 25, 50, 75, 100 ],
         ajax: "{{ route('reportcity.cpgetData') }}"+'/'+optional,
         columns: [
			{ data: 'City_Pair' },  // 0
			{ data: 'Flights' }, 	// 1 
			{ data: 'Start' },		// 2
			{ data: 'TaxiOut' },	// 3
			{ data: 'GndHolding' },	// 4
			{ data: 'TakeOff' },	// 5
			{ data: 'Climb' },		// 6
			{ data: 'Cruise' },		// 7
			{ data: 'Descend' },	// 8
			{ data: 'Holding' },	// 9
			{ data: 'Approach' },	// 10
			{ data: 'Landing' },	// 11
			{ data: 'TaxiIn' },		// 12
			{ data: 'Total' }, 		// 13 
			
         ],
          "columnDefs": [
          	{ className: "nk-tb-col tb-col-md", "targets": [ 0 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 1 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 2 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 3 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 4 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 5 ],  "searchable": false},
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 6 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 7 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 8 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 9 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 10 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 11 ],  "searchable": false},
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 12 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 13 ], "searchable": false }, 
		    // { "targets": [ 17 ], "searchable": false,"visible": false }
		  ],
           dom: '<"row justify-between g-2" <"col-7 col-sm-6 text-left" ><"col-5 col-sm-6 text-right"l> > <"datatable-wrap my-3"t> <"row align-items-center" <"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"i>><"clear">',
           // dom: 'Bfrtip',
           
      });
    return TableLastData;
	}
}  

function initTablePie(reqs) {
	let optional  = reqs; 
	if ( $.fn.dataTable.isDataTable( '#tb_pie' ) ) {
		let table = $('#tb_pie').DataTable();
		table.ajax.url( "{{ route('reportcity.cpTabelPie') }}"+'/'+optional ).load();
		table.ajax.reload();
	}else{
    	 TablePie = $('#tb_pie').DataTable({ 
    	 destroy: true,
         processing: true,
         serverSide: true,
         scrollX: true, 
         scrollCollapse: true, 
         language: { 
         	search: '', 
         	searchPlaceholder: "Search..."
		 },
	 
		 lengthMenu: [ 10, 25, 50, 75, 100 ],
         ajax: "{{ route('reportcity.cpTabelPie') }}"+'/'+optional,
         columns: [
			{ data: 'City_Pair' }, 				// 0
			{ data: 'Amount' },   
			
         ],
          "columnDefs": [
          	{ className: "nk-tb-col tb-col-md", "targets": [ 0 ], "searchable": false },
		    { className: "nk-tb-col tb-col-md text-right", "targets": [ 1 ], "searchable": false }
		  ],
		   
           dom: '<"row justify-between g-2" <"col-7 col-sm-6 text-left" ><"col-5 col-sm-6 text-right"l> > <"datatable-wrap my-3"t> <"row align-items-center" <"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"i>><"clear">',
           // dom: 'Bfrtip',
           
      });
    return TablePie;
	}
}  
 

// $('ul.hourly_menu li a').click(function(event) {
// 	event.preventDefault();
	
// 	var $this = $(this).parent().find('a');
// 	var $this_text = $(this).children().text();
// 	// console.log('child : '+$this_text);
// 	$(".hourly_menu li a").not($this).removeClass("active"); 

// 	$this.addClass("active");

// 	if($this_text=='Detail'){
// 		$('#sbar_box').fadeOut(200);
// 		$('#mbar_box').fadeIn(200);
// 	}else{
// 		$('#sbar_box').fadeIn(200);
// 		$('#mbar_box').fadeOut(200);
// 	}
	   	
	
// });
</script>
@endsection