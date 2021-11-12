@extends('layouts.app')

@section('template_title')
    Welcome {{ Auth::user()->name }}
@endsection

@section('head')
<style type="text/css">
    .nk-wrap-nosidebar .nk-content {
        min-height:unset!important;
    }
</style>
@endsection

@section('content') 
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-msg">
                    <div class="nk-msg-aside">
                        <div class="nk-msg-nav">
                            <ul class="nk-msg-menu">
                                <li class="nk-msg-menu-item active"><a href="">Notifications</a></li>
                                <li class="nk-msg-menu-item ml-auto"><a href="" class="search-toggle toggle-search" data-target="search"><em class="icon ni ni-search"></em></a></li>
                            </ul><!-- .nk-msg-menu -->
                            <div class="search-wrap" data-search="search">
                                <div class="search-content">
                                    <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                    <input type="text" class="form-control border-transparent form-focus-none" placeholder="Search by user or message">
                                    <button class="search-submit btn btn-icon"><em class="icon ni ni-search"></em></button>
                                </div>
                            </div><!-- .search-wrap -->
                        </div><!-- .nk-msg-nav -->
                        <div class="nk-msg-list" data-simplebar>
                            @foreach(auth()->user()->notifications as $notification)
                            <?php 
                                $showbyId = isset($showbyId) ? $showbyId : '';
                                $unread = (empty($notification->read_at)) ? ' is-unread ' : '';  
                            ?>
                            @if($showbyId!='')
                                <?php 
                                    $curr   = ($showbyId->id==$notification->id) ? ' current ' : '';
                                ?>
                                <div class="nk-msg-item {{ $unread.$curr }}" data-msg-id="{{$notification->id}}">
                            @else
                                <div class="nk-msg-item {{ $unread }}" data-msg-id="{{$notification->id}}">
                            @endif
                                <div class="nk-msg-media user-avatar">
                                    <span>{{ acronym( $notification->data['from'] ) }}</span>
                                </div>
                                <div class="nk-msg-info">
                                    <div class="nk-msg-from">
                                        <div class="nk-msg-sender">
                                            <div class="name">{{ $notification->data['from'] }} </div>
                                            <div class="lable-tag dot bg-pink"></div>
                                        </div>
                                        <div class="nk-msg-meta">
                                            <div class="attchment"><em class="icon ni ni-clip-h"></em></div>
                                            <div class="date">{{ \Carbon\Carbon::parse($notification->created_at)->format('d M') }}</div>
                                        </div>
                                    </div>
                                    <div class="nk-msg-context">
                                        <div class="nk-msg-text">
                                            <h6 class="title"><a href="/inbox.show/{{$notification->id}}">{{ $notification->data['title'] }}</a></h6>
                                            <p>{!! $notification->data['descriptions'] !!}</p>
                                        </div>
                                        <div class="nk-msg-lables">
                                            <div class="asterisk"><a href="#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a></div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .nk-msg-item -->
                            @endforeach 
                        </div><!-- .nk-msg-list -->
                    </div><!-- .nk-msg-aside -->
                        <div class="nk-msg-body bg-white profile-shown">
                            @php $showbyId= isset($showbyId) ? $showbyId : ''; @endphp
                        @if($showbyId!='') 
                            <div class="nk-msg-head">
                                <h4 class="title d-none d-lg-block">{{ $notification->data['title'] }}</h4>
                                <div class="nk-msg-head-meta">
                                    <div class="d-none d-lg-block">
                                        <ul class="nk-msg-tags">
                                            <li><span class="label-tag"><em class="icon ni ni-flag-fill"></em> <span>{{ $notification->data['from'] }}</span></span></li>
                                        </ul>
                                    </div>
                                    <div class="d-lg-none"><a href="#" class="btn btn-icon btn-trigger nk-msg-hide ml-n1"><em class="icon ni ni-arrow-left"></em></a></div>
                                    <ul class="nk-msg-actions">
                                        <li><a href="" class="btn btn-dim btn-sm btn-outline-light"><em class="icon ni ni-check"></em><span>Mark as Read</span></a></li> 
                                        <li class="d-lg-none"><a href="#" class="btn btn-icon btn-sm btn-white btn-light profile-toggle"><em class="icon ni ni-info-i"></em></a></li> 
                                    </ul>
                                </div>
                                <a href="#"class="nk-msg-profile-toggle profile-toggle active"><em class="icon ni ni-arrow-left"></em></a>
                            </div><!-- .nk-msg-head -->

                            <div class="nk-msg-reply nk-reply" data-simplebar>
                                <div class="nk-msg-head py-4 d-lg-none">
                                    <h4 class="title">{{ $notification->data['title'] }}</h4>
                                    <ul class="nk-msg-tags">
                                        <li><span class="label-tag"><em class="icon ni ni-flag-fill"></em> <span>{{ $notification->data['descriptions'] }}</span></span></li>
                                    </ul>
                                </div>
                                <div class="nk-reply-item">
                                    <div class="nk-reply-header">
                                        <div class="user-card">
                                            <div class="user-avatar sm bg-blue">
                                                <span>{{ acronym( $notification->data['from'] ) }}</span>
                                            </div>
                                            <div class="user-name">{{ $notification->data['from'] }}</div>
                                        </div>
                                        <div class="date-time">{{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y') }}</div>
                                    </div>
                                    <div class="nk-reply-body">
                                        <div class="nk-reply-entry entry">
                                            <p>{!! $notification->data['body'] !!}</p> 
                                            <p>{{ $notification->data['thanks'] }}</p>
                                        </div> 
                                    </div>
                                </div><!-- .nk-reply-item --> 
                                 
                            </div><!-- .nk-reply -->
                        @else 
                            <div class="nk-msg-head">
                                <h4 class="title d-none d-lg-block">No notification selected</h4>
                                <div class="nk-msg-head-meta">
                                    <div class="d-none d-lg-block">
                                        <ul class="nk-msg-tags">
                                            <li><span class="label-tag"><em class="icon ni ni-flag-fill"></em> <span>iWISH INDONESIA</span></span></li>
                                        </ul>
                                    </div>
                                    <div class="d-lg-none"><a href="#" class="btn btn-icon btn-trigger nk-msg-hide ml-n1"><em class="icon ni ni-arrow-left"></em></a></div>
                                    <ul class="nk-msg-actions">
                                        <li><a href="" class="btn btn-dim btn-sm btn-outline-light"><em class="icon ni ni-check"></em><span>Mark as Read</span></a></li> 
                                        <li class="d-lg-none"><a href="#" class="btn btn-icon btn-sm btn-white btn-light profile-toggle"><em class="icon ni ni-info-i"></em></a></li> 
                                    </ul>
                                </div>
                                <a href="#"class="nk-msg-profile-toggle profile-toggle active"><em class="icon ni ni-arrow-left"></em></a>
                            </div><!-- .nk-msg-head -->
                        @endif
                            <div class="nk-msg-profile visible" data-simplebar>
                                <div class="card">
                                    <div class="card-inner-group">
                                        <div class="card-inner">
                                            <div class="user-card user-card-s2 mb-2">
                                                <div class="user-avatar md bg-primary">
                                                    <span>{{ acronym (Auth::user()->name) }}</span>
                                                </div>
                                                <div class="user-info">
                                                    <h5>{{ Auth::user()->name }}</h5>
                                                    <span class="sub-text">{{ Auth::user()->roles[0]->name }}</span>
                                                </div>
                                                <div class="user-card-menu dropdown">
                                                    <a href="#" class="btn btn-icon btn-sm btn-trigger dropdown-toggle" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="link-list-opt no-bdr">
                                                            <li><a href="{{ url('/profile/'.Auth::user()->email) }}"><em class="icon ni ni-eye"></em><span>View Profile</span></a></li> 
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row text-center g-1">
                                                <!-- <div class="col-4">
                                                    <div class="profile-stats">
                                                        <span class="amount">23</span>
                                                        <span class="sub-text">Total Order</span>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="profile-stats">
                                                        <span class="amount">20</span>
                                                        <span class="sub-text">Complete</span>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="profile-stats">
                                                        <span class="amount">3</span>
                                                        <span class="sub-text">Progress</span>
                                                    </div>
                                                </div> -->
                                            </div>
                                        </div><!-- .card-inner -->
                                        <div class="card-inner">
                                            <div class="aside-wg">
                                                <h6 class="overline-title-alt mb-2">User Information</h6>
                                                <ul class="user-contacts">
                                                    <li>
                                                        <em class="icon ni ni-mail"></em><span>{{Auth::user()->email}}</span>
                                                    </li>
                                                    <li>
                                                        <em class="icon ni ni-call"></em><span>{{Auth::user()->user_phone}}</span>
                                                    </li>
                                                    <li>
                                                        <em class="icon ni ni-map-pin"></em><span>{{ getCountry(Auth::user()->user_country) }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        <!--     <div class="aside-wg">
                                                <h6 class="overline-title-alt mb-2">Additional</h6>
                                                <div class="row gx-1 gy-3">
                                                    <div class="col-6">
                                                        <span class="sub-text">Ref ID: </span>
                                                        <span>TID-049583</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <span class="sub-text">Requested:</span>
                                                        <span>Abu Bin Ishtiak</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <span class="sub-text">Status:</span>
                                                        <span class="lead-text text-success">Open</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <span class="sub-text">Last Reply:</span>
                                                        <span>Abu Bin Ishtiak</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="aside-wg">
                                                <h6 class="overline-title-alt mb-2">Assigned Account</h6>
                                                <ul class="align-center g-2">
                                                    <li>
                                                        <div class="user-avatar bg-purple">
                                                            <span>IH</span>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="user-avatar bg-pink">
                                                            <span>ST</span>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="user-avatar bg-gray">
                                                            <span>SI</span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div> -->
                                        </div><!-- .card-inner -->
                                    </div>
                                </div>
                            </div><!-- .nk-msg-profile -->
                        </div><!-- .nk-msg-body -->
                     
                </div><!-- .nk-msg -->
            </div>
        </div>
    <!-- JavaScript -->
@endsection
@section('footer_scripts')
<script src="{{ asset ('/template/assets/js/apps/messages.js?ver=1.9.2') }}"></script>
<script type="text/javascript">
</script> 