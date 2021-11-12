@extends('layouts.app')

@section('template_title')
    Welcome {{ Auth::user()->name }}
@endsection

@section('head')
@endsection

@section('template_linked_css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<style>
.iwish-section {
	font-weight: bold;
	font-size: 16px;
	cursor: pointer;
}
</style>
@endsection

@section('content')

<template v-if="state == 'summary'">
	<div class="container-fluid">
	  <div class="nk-content-inner">
	    <div class="nk-content-body">
	      <div class="nk-block-head nk-block-head-sm">
	        <div class="nk-block-between">
	          <div class="nk-block-head-content">
	            <h3 class="nk-block-title page-title">Summary</h3>
	          </div>
	          <div class="nk-block-head-content">
              <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="pageMenu">
                  <ul class="nk-block-tools g-3">
                    <li class="nk-block-tools-opt">
                      <a href="#" class="btn btn-icon btn-primary d-md-none"><em class="icon ni ni-plus"></em></a>
                       	<a  v-on:click="setState('list')" class="text-white btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                    </li>
                  </ul>
                </div>
              </div>
          	</div>
	        </div>
				</div>
				<div class="nk-block">
		      <div class="card card-stretch col-md-12">
		      	<br>
		      	<div class="row">
			      	<div class="col-md-5">
			      		<select class="form-control" v-model="summaryMonth">
			      			<option value="">--Month--</option>
			      			<option value="01">Jan</option>
			      			<option value="02">Feb</option>
			      			<option value="03">Mar</option>
			      			<option value="04">Apr</option>
			      			<option value="05">May</option>
			      			<option value="06">Jun</option>
			      			<option value="07">Jul</option>
			      			<option value="08">Aug</option>
			      			<option value="09">Sep</option>
			      			<option value="10">Oct</option>
			      			<option value="11">Nov</option>
			      			<option value="12">Dec</option>
			      			<option value="ALL">ALL</option>
			      		</select>
			      	</div>
			      	<div class="col-md-5">
			      		<input type="text" class="form-control" placeholder="Year" v-model="summaryYear">
			      	</div>
			      	<div class="col-md-2">
			      		<button class="btn btn-block btn-success" v-on:click="submitSummary()">Submit</button>
			      	</div>
		      	</div>
		      	<br>
		      	<p class="iwish-section">Non Data<span class="float-right">${ summaryObject.nondata.total }</span></p>
		      	<template v-for="section in summaryObject.data">
		      		<p data-toggle="collapse" v-bind:href="'#subsection'+section.id" class="iwish-section">${ section.sub_id } ${ section.definition } <span class="float-right">${ section.total }</span></p>
		      		<div class="collapse" v-bind:id="'subsection'+section.id">
		      			<template v-for="detail in section.submenus">
		      				<p data-toggle="collapse" class="iwish-section" v-bind:href="'#airport'+detail.id">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${ detail.sub_id } ${ detail.definition } <span class="float-right">${ detail.total }</span></p>
		      				<template v-if="detail.has_airport">
		      					<div class="collapse" v-bind:id="'airport'+detail.id">
		      						<template v-for="airport in detail.airports">
		      							<p class="iwish-section">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		      							${ airport.icao } - ${ airport.arpt_name } - ${ airport.city_name }<span class="float-right">${ airport.total }</span></p>
		      						</template>
		      					</div>	
		      				</template>
		      			</template>
		      			<hr>
		      		</div>
		      	</template>
		      </div>
		    </div>
			</div>
		</div>
	</div>
</template>

<template v-if="state == 'create'">
<div class="container-fluid">
	  <div class="nk-content-inner">
	    <div class="nk-content-body">
	      <div class="nk-block-head nk-block-head-sm">
	        <div class="nk-block-between">
	          <div class="nk-block-head-content">
	            <h3 class="nk-block-title page-title">Create New Issue</h3>
	          </div>
	          <div class="nk-block-head-content">
              <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="pageMenu">
                  <ul class="nk-block-tools g-3">
                    <li class="nk-block-tools-opt">
                      <a href="#" class="btn btn-icon btn-primary d-md-none"><em class="icon ni ni-plus"></em></a>
                       	<a href="#" v-on:click="setState('list')" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                    </li>
                  </ul>
                </div>
              </div>
          	</div>
	        </div>
				</div>
					<div class="nk-block">
		        <div class="card card-stretch col-md-12">
		        	<br><h5>Data</h5>
		        	<table class="table">
		        		<tbody>
		        			<tr>
		        				<td style="vertical-align: middle;" width="15%"><strong>Title</strong></td>
		        				<td><input type="text" v-model="issueObjectParam.title" class="form-control"></td>
		        			</tr>
		        			<tr>
		        				<td style="vertical-align: middle;">Topic</td>
		        				<td>
		        					<select class="form-control" v-model="issueObjectParam.topic_id">
		        						<option value="">--Choose--</option>
		        						<template v-for="topic in topicObject.data">
		        							<option v-bind:value="topic.id">${ topic.title }</option>
		        						</template>
		        					</select>
		        				</td>
		        			</tr>
		        			<tr>
		        				<td style="vertical-align: middle;">Priority</td>
		        				<td>
		        					<select class="form-control" v-model="issueObjectParam.priority_id">
		        						<option value="">--Choose--</option>
		        						<template v-for="priority in priorityObject.data">
		        							<option v-bind:value="priority.id">${ priority.name }</option>
		        						</template>
		        					</select>
		        				</td>
		        			</tr>
		        			<!-- <tr>
		        				<td style="vertical-align: middle;">Part</td>
		        				<td>
		        					<select class="form-control" v-model="issueObjectParam.part_id">
		        						<template v-for="part in partObject.data">
		        							<option v-bind:value="part.id">${ part.name }</option>
		        						</template>
		        					</select>
		        				</td>
		        			</tr> -->
		        			<tr>
		        				<td style="vertical-align: middle;">Section</td>
		        				<td>
		        					<select class="form-control" v-model="issueObjectParam.section_id">
		        						<option value="">--Choose--</option>
		        						<template v-for="section in sectionObject.data">
		        							<option v-bind:value="section.id">${ section.sub_id } ${ section.definition }</option>
		        						</template>
		        					</select>
		        				</td>
		        			</tr>
			        		<template v-if="issueObjectParam.section_id != null">
				        		<tr>
				        			<td style="vertical-align: middle;">Subsection</td>
				        			<td>
				        				<select class="form-control" v-model="issueObjectParam.subsection_id">
				        					<option value="">--Choose--</option>
				        					<template v-for="subsection in subsectionObject.data">
				        						<option v-bind:value="subsection.id">${ subsection.sub_id } ${ subsection.definition }</option>
				        					</template>
				        				</select>
				        			</td>
				        		</tr>
				        	</template>
				        	<tr v-show="issueObjectParam.subsection_id == 96">
		        				<td style="vertical-align: middle;">Airport</td>
		        				<td>
		        					<select class="form-control" v-model="issueObjectParam.airport_id">
		        						<option value="">--Choose--</option>
		        						<template v-for="airport in airportObject.data">
		        							<option v-bind:value="airport.value">${ airport.label }</option>
		        						</template>
		        					</select>
		        				</td>
		        			</tr>
		        			<tr>
		        				<td style="vertical-align: middle;"><strong>Description</strong></td>
		        				<td>
		        					<textarea class="form-control" v-model="issueObjectParam.description"></textarea>
		        				</td>
		        			</tr>
		        	</table>
		        </div><!-- .card -->
		        <div class="card card-stretch col-md-12">
		        	<br>
		        	<h5>Attachments</h5>
		        	<table class="table">
		        		<tbody>
		        			<template v-for="attachment in attachmentObject.data">
		        				<tr>
		        					<td><a v-bind:href="attachment.attachment_url" target="_blank">${ attachment.attachment}</a></td>
		        					<td><a href="#" v-on:click="removeAttachment(attachment.id)">Remove</a></td>
		        				</tr>
		        			</template>
		        			<tr>
		        				<td colspan="2">Add New File <input type="file" v-on:change="upload($event)"></td>
		        			</tr>
		        		</tbody>
		        	</table>
		        </div>
		        <div class="card card-stretch col-md-12">
		        	<a class="btn btn-block btn-success" v-on:click="create()">Create</a>
		        </div>
		      </div>
			</div>
		</div>
	</div>
</template>

<!-- <div class="container-fluid">
	  <div class="nk-content-inner">
	    <div class="nk-content-body">
	      <div class="nk-block-head nk-block-head-sm">
	        <div class="nk-block-between">
	          <div class="nk-block-head-content">
	            <h3 class="nk-block-title page-title">Create New Issue</h3>
	          </div>
	          <div class="nk-block-head-content">
              <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="pageMenu">
                  <ul class="nk-block-tools g-3">
                    <li class="nk-block-tools-opt">
                      <a href="#" class="btn btn-icon btn-primary d-md-none"><em class="icon ni ni-plus"></em></a>
                       	<a href="#" v-on:click="setState('list')" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                    </li>
                  </ul>
                </div>
              </div>
          	</div>
	        </div> -->

<template v-if="state == 'faq'">
	<div class="container-fluid">
	  <div class="nk-content-inner">
	    <div class="nk-content-body">
	      <div class="nk-block-head nk-block-head-sm">
	        <div class="nk-block-between">
	          <div class="nk-block-head-content">
	            <h3 class="nk-block-title page-title">Frequently Asked Question (FAQ)</h3>
	          </div>
						<div class="nk-block-head-content">
								<div class="toggle-wrap nk-block-tools-toggle">
									<a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
									<div class="toggle-expand-content" data-content="pageMenu">
										<ul class="nk-block-tools g-3">
											<li class="nk-block-tools-opt">
												<a href="#" class="btn btn-icon btn-primary d-md-none"><em class="icon ni ni-plus"></em></a>
													<a v-on:click="setState('list')" class="text-white btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
											</li>
										</ul>
									</div>
								</div>
							</div>
          	</div>
					</div>
	        <div id="sortable-faq" class="card card-stretch">	
	        	<template v-for="(faq, index) in faqObject.data">
		        	<div id="faqs" class="accordion" :key="faq.id">
		        		<div class="accordion-item">
		        			<a href="#" class="accordion-head" v-bind:data-target="'#faq-'+index" aria-expanded="false">
		        				<h6 class="title">
		        					<template v-if="faq.editable">
		        						<input type="text" v-model="faq.title" class="form-control">
		        					</template>
		        					<template v-else>
		        						${ faq.title }
		        					</template>
		        				</h6>
		        				<span class="accordion-icon"></span>
		        			</a>
		        			<div class="accordion-body" v-bind:id="'faq-'+index" data-parent="#faqs" style="">
		        				<div class="accordion-inner">
		        					<template v-if="faq.editable">
		        						<textarea class="form-control" v-model="faq.description"></textarea>
		        					</template>
		        					<template v-else>
		        						${ faq.description }
		        					</template>
		        					<br><br>
		        					<template v-if="faq.editable">	
		        						<p>
		        							<a class="btn btn-danger text-white" v-on:click="faq.editable = false">Cancel</a>
		        							<a class="btn btn-success text-white" v-on:click="saveFaq(faq)">Save</a>
		        						</p>
		        					</template>
		        					<template v-else>
		        						<p><a class="btn btn-danger text-white" v-on:click="faq.editable = true">Edit</a></p>
		        					</template>
		        				</div>
		        			</div>
		        		</div>
		        	</div>
		        </template>
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
            <h3 class="nk-block-title page-title">Feedback</h3>
            <div class="nk-block-des text-soft">
              <p>You have total ${ issueObject.data.length } feedback(s).</p>
              <p>
              	<a class="btn btn-primary text-white" v-on:click="setState('create')">CREATE</a>
              	<a class="btn btn-primary text-white" v-on:click="setState('faq')">FAQ</a>
              	<a class="btn btn-primary text-white" v-on:click="setState('faq-manager')">FAQ Manager</a>
              	<a class="btn btn-primary text-white" v-on:click="setState('summary')">Summary</a>
              </p>
            </div>
          </div>
        </div>
			</div>
				<div class="nk-block">
	        <div class="card card-stretch">
	        	<table class="table" cellspacing="2">
	        		<thead>
	        			<tr>
	        				<th>NUMBER</th>
	        				<th>DATE</th>
	        				<th>TITLE</th>
	        				<th>REPORTER</th>
	        				<th>STATUS</th>
	        			</tr>
	        		</thead>
	        		<tbody>
	        			<template v-for="issue in issueObject.data">
	        				<tr>
	        					<td><a style="cursor: pointer; color: blue;" v-on:click="loadSelectedIssue(issue)">${ issue.no }</a></td>
	        					<td>${ issue.created_at_label }</td>
	        					<td>${ issue.title }</td>
	        					<td>${ issue.reporter?.first_name } ${ issue.reporter?.last_name }</td>
	        					<td>
	        						<template v-if="issue.progress_user?.id == 3">
	        							<a class="btn btn-success text-white">${ issue.progress_user?.label }</a>
	        						</template>
	        						<template v-else>
	        							<a class="btn btn-primary text-white">${ issue.progress_user?.label }</a>
	        						</template>
	        					</td>	
	        				</tr>
	        			</template>
	        		</tbody>
	        	</table>
	        </div><!-- .card -->
	      </div>
		</div>
	</div>
</div>      
</template>
<template v-if="state == 'detail'">
	<div class="container-fluid">
	  <div class="nk-content-inner">
	    <div class="nk-content-body">
	      <div class="nk-block-head nk-block-head-sm">
	        <div class="nk-block-between">
	          <div class="nk-block-head-content">
	            <h3 class="nk-block-title page-title">Issue #${ issueObject.selected.no }</h3>
	          </div>
	          <div class="nk-block-head-content">
              <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="pageMenu">
                  <ul class="nk-block-tools g-3">
                    <li class="nk-block-tools-opt">
                      <a href="#" class="btn btn-icon btn-primary d-md-none"><em class="icon ni ni-plus"></em></a>
                       	<a style="color: white;" v-on:click="setState('list')" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                    </li>
                  </ul>
                </div>
              </div>
          	</div>
	        </div>
				</div>
					<div class="nk-block">
		        <div class="card card-stretch col-md-12">
		        	<br><h5>Data</h5>
		        	<template v-if="issueObject.selected.progress_id == 8">
			        	<table class="table">
			        		<tbody>
			        			<tr>
			        				<td style="vertical-align: middle;" width="15%"><strong>Number</strong></td>
			        				<td>${ issueObject.selected.no }</td>
			        			</tr>
			        			<tr>
			        				<td style="vertical-align: middle;">Reporter</td>
			        				<td>${ issueObject.selected.reporter.name }</td>
			        			</tr>
			        			<tr>
			        				<td style="vertical-align: middle;"><strong>Title</strong></td>
			        				<td><input readonly type="text" v-model="issueObject.selected.title" class="form-control"></td>
			        			</tr>
			        			<tr>
			        				<td style="vertical-align: middle;">Topic</td>
			        				<td>
			        					<select readonly class="form-control" v-model="issueObject.selected.topic_id">
			        						<option value="">--Choose--</option>
			        						<template v-for="topic in topicObject.data">
			        							<option v-bind:value="topic.id">${ topic.title }</option>
			        						</template>
			        					</select>
			        				</td>
			        			</tr>
			        			<tr>
			        				<td style="vertical-align: middle;">Priority</td>
			        				<td>
			        					<select readonly class="form-control" v-model="issueObject.selected.priority_id">
			        						<option value="">--Choose--</option>
			        						<template v-for="priority in priorityObject.data">
			        							<option v-bind:value="priority.id">${ priority.name }</option>
			        						</template>
			        					</select>
			        				</td>
			        			</tr>
			        			<tr>
			        				<td style="vertical-align: middle;">Section</td>
			        				<td>
			        					<select readonly class="form-control" v-model="issueObject.selected.section_id">
			        						<option value="">--Choose--</option>
			        						<template v-for="section in sectionObject.data">
			        							<option v-bind:value="section.id">${ section.sub_id } ${ section.definition }</option>
			        						</template>
			        					</select>
			        				</td>
			        			</tr>
			        			<tr>
			        				<td style="vertical-align: middle;">Subsection</td>
			        				<td>
			        					<select readonly class="form-control" v-model="issueObject.selected.subsection_id">
			        						<option value="">--Choose--</option>
			        						<template v-for="subsection in subsectionObject.data">
			        							<option v-bind:value="subsection.id">${ subsection.sub_id } ${ subsection.definition }</option>
			        						</template>
			        					</select>
			        				</td>
			        			</tr>
			        			<tr v-show="issueObject.selected.airport_id != null">
			        				<td style="vertical-align: middle;">Airport</td>
			        				<td>
			        					<select readonly class="form-control" v-model="issueObject.selected.airport_id">
			        						<option value="">--Choose--</option>
			        						<template v-for="airport in airportObject.data">
			        							<option v-bind:value="airport.value">${ airport.label }</option>
			        						</template>
			        					</select>
			        				</td>
			        			</tr>
			        			<tr>
			        				<td style="vertical-align: middle;">Data Problem Confirmation?</td>
			        				<td>
			        					<select readonly class="form-control" v-model="issueObject.selected.data_confirmation_id">
			        						<option value="0">--Choose--</option>
			        						<option value="1">Data Problem</option>
			        						<option value="2">Non-Data Problem</option>
			        					</select>
			        				</td>
			        			</tr>
			        			<tr>
			        				<td style="vertical-align: middle;"><strong>Description</strong></td>
			        				<td>
			        					<textarea readonly class="form-control" v-model="issueObject.selected.description"></textarea>
			        				</td>
			        			</tr>
			        		</tbody>
			        	</table>
			        </template>
			        <template v-else>
			        	<table class="table">
			        		<tbody>
			        			<tr>
			        				<td style="vertical-align: middle;" width="15%"><strong>Number</strong></td>
			        				<td>${ issueObject.selected.no }</td>
			        			</tr>
			        			<tr>
			        				<td style="vertical-align: middle;">Reporter</td>
			        				<td>${ issueObject.selected.reporter.name }</td>
			        			</tr>
			        			<tr>
			        				<td style="vertical-align: middle;"><strong>Title</strong></td>
			        				<td><input type="text" v-model="issueObject.selected.title" class="form-control"></td>
			        			</tr>
			        			<tr>
			        				<td style="vertical-align: middle;">Topic</td>
			        				<td>
			        					<select class="form-control" v-model="issueObject.selected.topic_id">
			        						<option value="">--Choose--</option>
			        						<template v-for="topic in topicObject.data">
			        							<option v-bind:value="topic.id">${ topic.title }</option>
			        						</template>
			        					</select>
			        				</td>
			        			</tr>
			        			<tr>
			        				<td style="vertical-align: middle;">Priority</td>
			        				<td>
			        					<select class="form-control" v-model="issueObject.selected.priority_id">
			        						<option value="">--Choose--</option>
			        						<template v-for="priority in priorityObject.data">
			        							<option v-bind:value="priority.id">${ priority.name }</option>
			        						</template>
			        					</select>
			        				</td>
			        			</tr>
			        			<tr>
			        				<td style="vertical-align: middle;">Section</td>
			        				<td>
			        					<select class="form-control" v-model="issueObject.selected.section_id">
			        						<option value="">--Choose--</option>
			        						<template v-for="section in sectionObject.data">
			        							<option v-bind:value="section.id">${ section.sub_id } ${ section.definition }</option>
			        						</template>
			        					</select>
			        				</td>
			        			</tr>
			        			<tr>
			        				<td style="vertical-align: middle;">Subsection</td>
			        				<td>
			        					<select class="form-control" v-model="issueObject.selected.subsection_id">
			        						<option value="">--Choose--</option>
			        						<template v-for="subsection in subsectionObject.data">
			        							<option v-bind:value="subsection.id">${ subsection.sub_id } ${ subsection.definition }</option>
			        						</template>
			        					</select>
			        				</td>
			        			</tr>
			        			<tr v-show="issueObject.selected.airport_id != null">
			        				<td style="vertical-align: middle;">Airport</td>
			        				<td>
			        					<select class="form-control" v-model="issueObject.selected.airport_id">
			        						<option value="">--Choose--</option>
			        						<template v-for="airport in airportObject.data">
			        							<option v-bind:value="airport.value">${ airport.label }</option>
			        						</template>
			        					</select>
			        				</td>
			        			</tr>
			        			<tr v-show="isAdmin">
			        				<td style="vertical-align: middle;">Data Problem Confirmation?</td>
			        				<td>
			        					<select class="form-control" v-model="issueObject.selected.data_confirmation_id">
			        						<option value="">--Choose--</option>
			        						<option value="1">Data Problem</option>
			        						<option value="2">Non-Data Problem</option>
			        					</select>
			        				</td>
			        			</tr>
			        			<tr>
			        				<td style="vertical-align: middle;"><strong>Description</strong></td>
			        				<td>
			        					<textarea class="form-control" v-model="issueObject.selected.description"></textarea>
			        					<br>
			        					<a class="btn btn-success" v-on:click="update()">Update</a>
			        					<a v-show="issueObject.selected.progress_user_id == 2 && isAdmin" class="btn btn-primary text-white" v-on:click="solved()">Mark as Solved</a>
			        				</td>
			        			</tr>
			        		</tbody>
			        	</table>
			        </template>
		        </div><!-- .card -->
		        <div class="card card-stretch col-md-12">
		        	<br>
		        	<h5>Attachments</h5>
		        	<table class="table">
		        		<tbody>
		        			<template v-for="attachment in attachmentObject.data">
		        				<tr>
		        					<td><a v-bind:href="attachment.attachment_url" target="_blank">${ attachment.attachment}</a></td>
		        					<td><a href="#" v-on:click="removeAttachment(attachment.id)">Remove</a></td>
		        				</tr>
		        			</template>
		        			<tr>
		        				<td colspan="2">Add New File <input type="file" v-on:change="upload($event)"></td>
		        			</tr>
		        		</tbody>
		        	</table>
		        </div>
		        <div class="card card-stretch col-md-12">
		        	<br />
		        	<h5>Histories</h5>
		        	<table class="table">
		        		<thead>
		        			<th>Time</th>
		        			<th>Log</th>
		        		</thead>
		        		<tbody>
		        			<template v-for="history in historyObject.data">
		        				<tr>
		        					<td>${ history.created_at }</td>
		        					<td v-html="history.log"></td>
		        				</tr>
		        			</template>
		        		</tbody>
		        	</table>
		        </div>
		      </div>
			</div>
		</div>
	</div>      
</template>
<template v-if="state == 'faq-manager'">
	<div class="container-fluid">
	  <div class="nk-content-inner">
	    <div class="nk-content-body">
	      <div class="nk-block-head nk-block-head-sm">
	        <div class="nk-block-between">
	          <div class="nk-block-head-content">
	            <h3 class="nk-block-title page-title">Frequently Asked Question (FAQ) Manager</h3>
	          </div>
	        </div>
				</div>    
	    </div>
			<div class="nk-block">
        <div class="card card-stretch col-md-12">
        	<br><h5>Data</h5>
        	<table class="table">
        		<tbody>
        			<tr>
        				<td style="vertical-align: top;"><strong>Title</strong></td>
        				<td><input type="text" v-model="faqObjectParam.title" class="form-control"></td>
        			</tr>
        			<tr>
        				<td style="vertical-align: top;">Description</td>
        				<td>
        					<textarea class="form-control" v-model="faqObjectParam.description"></textarea>
        				</td>
        			</tr>
        			<tr>
        				<td style="vertical-align: top;">Language</td>
        				<td>
	        				<select class="form-control" v-model="faqObjectParam.language">
	        					<option value="">--Choose--</option>
	        					<option value="1">Indonesia</option>
	        					<option value="2">English</option>
	        				</select>
	        				<br>
	        				<a class="btn btn-success" v-on:click="createFaq()">Create</a>
	        			</td>
        			</tr>
        		</tbody>
        	</table>
        </div>
      </div>
	  </div>
	</div>
</template>
@endsection
@section('footer_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ url('js/vue.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.4.2/Sortable.min.js"></script>
<script>
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
			isAdmin: <?php echo json_encode(Auth::user()->isAdmin()); ?>,
			state: 'list', //list, detail
			substate: null,
			topicObject: {
				data: [],
				loaded: false,
			},
			priorityObject: {
				data: [],
				loaded: false,
			},
			partObject: {
				data: [],
				loaded: false,
			},
			sectionObject: {
				data: [],
				loaded: false,
			},
			attachmentObject: {
				data: [],
				loaded: false
			},
			issueObject: {
				data: [], 
				loaded: false,
				selected: {
					data_confirmation_id: 0,
				},
			},
			historyObject: {
				data: [],
				loaded: false,
			},
			airportObject: {
				data: [],
				loaded: false,
			},
			faqObject: {
				data: [],
				loaded: false,
			},
			subsectionObject: {
				data: [],
				loaded: false,
			},
			issueObjectParam: {
				section_id: '',
				priority_id: '',
				subsection_id: '',
				topic_id: '',
				airport_id: '',
			},
			faqObjectParam: {
				language: '',
			},
			summaryObject: {
				data: [],
				nondata: {
					total: 0,
					label: 'Non Data',
				},	
				loaded: false,
			},
			firstOpenDetailPage: true,
			summaryMonth: '',
			summaryYear: '',
		}
	},
	mounted() {
		this.loadIssueObject()
		this.loadTopicObject()
		this.loadPriorityList()
		this.loadSectionObject()
		this.loadFaqObject()
		this.loadAirportObject()
	},
	watch: {
		'issueObject.selected': function(data) {
			this.loadHistoryObject(data.id)
		},
		'issueObjectParam.section_id': function(data) {
			this.loadSubsectionObject(data)
		},
		'issueObject.selected.section_id': function(data) {
			this.loadSubsectionObject(data)
		},
		state(data) {
			if (data == 'list') {
				this.loadIssueObject()
				this.firstOpenDetailPage = true
			}

			if (data == 'summary') {
				this.loadSummaryObject()
			}
		}
	},
	methods: {
		setState(state) {
			this.state = state
		},

		submitSummary() {
			XHR('GET', 'ajax/feedback/summary?year='+this.summaryYear+'&month='+this.summaryMonth, null, response => {
				this.summaryObject = {
					data: response.data.data, nondata: response.data.nondata, loaded: true
				}
			})
		},

		loadSummaryObject() {
			XHR('GET', 'ajax/feedback/summary', null, response => {
				this.summaryObject = {
					data: response.data.data, nondata: response.data.nondata, loaded: true
				}
			})
		},

		solved() {
			var answer = confirm('Are you sure want to mark this issue as solved?')

			if (answer) {
				XHR('POST', 'ajax/issue/solve', {issue: this.issueObject.selected.id}, response => {
					if (response.status == 'success') {
						toastr.success('Issue marked as Solved!')
						window.location.reload()
					}
				})
			}
		},

		loadAirportObject() {
			XHR('GET', 'ajax/airport/list', null, response => {
				console.log(response.data)
				response.data.forEach(airport => {
					this.airportObject.data.push(airport)
				})

				this.airportObject.loaded = true
			})
		},

		loadFaqObject() {
			XHR('GET', 'ajax/faq/list', null, response => {
				response.data.forEach(faq => {
					faq.editable = false;

					this.faqObject.data.push(faq)
				})
			})
		},	

		update() {
			XHR('POST', 'ajax/issue/'+this.issueObject.selected.id+'/update', this.issueObject.selected, response => {
				if (response.status == 'success') {
					toastr.success('Feedback Updated!')

					window.location.reload()
				}
			})
		},

		create() {
			XHR('POST', 'ajax/issue/create', this.issueObjectParam, response => {
				if (response.status == 'success') {
					toastr.success('Feedback Created!')
					window.location.reload()
				}
			})
		},

		upload(e) {
			var form = new FormData()
			form.append('file', e.target.files[0])
			form.append('issue_id', this.issueObject.selected.id)

			XHR('POST', 'ajax/attachment/upload', form, response => {
					if (response.status == 'success') {
						this.loadAttachmentObject()
					}
			})
		},

		createFaq() {
			XHR('POST', 'ajax/faq/create', this.faqObjectParam, response => {
				if (response.status == 'fail') {
					toastr.error('Please Fill All Required Fields!')
				}

				if (response.status == 'success') {
					toastr.success('FAQ Created!')

					this.faqObjectParam = {
						language: ''
					}
				}
			})
		},

		saveFaq(faq) {
			XHR('POST', 'ajax/faq/'+faq.id+'/update', faq, response => {
				toastr.success('FAQ Updated!')
				faq.editable = false
			})
		},

		loadHistoryObject(data) {
			XHR('GET', 'ajax/history/'+data+'/list', null, response => {
				this.historyObject = {data: response.data, loaded: true}
			})
		},

		loadAttachmentObject() {
			XHR('GET', 'ajax/attachment/'+this.issueObject.selected.id+'/list', null, response => {
				this.attachmentObject.data = response.data
				this.attachmentObject.loaded = true
			})
		},

		loadSectionObject(data) {
			XHR('GET', 'ajax/section/list', null, response => {
				this.sectionObject.data = response.data
				this.sectionObject.loaded = true
			})
		},

		loadPartObject() {
			XHR('GET', 'ajax/part/list', null, response => {
				this.partObject.data = response.data
				this.partObject.loaded = true
			})
		},

		loadPriorityList() {
			XHR('GET', 'ajax/priority/list', null, response => {
				this.priorityObject.data = response.data
				this.priorityObject.loaded = true
			})
		},

		loadTopicObject() {
			XHR('GET', 'ajax/topic/list', null, response => {
				this.topicObject.data = response.data
				this.topicObject.loaded = true
			})
		},

		loadSelectedIssue(issue) {
			if (issue.data_confirmation_id == null) {
				issue.data_confirmation_id = ''
			}
			this.state = 'detail'
			this.issueObject.selected = issue
			this.loadAttachmentObject()
		},

		loadSubsectionObject(section) {
			console.log(this.firstOpenDetailPage)
			this.issueObjectParam.subsection_id = ''
			if (!this.firstOpenDetailPage) {
				this.issueObject.selected.subsection_id = ''
			}			
			this.firstOpenDetailPage = false
			XHR('GET', 'ajax/subsection/'+section+'/list', null, response => {
				this.subsectionObject.data = response.data
				this.subsectionObject.loaded = true
			})
		},

		loadIssueObject() {
			XHR('GET', 'ajax/issue/list', null, (response) => {
				this.issueObject.data = response.data
				this.issueObject.loaded = true
			})
		},

		removeAttachment(id) {
			XHR('POST', 'ajax/attachment/remove', {id: id}, response => {
				if (response.status == 'success') {
					this.loadAttachmentObject()
				}
			})
		}
	}
})
</script>
@endsection