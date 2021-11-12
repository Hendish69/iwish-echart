<!-- main header @s -->
<div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ml-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-brand d-xl-none">
                <a href="/" class="logo-link">
                    <!-- <img class="logo-light logo-img" src="{{ url('/template/images/logo.png') }}" srcset="{{ url('/template/images/logo2x.png 2x') }}" alt="logo">
                    <img class="logo-dark logo-img" src="{{ url('/template/images/logo-dark.png') }}" srcset="{{ url('/template/images/logo-dark2x.png 2x') }}" alt="logo-dark">
                     -->
                     <img class="logo-light logo-img" src="{{ url('/template/images/last_logo.png') }}" srcset="{{ url('/template/images/last_logo.png') }}" alt="logo">
                    <img class="logo-dark logo-img" src="{{ url('/template/images/last_logo.png') }}" srcset="{{ url('/template/images/last_logo.png') }}" alt="logo-dark">
                </a>
            </div><!-- .nk-header-brand -->
            <div class="nk-header-search ml-3 ml-xl-0">
                    <!-- <em class="icon ni ni-search"></em> -->
                    <!-- <a href="/" class="logo-link logo-link w-100 text-center ml-5 pl-5 d-none" id="mid_logo"> -->
                  <!--   if(request()->route()->uri!='home')
                    <a href="/" class="logo-link logo-link w-100 text-center ml-5 pl-5" id="mid_logo">
                    <img class="logo-dark logo-img h-100" src="{{ url('/template/images/last_logo.png') }}" srcset="{{ url('/template/images/last_logo.png') }}" alt="logo-dark" style="max-height:60px!important;margin-left: 260px!important">
                    </a>
                    endif -->
                    <!-- <input type="text" class="form-control border-transparent form-focus-none" placeholder="Search anything"> -->
            </div><!-- .nk-header-news --> 
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                     <li class="dropdown chats-dropdown hide-mb-xs">
                        <div  id="btnFullscreen" class="dropdown-toggle nk-quick-nav-icon" data-toggle="tooltip" data-placement="bottom" title="Fullscreen">
                            <div class="icon-status icon-status-na"><em class="icon ni ni-maximize"></em></div>
                        </div>
                    </li>
             <!--        <li class="dropdown chats-dropdown hide-mb-xs">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
                            <div class="icon-status icon-status-na"><em class="icon ni ni-comments"></em></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
                            <div class="dropdown-head">
                                <span class="sub-title nk-dropdown-title">Recent Chats</span>
                                <a href="#">Setting</a>
                            </div>
                            <div class="dropdown-body">
                                <ul class="chat-list">
                                    <li class="chat-item">
                                        <a class="chat-link" href="html/apps-chats.html">
                                            <div class="chat-media user-avatar">
                                                <span>IH</span>
                                                <span class="status dot dot-lg dot-gray"></span>
                                            </div>
                                            <div class="chat-info">
                                                <div class="chat-from">
                                                    <div class="name">Iliash Hossain</div>
                                                    <span class="time">Now</span>
                                                </div>
                                                <div class="chat-context">
                                                    <div class="text">You: Please confrim if you got my last messages.</div>
                                                    <div class="status delivered">
                                                        <em class="icon ni ni-check-circle-fill"></em>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="chat-item is-unread">
                                        <a class="chat-link" href="html/apps-chats.html">
                                            <div class="chat-media user-avatar bg-pink">
                                                <span>AB</span>
                                                <span class="status dot dot-lg dot-success"></span>
                                            </div>
                                            <div class="chat-info">
                                                <div class="chat-from">
                                                    <div class="name">Abu Bin Ishtiyak</div>
                                                    <span class="time">4:49 AM</span>
                                                </div>
                                                <div class="chat-context">
                                                    <div class="text">Hi, I am Ishtiyak, can you help me with this problem ?</div>
                                                    <div class="status unread">
                                                        <em class="icon ni ni-bullet-fill"></em>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="chat-item">
                                        <a class="chat-link" href="html/apps-chats.html">
                                            <div class="chat-media user-avatar">
                                                <img src="/" alt="">
                                            </div>
                                            <div class="chat-info">
                                                <div class="chat-from">
                                                    <div class="name">George Philips</div>
                                                    <span class="time">6 Apr</span>
                                                </div>
                                                <div class="chat-context">
                                                    <div class="text">Have you seens the claim from Rose?</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="chat-item">
                                        <a class="chat-link" href="html/apps-chats.html">
                                            <div class="chat-media user-avatar user-avatar-multiple">
                                                <div class="user-avatar">
                                                    <img src="/" alt="">
                                                </div>
                                                <div class="user-avatar">
                                                    <span>AB</span>
                                                </div>
                                            </div>
                                            <div class="chat-info">
                                                <div class="chat-from">
                                                    <div class="name">Softnio Group</div>
                                                    <span class="time">27 Mar</span>
                                                </div>
                                                <div class="chat-context">
                                                    <div class="text">You: I just bought a new computer but i am having some problem</div>
                                                    <div class="status sent">
                                                        <em class="icon ni ni-check-circle"></em>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="chat-item">
                                        <a class="chat-link" href="html/apps-chats.html">
                                            <div class="chat-media user-avatar">
                                                <img src="/" alt="">
                                                <span class="status dot dot-lg dot-success"></span>
                                            </div>
                                            <div class="chat-info">
                                                <div class="chat-from">
                                                    <div class="name">Larry Hughes</div>
                                                    <span class="time">3 Apr</span>
                                                </div>
                                                <div class="chat-context">
                                                    <div class="text">Hi Frank! How is you doing?</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="chat-item">
                                        <a class="chat-link" href="html/apps-chats.html">
                                            <div class="chat-media user-avatar bg-purple">
                                                <span>TW</span>
                                            </div>
                                            <div class="chat-info">
                                                <div class="chat-from">
                                                    <div class="name">Tammy Wilson</div>
                                                    <span class="time">27 Mar</span>
                                                </div>
                                                <div class="chat-context">
                                                    <div class="text">You: I just bought a new computer but i am having some problem</div>
                                                    <div class="status sent">
                                                        <em class="icon ni ni-check-circle"></em>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="dropdown-foot center">
                                <a href="html/apps-chats.html">View All</a>
                            </div>
                        </div>
                    </li> -->
                    <li class="dropdown notification-dropdown">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
                            <div class="icon-status icon-status-info"><em class="icon ni ni-bell"></em></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
                            <div class="dropdown-head">
                                <span class="sub-title nk-dropdown-title">Notifications ({{ auth()->user()->unreadNotifications->count() }})</span>
                                <!-- <a href="#"> </a> -->
                            </div>
                            <div class="dropdown-body">
                                <div class="nk-notification">
                                    @foreach(auth()->user()->unreadNotifications as $notification)
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon">
                                            <em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">{!! $notification->data['descriptions'] !!} <span>{!! $notification->data['title'] !!} </span></div>
                                            <div class="nk-notification-time">
                                                @php 
                                                    $time = DiffDate(\Carbon\Carbon::now()->toDateTimeString(),$notification->created_at);
                                                    $ret = '';
                                                    if($time->d > 0)
                                                        $ret .= $time->d. ' days ';
                                                    if($time->h > 0)
                                                        $ret .= $time->h. ' hours ';
                                                    if($time->i > 0)
                                                        $ret .= $time->i. ' min ';
                                                    if($time->s > 0)
                                                        $ret .= $time->s. ' sec';
                                                    echo $ret . ' ago';
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach 
                                    
                                </div><!-- .nk-notification -->
                            </div><!-- .nk-dropdown-body -->
                            <div class="dropdown-foot center">
                                <a href="/inbox.messages">View All</a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle mr-n1" data-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar user-avatari sm">
                                    <em class="icon ni ni-user-alt"></em>
                                </div>
                                <div class="user-info d-none d-xl-block">
                                    <div class="user-status user-status-unverified">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                    <div class="user-name dropdown-indicator">{{ Auth::user()->name }}</div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar user-avatari">
                                        {{-- <span>{{ Auth::user()->name }}</span> --}}
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{ Auth::user()->first_name }}</span>
                                        <span class="sub-text">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list {{ Request::is('profile/'.Auth::user()->email, 'profile/'.Auth::user()->email . '/edit') ? 'active' : null }}">
                                    <li><a href="{{ url('/profile/'.Auth::user()->email) }}"><em class="icon ni ni-user"></em><span>{!! trans('titles.profile') !!}</span></a></li>
                                    {{-- <li><a href="html/user-profile-setting.html"><em class="icon ni ni-setting-alt"></em><span>Account Setting</span></a></li>
                                    <li><a href="html/user-profile-activity.html"><em class="icon ni ni-activity-alt"></em><span>Login Activity</span></a></li> --}}
                                </ul>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                            <em class="icon ni ni-signout"></em><span>{{ __('Logout') }}</span>
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>
<!-- main header @e -->
