<div class="card card-full">
    <div class="card-inner">
        <div class="card-title-group">
            <div class="card-title">
                <h6 class="title">List Room</h6>
            </div>
        </div>
    </div>
    <div class="nk-tb-list mt-n2">
        <div class="nk-tb-item nk-tb-head">
            <div class="nk-tb-col"><span>#.</span></div>
            <div class="nk-tb-col tb-col-sm"><span>Room</span></div>
            <div class="nk-tb-col tb-col-sm"><span>Volcano ID</span></div>
            <div class="nk-tb-col tb-col-md"><span>Creator</span></div>
            <div class="nk-tb-col"><span>Date</span></div>
            <div class="nk-tb-col"><span class="d-none d-sm-inline">Join</span></div>
            <div class="nk-tb-col"><span class="d-none d-sm-inline">Delete</span></div>
        </div>
        @foreach($rooms as $key=>$room)
        <div class="nk-tb-item">
            <div class="nk-tb-col">
                <span class="tb-lead"><a href="#">{{++$key}}</a></span>
            </div>
            <div class="nk-tb-col tb-col-sm">
                <span class="tb-sub">{{$room->name}}</span>
            </div>
            <div class="nk-tb-col tb-col-sm">
                <span class="tb-sub">{{$room->va_no}}</span>
            </div>

            <div class="nk-tb-col tb-col-md">
                <div class="user-card">
                  @if(is_object($room->user->profile))
                  <div class="nk-store-statistics sm bg-purple-dim">
                    <span><img class="icon" src="
                        @if ($room->user->profile->avatar_status == 1) 
                            @if(file_exists($room->user->profile->avatar))  
                                {{ $room->user->profile->avatar }}
                            @else 
                                {{ Gravatar::get($room->user->email) }}
                            @endif         
                        @else 
                            {{ Gravatar::get($room->user->email) }} 
                        @endif
                        " alt="{{ $room->user->name }}"></span>
                </div>
                @endif
                <div class="user-name">
                    <span class="tb-lead pl-2">{{$room->user->email}}</span>
                </div>
            </div>
        </div>
        <div class="nk-tb-col tb-col-md">
            <span class="tb-sub">{{$room->created_at}}</span>
        </div>
        <div class="nk-tb-col">
            <span class="badge badge-dot badge-dot-xs badge-success">
                <!-- <a href="{{--url('/room/'.$room->id)--}}" target="_blank" >Join</a> -->
                <span onclick="showRoomURLx('{{ $room->va_no }}')" style="cursor: pointer;">Join</span>
            </span>

        </div>
        <div class="nk-tb-col">
            <span class="badge badge-dot badge-dot-xs badge-danger"><a href="{{url('/room/delete/'.$room->id)}}" class="">Delete</a></span>
        </div>
    </div>
    @endforeach
</div>
{{$rooms->links()}}
</div><!-- .card -->
