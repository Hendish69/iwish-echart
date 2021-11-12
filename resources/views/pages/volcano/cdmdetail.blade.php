@extends('layouts.app')

@section('template_title')
    CDM
@endsection

@section('head')
<style type="text/css">
     .modal.modal-fullscreen .modal-dialog {
      width: 100vw;
      height: 100vh;
      margin: 0;
      padding: 0;
      max-width: none; 
    }

    .modal.modal-fullscreen .modal-content {
      height: auto;
      height: 100vh;
      border-radius: 0;
      border: none; 
    }

    .modal.modal-fullscreen .modal-body {
      overflow-y: auto; 
    }

</style>
<link href="{{ asset('template/assets/css/v-modal.css') }}" rel="stylesheet" >
@endsection

@section('content')
    <div class="container-fluid">
        <div class="nk-wrap">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4>Volcano Colaborative Decision Making (CDM)</h4>
                    </div><!-- .nk-block-head-content -->
                </div><!-- .nk-block-between -->
            </div>
            <div class="modal-dialog-lg" role="document" id="showinfo" style="visibility: hidden">
                
            </div>
            <div id="detailinfo" style="visibility: visible">
                <div class="row-fluid">
                    <a onclick="backtolist()" id="backid" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                </div>
                <div class="panel-heading">
                    <div class="demo-wrapper" style="padding:20px 0px 0px 20px !important;">
                        <h3 class="panel-title" id="volname"></h3>
                        <br>
                        <div class="row">
                            <div class="col-2"><b>Status</b></div>
                            <p class="col-10" id="volwarna"></p>
                            <!-- <div class="col-10"><span id="volwarna">volstatus</span></div> -->
                        </div>
                        <div class="row">
                            <div class="col-2"><b>Volcano Location</b></div>
                            <div class="col-10" id="volloc"></div>
                        </div>
                        <div class="row">
                            <div class="col-2"><b>Summit Elevation</b></div>
                            <div class="col-10" id="volelev"></div>
                        </div>
                        <div class="row">
                            <div class="col-2"><b>Area</b></div>
                            <div class="col-10" id="volarea"></div>
                        </div>
                        <br>
                        <div class="form-group row">
                            <div class="row-fluid">
                                <form method="GET" action="" id="frm_edCdmGrp">
                                    <input type="hidden" name="va_no" value="{{ request()->segment(count(request()->segments())) }}">
                                    <button type="submit" class="btn btn-dim btn-info" ><i class="icon ni ni-edit"></i> Edit Group</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-tabs" id="tabMenu">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabItem5"><em class="icon ni ni-book-fill"></em><span>Log</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabItem6"><em class="icon ni ni-chat-fill"></em><span>Chat</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabItem7"><em class="icon ni ni-video-fill"></em><span>Teleconference</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabItem8"><em class="icon ni ni-report-fill"></em><span>Report</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabItem5">
                            <form class="form-inline col-sm-12">
                                <p class="btn my-2 my-sm-0">Type</p>
                                <select selected="selected" @click="selecttype()" class="form-control" id="logtype">
                                        <option value="1"> Log Data  </option>
                                        <option value="2"> Action Scheme  </option>
                                        <option value="3"> Volcex Sheet  </option>
                                </select>
                            </form>
                            <div class="row mt-1">
                                <template v-if="islog">
                                    <div class="col-md-12">
                                        <table class="datatable-init table table-bordered table-hover" id="table-content">
                                            <thead class="thead-dark">
                                                <tr align="center">
                                                    <th>Time</th>
                                                    <th>Response Time</th>
                                                    <th>Stakeholder</th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody id="loglist">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </template>
                                <template v-if="isscheme">
                                    <div class="col-md-12" v-html="schemelist" id="schemelist">
                                    </div>
                                </template>
                                <template v-if="isvolcex">
                                    <div class="col-md-12" id="volcexlist">
                                    </div>
                                </template>
                            </div>
                        </div>
                    <div class="tab-pane" id="tabItem6">
                        <form class="form-inline col-sm-12">
                            <p class="btn my-2 my-sm-0">Chat Type</p>
                            <select selected="selected" class="form-control" id="chattype" v-model="chatType">

                            </select>
                        </form>
                        <div class="nk-content mt-3">
                            <div class="container-fluid">
                                <div class="nk-content-inner">
                                    <div class="nk-content-body">
                                        <div class="nk-chat">
                                            <div class="nk-chat-body" >
                                                <div class="nk-chat-editor">
                                                    <div class="nk-chat-editor-form">
                                                        <div class="form-control-wrap">
                                                            <textarea class="form-control form-control-simple no-resize" rows="1" id="default-textarea" placeholder="Type your message..." v-model="chatbox"
                                                            @keydown.enter.exact.prevent
                                                            @keyup.enter.exact="postChat()"
                                                            @keydown.enter.shift.exact="newline"
                                                            ></textarea>
                                                        </div>
                                                    </div>
                                                        <ul class="nk-chat-editor-tools g-2">
                                                            <li>
                                                                <a v-on:click="openAttachment()" class="btn btn-sm btn-icon btn-trigger text-primary"><em class="icon ni ni-clip"></em></a>
                                                                <input style="display:none;" type="file" id="attachment-box" v-on:change="handleAttachment()">
                                                            </li>
                                                            <li>
                                                                <button v-on:click="postChat()" class="btn btn-round btn-primary btn-icon"><em class="icon ni ni-send-alt"></em></button>
                                                            </li>
                                                        </ul>
                                                    </div><!-- .nk-chat-editor -->
                                                    <div class="nk-chat-panel">
                                                        <template v-if="chatObject.loaded">
                                                            <template v-for="chat in chatObject.data">
                                                            <template v-if="me == chat.user?.id">
                                                                <template v-if="chat.chat_file_path">
                                                                <div class="chat is-me">
                                                                    <div class="chat-content">
                                                                    <div class="chat-bubbles">
                                                                        <div class="chat-bubble">
                                                                        <span style="cursor:pointer;" v-bind:data-popup="chat.chat_file_path" v-on:click="openWind(chat.chat_file_path)" >${ chat.chat_file_name }</span>
                                                                        </div>
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                                </template>
                                                                <div class="chat is-me">
                                                                <div class="chat-content">
                                                                    <div class="chat-bubbles">
                                                                    <div class="chat-bubble">
                                                                    <div class="chat-msg" v-html="chat.chat_content">
                                                                    </div>
                                                                        
                                                                    </div>
                                                                    </div>
                                                                    <ul class="chat-meta">
                                                                    <li>${ chat.user?.first_name }</li>
                                                                    <li>${ new Date(chat.chat_date).toLocaleTimeString() }</li>
                                                                    </ul>
                                                                    <ul class="chat-meta">
                                                                    <li>${ chat.user?.organization?.org_short_en }</li>
                                                                    </ul>
                                                                </div>
                                                                </div>
                                                            </template>
                                                            <template v-else>
                                                            <template v-if="chat.chat_file_path">
                                                                <div class="chat is-you">
                                                                <div class="chat-content">
                                                                    <div class="chat-bubbles">
                                                                    <div class="chat-bubble">
                                                                        <span style="cursor:pointer;" v-bind:data-popup="chat.chat_file_path" v-on:click="openWind(chat.chat_file_path)" class="wmBox">${ chat.chat_file_name }</span>    
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                            </template>
                                                            <div class="chat is-you">
                                                                <div class="chat-avatar">
                                                                    <div class="nk-msg-media">
                                                                        <template v-if="chat.user?.profile?.avatar != null">
                                                                            <img v-bind:src="chat.user?.profile?.avatar_url" width="50px" alt="" style="vertical-align: top;">
                                                                        </template>
                                                                        <template v-else>
                                                                            <img src="/images/avatar_placeholder.png" width="50px" alt="" style="vertical-align: top;">
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                                <div class="chat-content">
                                                                    <div class="chat-bubbles">
                                                                    <div class="chat-bubble">
                                                                    <div class="chat-msg" v-html="chat.chat_content">
                                                                        </div>
                                                                    
                                                                    </div>
                                                                    </div>
                                                                    <ul class="chat-meta">
                                                                    <li>${ chat.user?.first_name }</li>
                                                                    <li>${new Date(chat.chat_date)}</li>
                                                                    </ul>
                                                                    <ul class="chat-meta">
                                                                    <li>${ chat.user?.organization?.org_short_en }</li>
                                                                    </ul>
                                                                </div>
                                                                </div>
                                                            </template>
                                                            </template>
                                                            <a v-show="showLoadMore" v-on:click="loadMore()" class="btn btn-success btn-block text-white">More</a>
                                                        </template>
                                                        </div><!-- .nk-chat-panel -->
                                                </div><!-- .nk-chat-aside -->
                                                    <div class="nk-chat-aside">
                                                        <div class="nk-chat-aside-head">
                                                            <h6 class="title overline-title-alt">Stakeholder</h6>
                                                        </div>
                                                    <div class="nk-chat-aside-body" data-simplebar>
                                                        <div class="nk-chat-list" id="userlist">
                                                        <template v-for="participant in participantObject.data">
                                                            <ul class="chat-list">
                                                                <li class="chat-item">
                                                                <a class="chat-link chat-open" href="#">
                                                                    <div class="chat-media bg-purple">
                                                                        <div class="nk-msg-media">
                                                                            <template v-if="participant?.profile?.avatar != null">
                                                                                <img v-bind:src="participant?.profile?.avatar_url" alt="">
                                                                            </template>
                                                                            <template v-else>
                                                                                <img src="/images/avatar_placeholder.png" alt="">
                                                                            </template>
                                                                        </div>
                                                                    </div>
                                                                            <div class="chat-info">
                                                                                <div class="chat-from">
                                                                                    <div class="name">${ participant.first_name } ${ participant.last_name }</div>
                                                                                    <div class="time"></div>
                                                                                </div>
                                                                                <div class="chat-context">
                                                                                    <div class="text">
                                                                                        <span>${ participant.email }</span><br>
                                                                                        <span>${ participant.user_position }</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </template>
                                                        </div><!-- .nk-chat-list -->
                                                    </div>
                                                </div><!-- .nk-chat-body -->
                                            </div><!-- .nk-chat -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabItem7">
                        <!-- <button class="btn btn-danger" style="" id="stop-room" onclick="location.reload()"><i class="fa fa-video-camera"></i> Stop Conference</button> -->
                        
                            @include('pages.volcano.partials.tab7-content')             
                            <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#room_mdl">
                              <em class="icon ni ni-grid-plus-fill"></em><span>Create Room</span>
                            </button> 

                            @include('modals.modal-telecon')
                        </div>
                        <div class="tab-pane" id="tabItem8">
                        <div class="row">
                                <div class="col-md-6">
                                    <strong>Stakeholder</strong>
                                    <br>
                                    <select selected="selected" onclick="selectholder()" class="form-control" id="stackholder">
                                        @foreach($stkholder as $s=>$stk)
                                            <option value="{{$s}}">{{$stk->group_name}}</option>
                                        @endforeach
                                    </select>
                                    <strong>Message Type</strong>
                                    <select selected="selected" class="form-control" id="messtype">
                                       
                                    </select>
                                    <br>
                                    <button onclick="createreport()" class="btn btn-dim btn-success"><i class="icon ni ni-file-pdf"></i> Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
@section('footer_scripts') 
@yield('ext_script')
<script src="https://iwishtelecon.id/external_api.js"></script> 
<script src="{{ asset('template/assets/js/v-modal.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('#tabMenu a[href="#{{ old('tab') }}"]').tab('show'); 
    // $('.image-link').magnificPopup({ type:'iframe' });
    $.wmBox();
});
</script>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script type="text/javascript">
var volcano=@json($volcano);
var cdmlog=@json($cdmlog);
var cdmuser=@json($cdmuser);
var cdmchat=@json($cdmchat);
var ashtam=@json($ashtam);
var vona=@json($vona);
var tbl=@json($tbl);
var stack=@json($stkholder);
var stakeholder=@json($stakeholder);
var timex=@json($time);
// console.log('cdmid : ',cdmlog,volcano);
var cdmid='';
if (cdmlog.length >0){
    cdmid=cdmlog[0].cdm_id;
}
Vue.config.silent = true
const APP_URL = "<?php echo env('APP_URL') ?>"
const CSRF = "{{ csrf_token() }}";
const USER = "{{ Auth::user()->id }}"
var AppVue = new Vue({
    el: '#app',
    delimiters: ['${', '}'],
    data() {
        return {
            me: USER,
            ws: null,
            chatObject: {
                loaded: false,
                data: [],
            },
            participantObject: {
                loaded: false,
                data: [],
            },
            chatbox: '',
            chatType: 0,
            attachment: null,
            showLoadMore: true,
            islog:true,
            isscheme:false,
            isvolcex:false,
            schemelist:'',

        }
    },
    mounted() {
    this.initiateSocket()
        this.handleParticipantObject()
        this.handleChatObject()
        // setInterval(() => {
        //     this.refreshChatList()
        // }, 2000)
    },
methods: {
    initiateSocket() {
    this.ws = new WebSocket('<?php echo str_replace('http','ws',env('APP_URL')).":".env('APP_WEBSOCKET_PORT') ?>')
        this.ws.onopen = event => {
            this.ws.send(JSON.stringify({
                type: 'webauthorize',
                data: "<?php echo Auth::user()->name; ?>"
            }))
        }

        this.ws.onmessage = event => {
            var payload = JSON.parse(event.data)

            if (payload.type == 'chat') {
                this.chatObject.data.unshift(payload.data)
            }
        }
    },

handleAttachment() {
    var file = $('#attachment-box').prop('files')
    // console.log(file[0])
    this.attachment = file[0]
},

openAttachment() {
    $("#attachment-box").click()
},
openWind(link){
	vModal(link);
},
handleParticipantObject() {
    $.ajax({
        type: 'GET',
        url: APP_URL+'/cdm/participants/'+<?php echo $volcano[0]->va_no; ?>,
        success: response => {
            this.participantObject.loaded = true
            response.data.forEach(participant => {
                if (null === participant.masteruser) {
                return
                }

                this.participantObject.data.push(participant.masteruser)
            })
            }
        })
    },

    handleChatObject() {
        $.ajax({
            type: 'GET',
            url: APP_URL+'/cdm/chat/'+<?php echo $volcano[0]->va_no; ?>+'?all=true&token='+CSRF,
            success: response => {
            this.chatObject.loaded = true
            response.data.forEach(chat => {
                // console.log(chat)
                var chatpath = chat.chat_file_path.split('/');
                chat.chat_file_path =pathpop() + '/upload/chat/' + chatpath[chatpath.length -1];
                chat.chat_file_path =pathpop() + '/upload/chat/' + chatpath[chatpath.length -1];
		let uri = chat.chat_file_path ;
                chat.chat_file_path = uri.replace('chat/chat', 'chat'); 
                this.chatObject.data.push(chat); 
            })
            }
        })
    },

    loadMore() {
      var lastchatid = 1

      if (this.chatObject.data.length !== 0) {
        lastchatid = this.chatObject.data[this.chatObject.data.length-1].chat_id
      }

      $.ajax({
        type: 'GET',
        url: APP_URL+'/cdm/chat/older/'+<?php echo $volcano[0]->va_no; ?>+'/'+lastchatid,
        success: response => {
          if (response.data.length < 25) {
            this.showLoadMore = false
          }

          response.data.forEach(chat => {
                let uri = chat.chat_file_path ;
                chat.chat_file_path = uri.replace('chat/chat', 'chat');
            this.chatObject.data.push(chat);
          });
          $('.image-link').magnificPopup({ type:'iframe' });
        }
      })
    },  

    refreshChatList() {
        var lastchatid = 1

        if (this.chatObject.data.length !== 0) {
            lastchatid = this.chatObject.data[0].chat_id
        }

        $.ajax({
            type: 'GET',
            url: APP_URL+'/cdm/chat/refresh/'+<?php echo $volcano[0]->va_no; ?>+'/'+lastchatid,
            success: response => {
            response.data.forEach(chat => {
                if (chat.user_id == USER) {
                return
                }

                this.chatObject.data.unshift(chat)
            })
            }
        })
    },

    postChat() {
        var self = this

        var form = new FormData()

        form.append('_token', CSRF)
        form.append('type', this.chatType)
        form.append('text', this.chatbox)

        if (null !== this.attachment) {
            form.append('file', this.attachment)
        }

        $.ajax({
            type: 'POST',
            url: APP_URL+'/cdm/chat/'+<?php echo $volcano[0]->va_no; ?>,
            data: form,
            processData: false,
            contentType: false,
            success: response => {
                if (response.status == 'fail') {
                    alert('The message box cannot be empty')
                }

                if (response.status == 'error') {
                    alert('You are not a member of this CDM')
                }

                if (response.status == 'success') {
                    self.chatbox = ''
                    self.attachment = null
                    self.chatObject.data.unshift(response.data)
                    document.getElementById('attachment-box').value = ''
                    this.ws.send(JSON.stringify({
                      type: 'chat',
                      data: response.data.chat_id
                    }))
                }
            }
        })
        },
        selecttype(){
            // console.log($("#logtype").val());
            this.islog=false;
            this.isscheme=false;
            this.isvolcex=false;
            switch ($("#logtype").val()) {
                case "1":
                    this.islog=true;
                    listlog(cdmlog);
                    break;
                case "2":
                    this.isscheme=true;
                    this.listscheme();
                    break;
                case "3":
                    this.isvolcex=true;
                    break;
            }
        },
        listscheme(){
            var issued=timex[0].min_issued.substr(0,13)+':00:00';
            var dt = new Date(timex[0].min_issued);
            var hrs=dt.getHours();
            var min_issued=issued;//substr($fsql->get('MIN_ISSUED'),0,13).':00:00';
            var max_issued=timex[0].max_issued.substr(0,13)+':00:00';

            
            this.schemelist='<table class="table table-bordered table-hover" id="table-content">'+
                '<thead class="thead-dark">'+
                    '<tr align="center">'+
                        '<th style="width:20px">Time</th>';
                        for( let i=0; i<10; i++){
                            var hhh=hrs + i;
                            if (hhh >= 24){
                                hhh -= 24;
                            }
                            this.schemelist +='<th>'+ hhh.toString().padStart(2, '0')+'00</th>';
                        }
                        this.schemelist += '</tr>'+
                '</thead>'+
                '<tbody id="schemelistdetail">';
                stakeholder.forEach(l=>{
                        // console.log(l)
                        this.schemelist+= '<tr class="nk-tb-item"><td>' + l.group_name + '</td></tr>'
                        // $("#schemelistdetail").append(hasil);
                    
                })
                    
                this.schemelist+= '</tbody>'+
            '</table>';
            // $("#schemelist").html(hasil);
            // console.log(hasil)
        }

    }
})
$('#showinfo').hide();

function showimage(id){
    // console.log(id)
}
var v = volcano[0];
// console.log(volcano,tbl)
        tbl.forEach(v=>{
            hhs='<option value="'+  v.reff_code + '">' + v.reff_name + '</option>'
            $("#chattype").append(hhs);                           
            // console.log(v);
        });

        cdmuser.forEach(v=>{
            // console.log(v.user[0])
            var usrname='';chatdate='';photouser='';umail='';upos='';
            if (v.user.length >0){
                
                if (v.user[0].first_name =='' || v.user[0].first_name == null){
                    usrname=v.user[0].name;
                }else{
                    usrname=v.user[0].first_name + ' ' + v.user[0].last_name
                }
                photouser=getphotouser(v.user[0].user_photo);
                umail=v.user[0].email;
                upos=v.user[0].user_position;
           
            // if (v.chat.length > 0){
            //     chatdate = DateFormat(new Date(v.chat[0].chat_date));
            // }


            hsl='<ul class="chat-list">'+
                '<li class="chat-item">'+
                    '<a class="chat-link chat-open" href="#">'+
                        '<div class="chat-media user-avatar bg-purple">'+
                        '<div class="nk-msg-media user-avatar">'+
                '<img src="' + photouser + '" alt="">'+
            '</div>'+
                        '</div>'+
                        '<div class="chat-info">'+
                            '<div class="chat-from">'+
                                '<div class="name">' + usrname + '</div>'+
                                // '<div class="time">' + chatdate + '</div>'+
                            '</div>'+
                            '<div class="chat-context">'+
                                '<div class="text">'+
                                    '<span>' +umail + '</span><br>'+
                                    '<span>' + upos + '</span>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</a>'+
                '</li>'+
            '</ul>'
            //$("#userlist").append(hsl);
        }
            // console.log(v);
            // routes.push(v);
        });
    // listlog(cdmlog);

    // $("#volname").html(v.va_name + ' - ' + v.va_no);
    // $("#roomname").val(v.va_name + ' - ' + v.va_no);
    // var clat=SetWgs(v.va_lat_deg,v.va_lat_min,v.va_lat_sec,v.va_lat_ns);
    // var clon=SetWgs(v.va_lon_deg,v.va_lon_min,v.va_lon_sec,v.va_lon_ew)


    listlog(cdmlog);
    $("#volname").html(v.va_name + ' - ' + v.va_no);
    $("#roomname").val(v.va_name + ' - ' + v.va_no);
    var clat=SetWgs(v.va_lat_deg,v.va_lat_min,v.va_lat_sec,v.va_lat_ns);
    var clon=SetWgs(v.va_lon_deg,v.va_lon_min,v.va_lon_sec,v.va_lon_ew)
    // console.log(clat,clon,v.va_lon,v.va_lat)
    var cr=SetCoordinatebyDecimal(clon,clat)
    // console.dir(cr)
    $("#volwarna").html(': ' + getwarna(v.va_status));
    $("#volloc").html(': ' +cr.WGS[1] + ' ' + cr.WGS[0]);
    $("#volelev").html(': ' +v.va_summit_elevm + ' M');
    $("#volarea").html(': ' +v.va_subregion + ', ' + v.va_state);

function backtolist(){
    history.back();
}
function getwarna(status){
    // console.log(status,'status');
    var hsl='';
    switch(status){
                case '4':
                case 'RED':
                    this.volstatus='RED'
                    hsl='<strong style="color:#CC0505; font-weight:bolder;">RED</strong>'
                    break;
                case '3':
                case 'ORANGE':
                    this.volstatus='ORANGE'
                    hsl='<strong style="color:#FF9100; font-weight:bolder;">ORANGE</strong>'
                    break;
                case '2':
                case 'YELLOW':
                    this.volstatus='YELLOW'
                    hsl='<strong style="color:#e7d107; font-weight:bolder;">YELLOW</strong>'
                    break;
                case '1':
                case 'GREEN':
                    this.volstatus='GREEN'
                    hsl='<strong style="color:#179638; font-weight:bolder;">GREEN</strong>'
                    break;
            }
            
    return hsl;
}

function cdmlogdetail(log,cdm){
    // console.log('cdm',cdm,'log',log)
    // console.log('volcano',volcano)
    
    var titel='';isi=''; cdmtype ="11";chattype='';
    if (cdm==true){
        cdmtype = cdmlog.find( x => x.cdm_log_id === log ).cdm_type;
    }
    // console.log(cdmtype)
    chattype= tbl.find( x => x.reff_code === cdmtype ).reff_name;
    // this.logdetlist=[];
    // this.isVona=false;
    // this.isAshtam=false;
    // this.isSigmet=false;
    $('#showinfo').empty();
    switch(cdmtype){
        case "9":
            var dataid= cdmlog.find( x => x.cdm_log_id === log ).data_id;
            var nmr=ashtam.find( x => x.ashtam_id === dataid ).ashtam_number.split(' ');
            // console.log(nmr,ashtam)
            titel= 'ASHTAM OF ' + v.va_name + ' VOLCANO' //ashtam.find( x => x.ashtam_id === dataid ).ashtam_number;
            isi= SetHeader(titel,'')+
            '<div class="row"><div class="col-3"><b>Update Time</b></div><div class="col-9">: <b><i>' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_update_time + '</i></b></div></div><div class="row"><div class="col-3"><b>ASHTAM Number</b></div><div class="col-9">: <b><i>' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_number + '</i></b></div></div><hr size="10px">' +
            '<ul>'+
                '<li>' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_number.replace(/(\s+)/g,'') + ' ' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_fir + ' ' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_utc + '</li>' +
                '<li>ASHTAM ' + nmr + '</li>' +
                '<li>A) ' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_fir +'</li>' +
                '<li>B) ' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_utc_issued +'</li>' +
                '<li>C) ' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_volcano + ' ' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_volcano_number +'</li>' +
                '<li>D) ' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_navaid_lon_dms +'</li>' +
                '<li>E) ' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_alert_code + '</li>' +
                '<li>F) ' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_ahve + '</li>' +
                '<li>G) ' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_ash_direction + '</li>' +
                '<li>H) ' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_affected_route + '</li>' +
                '<li>I) ' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_air_space + '</li>' +
                '<li>J) ' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_source + '</li>' +
                '<li>K) ' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_plain_language + '</li>' +
                '<li>RMK : ' + ashtam.find( x => x.ashtam_id === dataid ).ashtam_remarks + '</li>' +
            '</ul>' + SetFooter(v.va_name,volcano.va_no);
            break;
        case "10":
            
            var dataid= cdmlog.find( x => x.cdm_log_id === log ).data_id;
            // console.dir(v);
            titel='VONA - ' + v.va_name + ' ' +  vona.find( x => x.vona_id === dataid ).issued_utc;
            isi= SetHeader(titel,'')+
                '<div class="row">'+
                    '<div class="col-3"><b>Issued</b></div>'+
                    '<div class="col-8">' + vona.find( x => x.vona_id === dataid ).issued_utc + '</div>'+
                '</div>'+
                '<hr size="10px">'+
                '<div class="row">'+
                    '<div class="col-3"><b>Volcano</b></div>'+
                    '<div class="col-8">' + v.va_name + ' (' + v.va_no+ ')</div>'+
                '</div>'+
                '<hr size="10px">'+
                '<div class="row">'+
                    '<div class="col-3"><b>Current Aviation Colour Code</b></div>'+
                    '<div class="col-8">' + getwarna(vona.find( x => x.vona_id === dataid ).cu_code) + '</div>'+
                '</div>'+
                '<hr size="10px">'+
                '<div class="row">'+
                    '<div class="col-3"><b>Previous Aviation Colour Code</b></div>'+
                    '<div class="col-8">' + getwarna(vona.find( x => x.vona_id === dataid ).prev_code) + '</div>'+
                '</div>'+
                '<hr size="10px">'+
                '<div class="row">'+
                    '<div class="col-3"><b>Notice Number</b></div>'+
                    '<div class="col-8">' + vona.find( x => x.vona_id === dataid ).noticenumber+ '</div>'+
                '</div>'+
                '<hr size="10px">'+
                '<div class="row">'+
                    '<div class="col-3"><b>Volcano Location</b></div>'+
                    '<div class="col-8">' + vona.find( x => x.vona_id === dataid ).location+ '</div>'+
                '</div>'+
                '<hr size="10px">'+
                '<div class="row">'+
                    '<div class="col-3"><b>Area</b></div>'+
                    '<div class="col-8">' + v.va_subregion+ '</div>'+
                '</div>'+
                '<hr size="10px">'+
                '<div class="row">'+
                    '<div class="col-3"><b>Summit Elevation</b></div>'+
                    '<div class="col-8">' + v.va_summit_elevm+ ' M</div>'+
                '</div>'+
                '<hr size="10px">'+
                '<div class="row">'+
                    '<div class="col-3"><b>Volcanic Activity Summary</b></div>'+
                    '<div class="col-8">' + vona.find( x => x.vona_id === dataid ).vas+ '</div>'+
                '</div>'+
                '<hr size="10px">'+
                '<div class="row">'+
                    '<div class="col-3"><b>Volcanic Cloud Height</b></div>'+
                    '<div class="col-8">' + vona.find( x => x.vona_id === dataid ).vch_asl+ ' FT</div>'+
                '</div>'+
                '<hr size="10px">'+
                '<div class="row">'+
                    '<div class="col-3"><b>Other Volcanic Cloud Information</b></div>'+
                    '<div class="col-8">' + vona.find( x => x.vona_id === dataid ).vch_other+ '</div>'+
                '</div>'+
                '<hr size="10px">'+
                '<div class="row">'+
                    '<div class="col-3"><b>Remarks</b></div>'+
                    '<div class="col-8">' + vona.find( x => x.vona_id === dataid ).remarks+ '</div>'+
                '</div>'+
                '<hr size="10px">' + SetFooter(v.va_name,v.va_no);
            break;
        default:
            if (cdm==true){
                var chatid = cdmlog.find( x => x.cdm_log_id === log ).chat_id;
                var chatpath = cdmchat.data.find( x => x.chat_id === chatid ).chat_file_path.split('/');
                var pdfLink=pathpop() + '/upload/chat/' + chatpath[chatpath.length -1]
                var chatname = cdmchat.data.find( x => x.chat_id === chatid ).chat_file_name;
                // console.log('chatid',chatid,chatpath,chatname,log);
                titel=cdmlog.find( x => x.cdm_log_id === log ).cdm_stakeholder + ' - ' + chattype;
            }else{
                var chatid = log;
                var chatpath = cdmchat.data.find( x => x.chat_id === chatid ).chat_file_path.split('/');
                var pdfLink=pathpop() + '/upload/chat/' + chatpath[chatpath.length -1]
                var chatname = cdmchat.data.find( x => x.chat_id === chatid ).chat_file_name;
                // console.log('chatid',chatid,chatpath,chatname,pdfLink);
                titel=cdmchat.data.find( x => x.chat_id === log ).user[0].org[0].org_short_en + ' - ' + chattype;
            }
        
        isi= SetHeader(titel,'') +
        '<div id="iframe-wrapper">'+
            '<iframe src=' + pdfLink + ' width="100%" height="600px"></iframe>'+
        '</div>' + SetFooter(volcano.va_name,volcano.va_no);
            break;
    }
    // console.log(isi);
    aboutvol('showinfo');
    aboutvol('detailinfo');
    $("#showinfo").append(isi);

    window.scrollTo(0,0);
}


function showabout(){
    aboutvol('showinfo');
    aboutvol('detailinfo');
}
function getphotouser(photo){
    var fotopath='';photouser='';
    if (photo !== null){
        fotopath = photo.split('/');
        photouser =pathpop() + '/upload/users/' + fotopath[fotopath.length -1]
    }
    return photouser
}
function listlog(log){

    var tgl=log.cdm_date
    log.forEach(l=>{
        if (l.cdmtype !== null){
            
            let idx = tbl.findIndex(x => x.reff_code===l.cdm_type);
            // console.log(idx,l)
            var reffname='';
            if (idx !==-1){
                reffname = tbl[idx].reff_name;
            }
            hasil = '<tr class="nk-tb-item" onclick="cdmlogdetail(' + l.cdm_log_id + ',true)" >'+
            '<td style="cursor:pointer">'+
                '<span> ' + DateFormat(new Date(l.cdm_issued)) + ' ' + new Date(l.cdm_issued).toLocaleTimeString() + '</span></td>'+
            '<td style="cursor:pointer">'+
                '<span>' + ConvertTime(l.cdm_response) + '</span></td>'+
            '<td style="cursor:pointer">'+
                '<span>' + l.cdm_stakeholder + '</span></td>'+
            '<td style="cursor:pointer">'+
                '<span>' + reffname + '</span></td>'+
            '</tr>';
            $("#loglist").append(hasil);
        }
    })
}

function createreport(){
    var sstk= Number($('#messtype').val());
    // console.log(sstk,cdmid)
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/vol/report/'+volcano[0].va_no +'/' + sstk, 'TheWindow',params);
    // vol/report/{cdmid}/{id}
}
// function selectmesstype(){
//     var sstk= Number($('#messtype').val());
//     console.log(sstk)
//     vol/report/{cdmid}/{id}
// }
function selectholder(){
    var sstk= Number($('#stackholder').val());
    var tablelist=[];
    // for (let i=0;i < stack.length;i++){
       
        switch (sstk) {
            case 0:
                tablelist=[0,12,2,6]
                break;
            case 1:
                tablelist=[0,12,2]
                break;
            case 2:
                tablelist=[0,12]
                break;
            case 3:
                tablelist=[0,12,13]
                break;
            case 4:
                tablelist=[0,1,12,2]
                break;
            case 5:
                tablelist=[0,1,12,2]
                break;
            case 6:
                tablelist=[0,12,2]
                break;
            case 7:
                tablelist=[0,12]
                break;
            case 8:
                tablelist=[0,11,12,2,3]
                break;
            case 9:
                tablelist=[0,12,14,4,5,8]
                break;
            case 10:
                tablelist=[0,12,7,9]
                break;
            case 11:
                tablelist=[0,12]
                break;
            case 12:
                tablelist=[0,1,10,11,12,13,14,2,3,4,5,6,7,8,9]
                break;
            case 13:
                tablelist=[12,4,5]
                break
            case 14:
                tablelist=[10,12]
                break
        
            default:
                break;
        }
        $("#messtype").empty();
        tablelist.forEach(tb=>{
          lst='<option value="'+ tbl[tb].reff_code+'">'+ tbl[tb].reff_name+'</option>'
          $("#messtype").append(lst);                              
            // console.log(tbl[tb])
        })
    // }
    // stack.forEach(st=>{
    //     console.log(st)
    // })
   
}
  $("#frm_edCdmGrp").submit(function(e){ 
     let act = APP_URL+"/cdm/editgrp/"+cdmid;
    $(this).attr("action", act);
  });
</script>
@endsection
