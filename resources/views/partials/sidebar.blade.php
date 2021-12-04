<div class="nk-sidebar nk-sidebar-fixed is-light " data-content="sidebarMenu" id="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="/" class="logo-link nk-sidebar-logo">
                <!-- <img class="logo-light logo-img" src="{{ url('/template/images/logo.png') }}" srcset="{{ url('/template/images/logo2x.png 2x') }}" alt="logo">
                <img class="logo-dark logo-img" src="{{ url('/template/images/logo-dark.png') }}" srcset="{{ url('/template/images/logo-dark2x.png 2x') }}" alt="logo-dark"> -->
                <!-- <img class="logo-light logo-img" src="{{ url('/template/images/last_logo_trim.png') }}" srcset="{{ url('/template/images/last_logo_trim.png') }} 2x" alt="logo">
                <img class="logo-dark logo-img" src="{{ url('/template/images/last_logo_trim.png') }}" srcset="{{ url('/template/images/last_logo_trim.png') }} 2x" alt="logo-dark"> -->
                <img class="logo-dark logo-img h-100" src="{{ url('/template/images/last_logo.png') }}" srcset="{{ url('/template/images/last_logo.png') }}" alt="logo-dark">
                <img class="logo-small logo-img logo-img-small" src="{{ url('template/images/last_logo_trim.png') }}" srcset="{{ url('template/images/last_logo_trim.png') }} 2x" alt="logo-small">

            </a>
        </div>
        <div class="nk-menu-trigger mr-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div>
    </div><!-- .nk-sidebar-element -->
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
  
                    @permission('view.echart')
                     <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">e-CHART</h6>
                    </li><!-- .nk-menu-heading -->
                    <li class="nk-menu-item">
                        <a href="/listairport/adc" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-dashboard-fill"></em></span>
                            <span class="nk-menu-text">ADC</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="/listairport/aoc" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-navigate"></em></span>
                            <span class="nk-menu-text">AOC</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="/listairport/iac" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-note-add"></em></span>
                            <span class="nk-menu-text">IAC</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="/listairport/sid" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-notes"></em></span>
                            <span class="nk-menu-text">SID</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="/listairport/star" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-star-round"></em></span>
                            <span class="nk-menu-text">STAR</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="/vfr_planning" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-notes-alt"></em></span>
                            <span class="nk-menu-text">VFR Planning</span>
                        </a>
                    </li>
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">supporting data</h6>
                    </li><!-- .nk-menu-heading -->
                    <li class="nk-menu-item">
                        <a href="/terminalwaypoint" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-wallet-out"></em></span>
                            <span class="nk-menu-text">Terminal Waypoint</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="/listairport/holding" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-swap-alt"></em></span>
                            <span class="nk-menu-text">Holding</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="/msa" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-meter"></em></span>
                            <span class="nk-menu-text">MSA</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="/listairport/chartprop" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-growth"></em></span>
                            <span class="nk-menu-text">Chart Properties</span>
                        </a>
                    </li>
                     
                     
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Data history</h6>
                    </li><!-- .nk-menu-heading -->
                    <li class="nk-menu-item">
                        <a href="/DataRequest" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-update"></em></span>
                            <span class="nk-menu-text">Data Updating</span>
                        </a>
                    </li>

                    <li class="nk-menu-item">
                        <a href="/datahistory" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-search"></em></span>
                            <span class="nk-menu-text">Data Tracking</span>
                        </a>
                    </li>

                    @endpermission
                    
                    @role('admin')
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">ADMINISTRATOR</h6>
                    </li><!-- .nk-menu-heading -->
                     
                    <li class="nk-menu-item">
                        <a href="{{ url('/pia') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-book"></em></span>
                            <span class="nk-menu-text">PIA Address</span>
                        </a>
                    </li>
                    
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                            <span class="nk-menu-text">{!! trans('titles.adminSideMenu') !!}</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item {{ (Request::is('roles') || Request::is('permissions')) ? 'active' : null }}">
                                <a href="{{ route('laravelroles::roles.index') }}" class="nk-menu-link"><span class="nk-menu-text">{!! trans('titles.laravelroles') !!}</span></a>
                            </li>
                            <li class="nk-menu-item {{ Request::is('users', 'users/' . Auth::user()->id, 'users/' . Auth::user()->id . '/edit') ? 'active' : null }}">
                                <a href="{{ url('/users') }}" class="nk-menu-link"><span class="nk-menu-text">{!! trans('titles.adminUserList') !!}</span></a>
                            </li>
                            <li class="nk-menu-item {{ Request::is('users/create') ? 'active' : null }}">
                                <a href="{{ url('/users/create') }}" class="nk-menu-link"><span class="nk-menu-text">{!! trans('titles.adminNewUser') !!}</span></a>
                            </li>
                            <!-- <li class="nk-menu-item {{ Request::is('active-users') ? 'active' : null }}">
                                <a href="{{ url('/active-users') }}" class="nk-menu-link"><span class="nk-menu-text">{!! trans('titles.activeUsers') !!}</span></a>
                            </li> -->
                            <li class="nk-menu-item {{ Request::is('activity') ? 'active' : null }}">
                                <a href="{{ url('/activity') }}" class="nk-menu-link"><span class="nk-menu-text">Logs Activity</span></a>
                            </li>
                            <li class="nk-menu-item {{ Request::is('blocker') ? 'active' : null }}">
                                <a href="{{ route('laravelblocker::blocker.index') }}" class="nk-menu-link"><span class="nk-menu-text">{!! trans('titles.laravelBlocker') !!}</span></a>
                            </li>
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    @endrole
                </ul><!-- .nk-menu -->
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
</div> 