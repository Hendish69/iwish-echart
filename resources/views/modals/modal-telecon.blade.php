<style type="text/css">
     .modal.modal-fullscreen .modal-dialog {
      width: 90vw;
      height: 90vh;
      margin: 2.5vh auto;
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
<div class="modal modal-fullscreen" id="myConference">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Iwish Teleconference</h4>
         <!-- <button type="button" class="close" id="capt-room"><em class="icon ni ni-video-fill"></em></button> -->
        <!--  <div onclick="launchFullScreen(document.getElementById('room-box'))" class="dropdown-toggle nk-quick-nav-icon" data-toggle="tooltip" data-placement="bottom" title="Fullscreen">
            <div class="icon-status icon-status-na"><em class="icon ni ni-maximize" alt="Fullscreen"></em></div>
        </div> -->
        <button type="button" class="close" data-dismiss="modal" id="stop-room">&times;</button>
      </div>    
      <!-- Modal body -->
      <div class="modal-body">
        <div id="room-box" style="width: 100%;"></div>
      </div>

      <!-- Modal footer -->
    </div>
  </div>
</div>
<div class="modal" id="room_mdl">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create New Room</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{url('/room/create')}}">
          @csrf
          @if($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach($errors->all() as $error)
              <li>{{$error}}</li>
              @endforeach
            </ul>
          </div>
          @endif
          <div class="form-group">
            <label>Room name</label>
            <input type="text" class="form-control" placeholder="Enter room name" name="name" required id="roomname">
            <input type="text" class="form-control" placeholder="Volcano ID" name="va_no" required value="{{request()->segment(count(request()->segments()))}}">
          </div>
          <button type='submit' class='btn btn-primary'>Create</button>  
        </form>
      </div> 

    </div>
  </div>
</div>
@section('ext_script') 
<script src="https://iwishtelecon.id/external_api.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#tabMenu a[href="#{{ old('tab') }}"]').tab('show')
    });
    function showRoomURLx(roomid) {
    // var roomid = roomid.replace(/<(.|\n)*?>/g, ' ');
    // popupCenter({url: 'https://live.aeross.co.id/'+roomid, title: 'Iwish Teleconference', w: 900, h: 500});  
    // need SSL //
    let hi = $(window).height() - 125;
    // let hi = $(document).height(); 
    $("#myConference").modal({backdrop: 'static', keyboard: false});
    var roomid = roomid.replace(/<(.|\n)*?>/g, ' ');
    // var roomURLsDiv = document.getElementById('room-box');
    // roomURLsDiv.style.display = 'block';
    const domain = 'iwishtelecon.id';
    const options = {
        roomName: roomid,
        width: '100%',
        height: hi,
        noSSL:false,
        parentNode: document.querySelector('#room-box'),
         userInfo: {
            email: '{{Auth::user()->email}}}',
            displayName: '{{Auth::user()->name}}'
        }
    };
    const api = new JitsiMeetExternalAPI(domain, options);
    $('#stop-room').on('click', function() { api.dispose() }); 
    const avatar = '@if (Auth::user()->profile->avatar_status == 1) {{  env("APP_URL").Auth::user()->profile->avatar }} @else {{ "https://www.gravatar.com/avatar?d=mp" }} @endif';
    console.log(avatar);
    api.executeCommand('avatarUrl', avatar);
    // $('#capt-room').on('click', function() { 
    //   api.captureLargeVideoScreenshot().then(data => {
    //       // data is an Object with only one param, dataURL
    //       let img = data.dataURL ; //= "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABQAA..."
    //       let url = img.replace(/^data:image\/[^;]+/, 'data:application/octet-stream');
    //       window.open(url);
    //   }); 
    // });
}

</script>
@endsection