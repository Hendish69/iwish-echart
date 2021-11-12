@extends('layouts.app')

@section('template_title')
    Welcome {{ Auth::user()->name }}
@endsection

@section('head')
@endsection

@section('template_linked_css')
<style>
.mtitle {
	vertical-align: middle !important; 
	font-weight: bold;
}
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
@endsection

@section('content')
<template v-if="state == 'create'">
	<div class="container-fluid">
	  <div class="nk-content-inner">
	    <div class="nk-content-body">
	      <div class="nk-block-head nk-block-head-sm">
	        <div class="nk-block-between">
	          <div class="nk-block-head-content">
	            <h3 class="nk-block-title page-title">Create Postflight Report</h3>
	          </div>
	        </div>
				</div>
				<div class="nk-block">
					<div class="row">
			      <div class="col-md-12">
			        <div class="card">
			        	<table class="table">
			        		<tr>
			        			<td width="8%" class="mtitle">Flight Number</td>
			        			<td width="10%"><input type="text" class="form-control" v-model="form.flight_number"></td>
			        			<td width="8%" class="mtitle">Date</td>
			        			<td width="10%"><input type="text" id="field-date" placeholder="yyyy/mm/dd" class="form-control" v-model="form.date"></td>
			        			<td width="8%" class="mtitle">Departure</td>
			        			<td width="10%">
			        				<select id="field-departure" class="form-control" v-model="form.departure">
			        					<template v-for="airport in airportObject.data">
			        						<option v-bind:value="airport.value">${ airport.label }</option>
			        					</template>
			        				</select>
			        			</td>
			        			<td width="8%" class="mtitle">ATD (UTC)</td>
			        			<td width="10%"><input type="text" id="field-atd" class="form-control" v-model="form.atd"></td>
			        		</tr>
			        		<tr>
			        			<td width="8%" class="mtitle">Route</td>
			        			<td width="10%"><input type="text" class="form-control" v-model="form.route"></td>
			        			<td width="8%" class="mtitle">Aircraft Type</td>
			        			<td width="10%"><input type="text" class="form-control" v-model="form.aircraft"></td>
			        			<td width="8%" class="mtitle">Destination</td>
			        			<td width="10%">
			        				<select id="field-destination" class="form-control" v-model="form.departure">
			        					<template v-for="airport in airportObject.data">
			        						<option v-bind:value="airport.value">${ airport.label }</option>
			        					</template>
			        				</select></td>
			        			<td width="8%" class="mtitle">ATA (UTC)</td>
			        			<td width="10%"><input type="text" id="field-ata" class="form-control" v-model="form.ata"></td>
			        		</tr>
			        	</table>
			        	<table class="table">
			        		<tr>
			        			<td><p style="font-weight: bold; font-size: 20px;">1. SERVICE & COMMUNICATION</p></td>
			        		</tr>
			        	</table>
			        	<table class="table table-bordered table-striped" border="1" style="text-align: center;">
									<thead>
										<tr>
											<td rowspan="3" colspan="2">&nbsp;</td>
											<td rowspan="3">Service</td>
											<td colspan="8">Reception</td>
											<td colspan="3">Procedure</td>
											<td colspan="3">Pharaseology</td>
										</tr>
										<tr>
											
											<td rowspan="2">Freq.</td>
											<td colspan="5">Readibility</td>
											<td rowspan="2">Distance</td>
											<td>Flight</td>
											<td rowspan="2">Good</td>
											<td rowspan="2">Fair</td>
											<td rowspan="2">Poor</td>
											<td rowspan="2">Good</td>
											<td rowspan="2">Fair</td>
											<td rowspan="2">Poor</td>
										</tr>
										<tr>
											<td>1</td>
											<td>2</td>
											<td>3</td>
											<td>4</td>
											<td>5</td>
											<td>Level</td>
										</tr>
										<template v-for="(service, index) in form.services">
											<tr>
												<td><button class="btn btn-danger" v-on:click="removeService(index)">X</button></td>
												<td colspan="2">
													<select class="form-control" v-model="service.name" v-on:change="setServiceFreq(service)">
														<option value="">--Choose--</option>
														<template v-for="comm in comms">
															<option v-bind:value="comm.call_sign">${ comm.call_sign }</option>
														</template>
													</select>
												</td>
												<td><input type="text" readonly="" style="width: 75px;" class="form-control" v-model="service.freq"></td>
												<td><input type="radio" v-model="service.readibility" value="1"></td>
												<td><input type="radio" v-model="service.readibility" value="2"></td>
												<td><input type="radio" v-model="service.readibility" value="3"></td>
												<td><input type="radio" v-model="service.readibility" value="4"></td>
												<td><input type="radio" v-model="service.readibility" value="5"></td>
												<td><input type="text" class="form-control" v-model="service.distance" style="width: 75px;"></td>
												<td><input type="text" class="form-control" v-model="service.flight_level" style="width: 75px;"></td>
												<td><input type="radio" v-model="service.procedure" value="GOOD"></td>
												<td><input type="radio" v-model="service.procedure" value="FAIR"></td>
												<td><input type="radio" v-model="service.procedure" value="POOR"></td>
												<td><input type="radio" v-model="service.pharaseology" value="GOOD"></td>
												<td><input type="radio" v-model="service.pharaseology" value="FAIR"></td>
												<td><input type="radio" v-model="service.pharaseology" value="POOR"></td>
											</tr>
										</template>
										<tr>
											<td colspan="3"><button v-on:click="addService()" class="btn btn-primary">Add</button></td>
										</tr>
									</thead>
								</table>
								<table class="table">
			        		<tr>
			        			<td><p style="font-weight: bold; font-size: 20px;">2. NAVIGATION AIDS</p></td>
			        		</tr>
			        	</table>
			        	<table class="table table-bordered table-striped" border="1" id="NavigationAidTable" style="text-align: center;">
									<thead>
										<tr>
											<td rowspan="3" colspan="2">&nbsp;</td>
											<td rowspan="3">Type</td>
											<td rowspan="3">Ident</td>
											<td rowspan="3">Freq.</td>
											<td colspan="12">Reception</td>
										</tr>
										<tr>
											<td colspan="3">&lt; 50 NML</td>
											<td colspan="3">50-150 nml</td>
											<td colspan="3">100-150 NML</td>
											<td colspan="3">&gt;150 NML</td>
										</tr>
										<tr>
											<td>Good</td>
											<td>Fair</td>
											<td>Poor</td>
											<td>Good</td>
											<td>Fair</td>
											<td>Poor</td>
											<td>Good</td>
											<td>Fair</td>
											<td>Poor</td>
											<td>Good</td>
											<td>Fair</td>
											<td>Poor</td>
										</tr>
										<template v-for="(aids, index) in form.navigations">
											<tr>	
												<td><button class="btn btn-danger" v-on:click="removeNavigation(index)">X</button></td>
												<td colspan="2">
													<select class="form-control" v-model="aids.type" v-on:change="setNavigationAuto(aids)">
														<option value="">--Choose--</option>
														<template v-for="navaid in navaids">
															<option v-bind:value="navaid.definition+'-'+navaid.nav_id">${ navaid.definition }-${ navaid.nav_id }</option>
														</template>
													</select>
												</td>
												<td><input type="text" readonly class="form-control" v-model="aids.ident"></td>
												<td><input type="text" readonly class="form-control" v-model="aids.freq"></td>
												<td><input type="radio" v-model="aids.reception_1" value="GOOD"></td>
												<td><input type="radio" v-model="aids.reception_1" value="FAIR"></td>
												<td><input type="radio" v-model="aids.reception_1" value="POOR"></td>
												<td><input type="radio" v-model="aids.reception_2" value="GOOD"></td>
												<td><input type="radio" v-model="aids.reception_2" value="FAIR"></td>
												<td><input type="radio" v-model="aids.reception_2" value="POOR"></td>
												<td><input type="radio" v-model="aids.reception_3" value="GOOD"></td>
												<td><input type="radio" v-model="aids.reception_3" value="FAIR"></td>
												<td><input type="radio" v-model="aids.reception_3" value="POOR"></td>
												<td><input type="radio" v-model="aids.reception_4" value="GOOD"></td>
												<td><input type="radio" v-model="aids.reception_4" value="FAIR"></td>
												<td><input type="radio" v-model="aids.reception_4" value="POOR"></td>
											</tr>
										</template>
										<tr>
											<td colspan="3"><button v-on:click="addNavigation()" class="btn btn-primary">Add</button></td>
										</tr>
									</thead>	
								</table>
								<table class="table">
			        		<tr>
			        			<td><p style="font-weight: bold; font-size: 20px;">3. LIGHTNING</p></td>
			        		</tr>
			        	</table>
			        	<table class="table table-bordered table-striped" border="1" style="text-align: center;">
									<thead>
										<tr>
											<td rowspan="2" colspan="2">&nbsp;</td>
											<td rowspan="2">Type</td>
											<td colspan="3">Reception</td>
										</tr>
										<tr>
											<td>GOOD</td>
											<td>FAIR</td>
											<td>POOR</td>
										</tr>
										<template v-for="(lightning, index) in form.lightnings">
											<tr>
												<td><button v-if="lightning.removable" class="btn btn-danger" v-on:click="removeLightning(index)">X</button></td>
												<td colspan="2">
													<input v-if="lightning.field == 'text'" type="text" class="form-control" v-model="lightning.type">
													<select v-if="lightning.field == 'select'" class="form-control" v-model="lightning.type">
														<option value="">--Choose--</option>
														<template v-for="lightning in lightnings">
															<option v-bind:value="'PAPI RWY'+lightning.rwy_ident">PAPI RWY ${ lightning.rwy_ident }</option>
														</template>
													</select>
												</td>
												<td><input type="radio" v-model="lightning.reception" value="GOOD"></td>
												<td><input type="radio" v-model="lightning.reception" value="FAIR"></td>
												<td><input type="radio" v-model="lightning.reception" value="POOR"></td>
											</tr>
										</template>
										<tr>
											<td colspan="3"><button v-on:click="addLightning()" class="btn btn-primary">Add</button></td>
										</tr>
									</thead>	
								</table>
								<table class="table">
			        		<tr>
			        			<td><p style="font-weight: bold; font-size: 20px;">4. METEOROLOGICAL INFORMATION</p></td>
			        		</tr>
			        	</table>
			        	<table class="table table-bordered table-striped" border="1" style="text-align: center;">
			        		<tr>
			        			<td><textarea class="form-control" v-model="form.meteorological_information"></textarea></td>
			        		</tr>
			        	</table>
			        	<table class="table">
			        		<tr>
			        			<td><p style="font-weight: bold; font-size: 20px;">PRESENCE OF BIRDS</p></td>
			        		</tr>
			        	</table>
			        	<table class="table table-bordered table-striped" border="1" style="text-align: center;">
			        		<thead>
				        		<tr>
				        			<td>Birds</td>
				        			<td>Location</td>
				        			<td>Details</td>
				        			<td colspan="2">Time Of Observation</td>
				        		</tr>
				        		<tr>
				        			<td><input type="text" class="form-control" v-model="form.birds"></td>
				        			<td><input type="text" class="form-control" v-model="form.birds_location"></td>
				        			<td><input type="text" class="form-control" v-model="form.birds_detail"></td>
				        			<td>
				        				<input type="text" class="form-control" id="field-observation-date" v-model="form.birds_atdate">
				        			</td>
				        			<td>
				        				<input type="text" class="form-control" id="field-observation-time" v-model="form.birds_attime">
				        			</td>
				        		</tr>
				        		<tr>
				        			<td colspan="5"><textarea class="form-control" placeholder="Remarks And Suggestions" v-model="form.suggestion"></textarea></td>
				        		</tr>
				        	</thead>
			        	</table>
			        	<br>
			        	<table class="table table-bordered table-striped" border="1" style="text-align: center;">
			        		<thead>
				        		<tr>
				        			<td>Captain/PIC</td>
				        			<td>ID Number</td>
				        			<td>Email</td>
				        		</tr>
				        		<tr>
				        			<td><input type="text" class="form-control" v-model="form.pic"></td>
				        			<td><input type="text" class="form-control" v-model="form.id_no"></td>
				        			<td><input type="text" class="form-control" v-model="form.email"></td>
				        		</tr>	
				        	</thead>
			        	</table>
			        	<br>
			        	<table class="table">
			        		<tr>
			        			<td><button class="btn btn-danger" v-on:click="setState('list')">Cancel</button>&nbsp;&nbsp;<button class="btn btn-primary" v-on:click="process()">Submit</button></td>
			        		</tr>
			        	</table>
			        	<br>
			        </div><!-- .card -->
			      </div>
			    </div>
	      </div>
	  	</div>
		</div>
	</div>
</template><template v-if="state == 'view'">
	<div class="container-fluid">
	  <div class="nk-content-inner">
	    <div class="nk-content-body">
	      <div class="nk-block-head nk-block-head-sm">
	        <div class="nk-block-between">
	          <div class="nk-block-head-content">
	            <h3 class="nk-block-title page-title">View Postflight Report</h3>
	            <button class="btn btn-primary" v-on:click="setState('list')">Back</button>
	          </div>
	        </div>
				</div>
				<div class="nk-block">
					<div class="row">
			      <div class="col-md-12">
			        <div class="card">
			        	<table class="table">
			        		<tr>
			        			<td width="8%" class="mtitle">Flight Number</td>
			        			<td width="10%"><input readonly type="text" class="form-control" v-model="view.flight_number"></td>
			        			<td width="8%" class="mtitle">Date</td>
			        			<td width="10%"><input readonly type="text" class="form-control" v-model="view.date"></td>
			        			<td width="8%" class="mtitle">Departure</td>
			        			<td width="10%"><input readonly type="text" class="form-control" v-bind:value="view.departure?.arpt_name+' - '+view.departure?.icao"></td>
			        			<td width="8%" class="mtitle">ATD (UTC)</td>
			        			<td width="10%"><input readonly type="text" class="form-control" v-model="view.atd"></td>
			        		</tr>
			        		<tr>
			        			<td width="8%" class="mtitle">Route</td>
			        			<td width="10%"><input readonly type="text" class="form-control" v-model="view.route"></td>
			        			<td width="8%" class="mtitle">Aircraft Type</td>
			        			<td width="10%"><input readonly type="text" class="form-control" v-model="view.aircraft"></td>
			        			<td width="8%" class="mtitle">Destination</td>
			        			<td width="10%"><input readonly type="text" class="form-control" v-bind:value="view.destination?.arpt_name+' - '+view.departure?.icao"></td>
			        			<td width="8%" class="mtitle">ATA (UTC)</td>
			        			<td width="10%"><input readonly type="text" class="form-control" v-model="view.ata"></td>
			        		</tr>
			        	</table>
			        	<table class="table">
			        		<tr>
			        			<td><p style="font-weight: bold; font-size: 20px;">1. SERVICE & COMMUNICATION</p></td>
			        		</tr>
			        	</table>
			        	<table class="table table-bordered table-striped" border="1" style="text-align: center;">
									<thead>
										<tr>
											<td rowspan="3" colspan="2">&nbsp;</td>
											<td rowspan="3">Service</td>
											<td colspan="8">Reception</td>
											<td colspan="3">Procedure</td>
											<td colspan="3">Pharaseology</td>
										</tr>
										<tr>
											
											<td rowspan="2">Freq.</td>
											<td colspan="5">Readibility</td>
											<td rowspan="2">Distance</td>
											<td>Flight</td>
											<td rowspan="2">Good</td>
											<td rowspan="2">Fair</td>
											<td rowspan="2">Poor</td>
											<td rowspan="2">Good</td>
											<td rowspan="2">Fair</td>
											<td rowspan="2">Poor</td>
										</tr>
										<tr>
											<td>1</td>
											<td>2</td>
											<td>3</td>
											<td>4</td>
											<td>5</td>
											<td>Level</td>
										</tr>
										<template v-for="(service, index) in view.services">
											<tr>
												<td colspan="3"><input readonly type="text" class="form-control" v-model="service.name"></td>
												<td><input type="text" readonly style="width: 75px;" class="form-control" v-model="service.freq"></td>
												<td><input type="radio" v-model="service.readibility" value="1"></td>
												<td><input type="radio" v-model="service.readibility" value="2"></td>
												<td><input type="radio" v-model="service.readibility" value="3"></td>
												<td><input type="radio" v-model="service.readibility" value="4"></td>
												<td><input type="radio" v-model="service.readibility" value="5"></td>
												<td><input type="text" class="form-control" v-model="service.distance" style="width: 75px;"></td>
												<td><input type="text" class="form-control" v-model="service.flight_level" style="width: 75px;"></td>
												<td><input type="radio" v-model="service.procedure" value="GOOD"></td>
												<td><input type="radio" v-model="service.procedure" value="FAIR"></td>
												<td><input type="radio" v-model="service.procedure" value="POOR"></td>
												<td><input type="radio" v-model="service.pharaseology" value="GOOD"></td>
												<td><input type="radio" v-model="service.pharaseology" value="FAIR"></td>
												<td><input type="radio" v-model="service.pharaseology" value="POOR"></td>
											</tr>
										</template>
									</thead>
								</table>
								<table class="table">
			        		<tr>
			        			<td><p style="font-weight: bold; font-size: 20px;">2. NAVIGATION AIDS</p></td>
			        		</tr>
			        	</table>
			        	<table class="table table-bordered table-striped" border="1" id="NavigationAidTable" style="text-align: center;">
									<thead>
										<tr>
											<td rowspan="3" colspan="2">&nbsp;</td>
											<td rowspan="3">Type</td>
											<td rowspan="3">Ident</td>
											<td rowspan="3">Freq.</td>
											<td colspan="12">Reception</td>
										</tr>
										<tr>
											<td colspan="3">&lt; 50 NML</td>
											<td colspan="3">50-150 nml</td>
											<td colspan="3">100-150 NML</td>
											<td colspan="3">&gt;150 NML</td>
										</tr>
										<tr>
											<td>Good</td>
											<td>Fair</td>
											<td>Poor</td>
											<td>Good</td>
											<td>Fair</td>
											<td>Poor</td>
											<td>Good</td>
											<td>Fair</td>
											<td>Poor</td>
											<td>Good</td>
											<td>Fair</td>
											<td>Poor</td>
										</tr>
										<template v-for="(aids, index) in view.navigations">
											<tr>	
												<td colspan="3"><input readonly type="text" class="form-control" v-model="aids.type"></td>
												<td><input type="text" readonly class="form-control" v-model="aids.ident"></td>
												<td><input type="text" readonly class="form-control" v-model="aids.freq"></td>
												<td><input type="radio" v-model="aids.reception_1" value="GOOD"></td>
												<td><input type="radio" v-model="aids.reception_1" value="FAIR"></td>
												<td><input type="radio" v-model="aids.reception_1" value="POOR"></td>
												<td><input type="radio" v-model="aids.reception_2" value="GOOD"></td>
												<td><input type="radio" v-model="aids.reception_2" value="FAIR"></td>
												<td><input type="radio" v-model="aids.reception_2" value="POOR"></td>
												<td><input type="radio" v-model="aids.reception_3" value="GOOD"></td>
												<td><input type="radio" v-model="aids.reception_3" value="FAIR"></td>
												<td><input type="radio" v-model="aids.reception_3" value="POOR"></td>
												<td><input type="radio" v-model="aids.reception_4" value="GOOD"></td>
												<td><input type="radio" v-model="aids.reception_4" value="FAIR"></td>
												<td><input type="radio" v-model="aids.reception_4" value="POOR"></td>
											</tr>
										</template>
									</thead>	
								</table>
								<table class="table">
			        		<tr>
			        			<td><p style="font-weight: bold; font-size: 20px;">3. LIGHTNING</p></td>
			        		</tr>
			        	</table>
			        	<table class="table table-bordered table-striped" border="1" style="text-align: center;">
									<thead>
										<tr>
											<td rowspan="2" colspan="2">&nbsp;</td>
											<td rowspan="2">Type</td>
											<td colspan="3">Reception</td>
										</tr>
										<tr>
											<td>GOOD</td>
											<td>FAIR</td>
											<td>POOR</td>
										</tr>
										<template v-for="(lightning, index) in view.lightnings">
											<tr>
												<td colspan="3"><input readonly type="text" class="form-control" v-model="lightning.type"></td>
												<td><input type="radio" v-model="lightning.reception" value="GOOD"></td>
												<td><input type="radio" v-model="lightning.reception" value="FAIR"></td>
												<td><input type="radio" v-model="lightning.reception" value="POOR"></td>
											</tr>
										</template>
									</thead>	
								</table>
								<table class="table">
			        		<tr>
			        			<td><p style="font-weight: bold; font-size: 20px;">4. METEOROLOGICAL INFORMATION</p></td>
			        		</tr>
			        	</table>
			        	<table class="table table-bordered table-striped" border="1" style="text-align: center;">
			        		<tr>
			        			<td><textarea readonly class="form-control">${ view.meteorological_information }</textarea></td>
			        		</tr>
			        	</table>
			        	<table class="table">
			        		<tr>
			        			<td><p style="font-weight: bold; font-size: 20px;">PRESENCE OF BIRDS</p></td>
			        		</tr>
			        	</table>
			        	<table class="table table-bordered table-striped" border="1" style="text-align: center;">
			        		<thead>
				        		<tr>
				        			<td>Birds</td>
				        			<td>Location</td>
				        			<td>Details</td>
				        			<td>Time Of Observation</td>
				        		</tr>
				        		<tr>
				        			<td><input readonly type="text" class="form-control" v-model="view.birds"></td>
				        			<td><input readonly type="text" class="form-control" v-model="view.birds_location"></td>
				        			<td><input readonly type="text" class="form-control" v-model="view.birds_detail"></td>
				        			<td><input readonly type="text" class="form-control" v-model="view.birds_at"></td>
				        		</tr>
				        		<tr>
				        			<td colspan="4"><textarea readonly class="form-control" placeholder="Remarks And Suggestions">${ view.suggestion }</textarea></td>
				        		</tr>
				        	</thead>
			        	</table>
			        	<br>
			        	<table class="table table-bordered table-striped" border="1" style="text-align: center;">
			        		<thead>
				        		<tr>
				        			<td>Captain/PIC</td>
				        			<td>ID Number</td>
				        			<td>Email</td>
				        		</tr>
				        		<tr>
				        			<td><input type="text" readonly class="form-control" v-model="view.pic"></td>
				        			<td><input type="text" readonly class="form-control" v-model="view.id_no"></td>
				        			<td><input type="text" readonly class="form-control" v-model="view.email"></td>
				        		</tr>	
				        	</thead>
			        	</table>
			        	<br>
			        	<br>
			        </div><!-- .card -->
			      </div>
			    </div>
	      </div>
	  	</div>
		</div>
	</div>
</template>
<template v-if="state == 'list'">
<div class="container-fluid">
  <div class="nk-content-inner">
    <div class="nk-content-body">
      <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
          <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Postflight Report</h3>
            <div class="nk-block-des text-soft">
              <p>You have total ${reportObject.data.length} postflight report(s).</p>
              <template v-if="isPilot">
              	<a class="btn btn-primary text-white" v-on:click="setState('create')">CREATE</a>
              </template>
            </div>
          </div>
        </div>
			</div>
			<div class="nk-block">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<table class="table" cellspacing="2">
								<thead>
									<tr>
										<th>FLIGHT NUMBER</th>
										<th>AIRCRAFT TYPE</th>
										<th>DATE</th>
										<th>DEPARTURE</th>
										<th>DESTINATION</th>
										<th>ETD</th>
										<th>CAPTAIN</th>
										<th>ACTION</th>
									</tr>
								</thead>
								<tbody>
									<template v-for="(report, index) in reportObject.data">
										<tr>
											<td>${ report.flight_number }</td>
											<td>${ report.aircraft }</td>
											<td>${ report.date }</td>
											<td>${ report.departure?.arpt_name } - ${ report.departure?.icao }</td>
											<td>${ report.destination?.arpt_name } - ${ report.destination?.icao }</td>
											<td>${ report.atd }</td>
											<td>${ report.pic }</td>
											<td><button class="btn btn-primary" v-on:click="setView(report.id)">VIEW</button></td>
										</tr>
									</template>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			</div>
  	</div>
	</div>
</div>
</template>
@endsection
@section('footer_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ url('js/vue.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script>
const API_URL = "<?php echo env('API_URL'); ?>"
toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-bottom-full-width",
  "preventDuplicates": true,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}
Vue.config.silent = true
var App = new Vue({
	el: '#app',
	delimiters: ['${', '}'],
	data() {
		return {
			isAdmin: false,
			isPilot: false,
			state: 'list',
			view: {},
			comms: [],
			navaids: [],
			form: {
				date: null,
				flight_number: null,
				departure: null,
				destination: null,
				route: null,
				aircraft: null,
				atd: null,
				ata: null,
				services: [],
				navigations: [],
				lightnings: [{
					removable: false,
					field: 'text',
					type: 'RWY LIGHT',
					reception: 'FAIR',
				}, {
					removable: false,
					field: 'text',
					type: 'TWY LIGHT',
					reception: 'FAIR',
				}, {
					removable: false,
					field: 'text',
					type: 'APRON LIGHT',
					reception: 'FAIR',
				}, {
					removable: false,
					field: 'text',
					type: 'ROTATING BEACON',
					reception: 'FAIR',
				}],
				information: [],
			},
			reportObject: {
				loaded: false,
				data: [],
			},
			airportObject: {
				loaded: false,
				data: [],
			}
		}
	},
	methods: {
		setState(state) {
			this.state = state
		},
		handleAirportObject() {
			XHR('GET', 'ajax/airport/list', null, response => {
				this.airportObject = {loaded: true, data: response.data}
			})
		},
		handleReportObject() {
			XHR('GET', 'ajax/postflight/list', null, response => {
				this.reportObject = {loaded: true, data: response.data}	
			})
		},
		process() {
			this.form.birds_at = this.form.birds_atdate+' '+this.form.birds_attime+':00'
			XHR('POST', 'ajax/postflight/create', this.form, response => {
				if (response.status == 'fail') {
					alert("Please check all fields")
				}

				if (response.status == 'error') {
					alert("Please check all fields\n\nDetail: "+response.message)
				}

				if (response.status == 'success') {
					alert('Report Added')

					this.setState('list')
				}
			})
		},
		setView(id) {
			this.setState('view')
			XHR('GET', 'ajax/postflight/view/'+id, null, response => {
				this.view = response.data
			})
		},
		addService() {
			this.form.services.push({
				name: '',
				readibility: 3,
				procedure: 'FAIR',
				pharaseology: 'FAIR',
			})
		},
		removeService(index) {
			this.form.services.splice(index, 1)
		},
		addNavigation() {
			this.form.navigations.push({
				type: '',
				reception_1: 'FAIR',
				reception_2: 'FAIR',
				reception_3: 'FAIR',
				reception_4: 'FAIR',
			})
		},
		removeNavigation(index) {
			this.form.navigations.splice(index, 1)
		},
		addLightning() {
			this.form.lightnings.push({
				reception: 'FAIR',
				removable: true,
				field: 'select',
			})
		},
		removeLightning(index) {
			this.form.lightnings.splice(index, 1)
		},
		loadAdditionalContent() {
			this.comms = []
			this.navaids = []
			this.lightnings = []

			$.ajax({
				type: 'GET',
				url: API_URL+'/freqarpt/'+this.form.departure,
				complete: response => {
					response.responseJSON.data.forEach(comm => {
						this.comms.push(comm)
					})
				}
			})

			$.ajax({
				type: 'GET',
				url: API_URL+'/freqarpt/'+this.form.destination,
				complete: response => {
					response.responseJSON.data.forEach(comm => {
						this.comms.push(comm)
					})
				}
			})


			$.ajax({
				type: 'GET',
				url: API_URL+'/navarpt/'+this.form.departure,
				complete: response => {
					response.responseJSON.data.forEach(navaid => {
						this.navaids.push(navaid)
					})
				}
			})

			$.ajax({
				type: 'GET',
				url: API_URL+'/navarpt/'+this.form.destination,
				complete: response => {
					response.responseJSON.data.forEach(navaid => {
						this.navaids.push(navaid)
					})
				}
			})

			$.ajax({
				type: 'GET',
				url: API_URL+'/rwyarpt/'+this.form.departure,
				complete: response => {
					response.responseJSON.data.forEach(lightning => {
						this.lightnings.push(lightning)
					})
				}
			})

			$.ajax({
				type: 'GET',
				url: API_URL+'/rwyarpt/'+this.form.destination,
				complete: response => {
					response.responseJSON.data.forEach(lightning => {
						this.lightnings.push(lightning)
					})
				}
			})
		},
		setServiceFreq(service) {
			var selected = null
			this.comms.forEach(comm => {
				if (comm.call_sign == service.name) {
					service.freq = comm.freq
				}
			})
		},
		setNavigationAuto(aids) {
			var selected = null

			this.navaids.forEach(navaid => {
				var string = navaid.definition+'-'+navaid.nav_id
				if (string == aids.type) {
					aids.ident = navaid.nav_ident
					aids.freq = navaid.freq
				}
			})
		},
		prepareCreate() {
			$('#field-departure').select2().on('select2:select', event => {
				this.form.departure = event.params.data.id
			})
			$('#field-destination').select2().on('select2:select', event => {
				this.form.destination = event.params.data.id
			})
			$('#field-date').datepicker({
				format: 'yyyy-mm-dd'
			}).on('changeDate', event => {
				this.form.date = event.format('yyyy-mm-dd')
			})

			$('#field-observation-date').datepicker({
				format: 'yyyy-mm-dd'
			}).on('changeDate', event => {
				this.form.birds_atdate = event.format('yyyy-mm-dd')
			})

			$('#field-observation-time').inputmask('99:99', {oncomplete: event => {
				this.form.birds_attime = $('#field-observation-time').val()
			}})

			$('#field-atd').inputmask('99:99', {oncomplete: event => {
				this.form.atd = $('#field-atd').val()
			}})
			$('#field-ata').inputmask('99:99', {oncomplete: event => {
				this.form.ata = $('#field-ata').val()
			}})
		}
	},
	watch: {
		state: function(data) {
			if (data == 'list') {
				this.handleReportObject()
			}

			if (data == 'create') {
				setTimeout(() => {
					this.prepareCreate()
				}, 500)
			}
		},
		'form.departure': function(data) {
			this.loadAdditionalContent()
		},
		'form.destination': function(data) {
			this.loadAdditionalContent()
		}
	},
	mounted() {
		this.isAdmin = <?php echo json_encode(Auth::user()->isAdmin()); ?>;
		this.isPilot = <?php echo json_encode(Auth::user()->hasRole('pilot')); ?>;
		this.handleReportObject()
		this.handleAirportObject()
		setTimeout(() => {
			this.prepareCreate()
		}, 500)
	},
})
</script>
@endsection