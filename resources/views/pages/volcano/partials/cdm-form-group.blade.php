@extends('layouts.app')

@section('template_title')
CDM GROUP
@endsection

@section('head')
@endsection

@section('content')

<div class="container-fluid">
  <div class="nk-content-inner">
    <div class="nk-content-body">

      <!-- HEADER -->
      <div class="nk-block-head nk-block-head-sm">
          <!-- <div class="nk-block-between"> -->
        <!-- <div class="nk-block-head-content">
          <h3 class="nk-block-title page-title">New Group</h3>
        </div> -->
        <div class="row-fluid">
            <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
        </div>
        <br/>
      </div>

              <!-- CONTENT  -->

      <div class="card">
        <div class="card-inner">
          <form class="gy-3" id="frm_cdm">
            @csrf
            <input type="hidden" name="action" id="action" value="">
            <input type="hidden" name="cdm_id" id="cdm_id" value="{{ $cdm_id }}">

                   <!-- 1 FIELD -->
            <div class="row g-3 align-center">
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-label" for="site-name">Volcano *:</label>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <div class="form-control-wrap">
                    <select id="va_no" name="va_no" class="form-control" required>
                      <option value="">Choose..</option>
                      {!! $list_va !!}
                    </select>
                  </div>
                </div>
              </div>
            </div>

                <!-- 1 FIELD -->
            <div class="row g-3 align-center">
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-label" for="site-name">Status *:</label>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <div class="form-control-wrap">
                    <select id="va_status" name="va_status" class="form-control" required>
                      <option value="">Choose..</option>
                      {!! $list_va_status !!}              
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- 1 FIELD -->
            <div class="row g-3 align-center">
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-label" for="site-name">Description :</label>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <div class="form-control-wrap">
                    <input id="cdm_desc" name="cdm_desc" value="{{ $cdm_desc }}" class="form-control" required>
                  </div>
                </div>
              </div>
            </div>

            <div class="card m-2">
              <div class="card-inner">
                <h4>Participants</h4>
                <br>
                <div class="row col-8">
                  <button onclick="addUser()" type="button" class="btn btn-success"><em class="icon ni ni-user-add"></em> Add Participant</button>
                  &nbsp; 
                  <button type="submit" class="btn btn-primary btn_process" name="btn_process">Process</button>
                  <!-- <button type="button" class="btn btn-primary btn_process" id="btn_process" onclick="$('#frm_cdm').submit();">Process</button> -->
                </div>
                <br>
                <table class="table">
                  <thead class="thead-inverse bg-dark text-white">
                    <tr>
                      <th>User</th>
                      <th>Designation</th>
                      <th>Contact</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="DOC_ROW">
                    {!! $table_content !!}
                  </tbody>
                </table>
              </div>

              <input type="hidden" name="user_sum" id="user_sum" value="{{ $no }}">
              <div class="form-group row m-2">
                <div class="col-2">
                  <!-- <button type="button"  onclick="$('#frm_cdm').submit();" class="btn btn-primary btn-block btn_process" id="btn_process1">Process</button> -->
                  <button type="submit" class="btn btn-primary btn-block btn_process" name="btn_process">Process</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection
@section('footer_scripts') 
<script> 

// $('.chosen').chosen();
$("#frm_cdm").submit(function(e){
  Swal.fire({
            title: 'Are you sure?',
            text: "You are going to save CDM Group!",
            icon: 'info',
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "Confirm",
            closeOnConfirm: false
          }).then((result) => {
              if (result.value) {
                $('.btn_process').attr('disabled', 'disabled');
                
                var parseData = $("#frm_cdm").serializeArray();
                $.post("/cdm/editgrp/",parseData,function(va_no){
                  Swal.fire({
                    title: "CDM Group",
                    text: "CDM Group saved!",
                    type: "success",
                    confirmButtonColor: "#8CD4F5",
                    confirmButtonText: "OK",
                    closeOnConfirm: false
                  }).then((result) => {
                      document.location = '/cdmlogdetail/'+va_no;
                  });
                  
                });
              
              }
          });
        e.preventDefault();
  });
  

function addUser(){
  var id = parseInt($('#user_sum').val())+parseInt(1);
  $('<tr id="user_row'+id+'">'+
    '<td><select class="form-control" name="user_id[]" id="user_id'+id+'" onchange="setUser(\'user_id'+id+'\', \''+id+'\')" style="width:250px;"><option value="">Choose..</option>{!! listUser() !!}</select></td>'+
    '<td id="designation'+id+'">-</td>'+
    '<td id="contact'+id+'">-</td>'+
    '<td><button onclick="removeUser(\''+id+'\')" type="button" class="btn btn-danger"><em class="icon ni ni-trash-alt"></em></button></td>'+
    '</tr>').prependTo('#DOC_ROW');
  $('#user_sum').val(id.toString());
  // $('#user_id'+id).chosen();
}

function setUser(elem, id){
  // console.log(elem);
  // let idd = elem.parseInt();
  var contact = $('#'+elem+' option:selected').attr('contact');
  var designation = $('#'+elem+' option:selected').attr('designation');
  $('#designation'+id).html(designation.toString());
  $('#contact'+id).html(contact.toString());
}

function removeUser(id){
  $('#user_row'+id).remove();
}
function backtolist(){
    history.back();
}
</script>
@endsection