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

                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Dashboards</h6>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item has-sub active">
						<a href="#" class="nk-menu-link nk-menu-toggle">
							<span class="nk-menu-icon"><em class="icon ni ni-airbnb"></em></span>
							<span class="nk-menu-text">Volcano &amp; CDM</span>
						</a>
						<ul class="nk-menu-sub">
							<li class="nk-menu-item">
								<a href="/volcanoes" class="nk-menu-link">
									<span class="nk-menu-text">Volcano</span>
								</a>
							</li>
                        
							<li class="nk-menu-item">
								<a href="/cdm" class="nk-menu-link">
									<span class="nk-menu-text">Colaborative Decision Making (CDM)</span>
								</a>
							</li>

						</ul>
					</li>
                    <li class="nk-menu-item">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-map-pin"></em></span>
							<span class="nk-menu-text">Interaktif</span>
                            <!-- <span class="nk-menu-icon"><em class="icon ni ni-map-pin"></em>Interaktif</span> -->
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="/interaktif/Airport" class="nk-menu-link"><span class="nk-menu-text">Airport</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="#" class="nk-menu-link nk-menu-toggle"><span class="nk-menu-text">Airspace</span></a>
                                <ul class="nk-menu-sub">
                                    <li class="nk-menu-item">
                                        <a href="/interaktif/Airspace" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text">Airspace 2D View</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="/interaktif/Airspace3d" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text">Airspace 3D View</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nk-menu-item">
                                <a href="/interaktif/Enroute" class="nk-menu-link"><span class="nk-menu-text">Enroute</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="/interaktif/Navaid" class="nk-menu-link"><span class="nk-menu-text">Navaid</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="/interaktif/Waypoint" class="nk-menu-link"><span class="nk-menu-text">Waypoint</span></a>
                            </li>

                        </ul>
                    </li>
                    <li class="nk-menu-item">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-live"></em></span>
                            <span class="nk-menu-text">INAVCEC</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="{{ url('/inavcec/dashboard') }}" class="nk-menu-link"><span class="nk-menu-text">Dashboard</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="#" class="nk-menu-link nk-menu-toggle"><span class="nk-menu-text">Report</span></a>
                                <ul class="nk-menu-sub">
                                    <li class="nk-menu-item">
                                        <a href="{{ url('/inavcec/report') }}" class="nk-menu-link" ><span class="nk-menu-text">Periodic</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ url('/inavcec/reportcity') }}" class="nk-menu-link" ><span class="nk-menu-text">City Pair</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ url('/inavcec/reportdomin') }}" class="nk-menu-link" ><span class="nk-menu-text">Dom / Int</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ url('inavcec/predictool') }}" class="nk-menu-link" ><span class="nk-menu-text">Prediction Tool</span></a>
                            </li>
                            @role('admin')
                            <li class="nk-menu-item">
                                <a href="#" class="nk-menu-link nk-menu-toggle"><span class="nk-menu-text">Configuration</span></a>
                                <ul class="nk-menu-sub">
                                    <li class="nk-menu-item"><a href="{{ url('/inavcec/acft') }}" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text">Aircraft</span></a></li>
                                    <li class="nk-menu-item"><a  href="{{ url('/inavcec/airport') }}" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text">Airport</span></a></li>
                                    <li class="nk-menu-item"><a  href="{{ url('/inavcec/depfeat') }}" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text">Departure Features </span></a></li>
                                    <li class="nk-menu-item"><a  href="{{ url('/inavcec/arrfeat') }}" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text">Arrival Features </span></a></li>
                                </ul>
                            </li>
                            @endrole
                        </ul>
                        
                    </li>
                        <!-- <a href="/interaktif" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-map-pin"></em></span>
                            <span class="nk-menu-text">Interaktif</span>
                        </a>
                    </li> -->
                    <li class="nk-menu-item">
                        <a href="{{ url('/care') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-activity"></em></span>
                            <span class="nk-menu-text">Care</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{ url('/postflight') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-report"></em></span>
                            <span class="nk-menu-text">Post Flight Report</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="{{ url('/pib') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-notes-alt"></em></span>
                            <span class="nk-menu-text">PIB</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="/gpsraim" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-aperture"></em></span>
                            <span class="nk-menu-text">GPS RAIM PREDICTool</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    @permission('view.eaip')
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">E-AIP PAGES</h6>
                    </li><!-- .nk-menu-item --> 
                    <li class="nk-menu-item">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-bag"></em></span>
                            <span class="nk-menu-text">e-AIP</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="/aipsubmission/html" class="nk-menu-link"><span class="nk-menu-text">Electronik AIP</span></a>
                            </li>
                            <!-- <li class="nk-menu-item">
                                <a href="/aipsubmission/pdf" class="nk-menu-link"><span class="nk-menu-text">AIP</span></a>
                            </li> -->
                            <!-- <li class="nk-menu-item">
                                <a href="#" class="nk-menu-link nk-menu-toggle"><span class="nk-menu-text">AIP</span></a>
                                <ul class="nk-menu-sub">
                                    <li class="nk-menu-item">
                                        <a href="{{ url('/template/html/pages/auths/auth-login-v2.html') }}" class="nk-menu-link" target="_blank"><span class="nk-menu-text">PART 1 - GENERAL(GEN)</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ url('/template/html/pages/auths/auth-register-v2.html') }}" class="nk-menu-link" target="_blank"><span class="nk-menu-text">PART 2 - ENROUTE(ENR)</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ url('/template/html/pages/auths/auth-reset-v2.html') }}" class="nk-menu-link" target="_blank"><span class="nk-menu-text">PART 3 - AERODROMES(AD)</span></a>
                                    </li>
                                </ul>
                            </li> -->
                            <li class="nk-menu-item">
                                <a href="{{ url('/amdt/aic') }}" class="nk-menu-link"><span class="nk-menu-text">AIC</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ url('/amdt/amdt') }}" class="nk-menu-link"><span class="nk-menu-text">AMDT</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ url('/amdt/supp') }}" class="nk-menu-link"><span class="nk-menu-text">SUPP</span></a>
                            </li>
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    @endpermission 

                    @permission('view.publication')
                    <li class="nk-menu-item">
                        <a href="{{ url('/template/html/index.html') }}" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
                            <span class="nk-menu-text">Publications</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="/aipsubmission/edit" class="nk-menu-link"><span class="nk-menu-text">AIP Submission</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="/DataRequest" class="nk-menu-link"><span class="nk-menu-text">Check Data Request</span></a>
                            </li>
                           
                            <li class="nk-menu-item">
                                <a href="#" class="nk-menu-link nk-menu-toggle"><span class="nk-menu-text">Generate Draft File</span></a>
                                <ul class="nk-menu-sub">
                                    <li class="nk-menu-item">
                                        <a href="/createpdf/request" class="nk-menu-link"><span class="nk-menu-text">Data Request</span></a>
                                    </li>
                                    <!-- <li class="nk-menu-item">
                                        <a href="/createpdf/current" name="hendi" class="nk-menu-link"><span class="nk-menu-text">Data Current</span></a>
                                    </li> -->
                                    <li class="nk-menu-item">
                                        <a href="/createpdf/publication" class="nk-menu-link"><span class="nk-menu-text">Data Publication</span></a>
                                    </li>
                                </ul>
                            </li>
                            
                            <!-- <li class="nk-menu-item">
                                <a href="/sourcenr" class="nk-menu-link"><span class="nk-menu-text">Publication Nr</span></a>
                            </li> -->
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    @endpermission
                    
                    @permission('view.aixm')
                    <li class="nk-menu-item">
                        <a href="{{ url('/createaixm') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-html5"></em></span>
                            <span class="nk-menu-text">AIXM 5.1</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    @endpermission 
                    @permission('view.echart')
                    <li class="nk-menu-item">
                        <a href="{{ url('/template/html/index.html') }}" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-chart-up"></em></span>
                            <span class="nk-menu-text">e-Chart</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="/listairport/adc" class="nk-menu-link"><span class="nk-menu-text">ADC</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="/listairport/aoc" class="nk-menu-link"><span class="nk-menu-text">AOC</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="/listairport/iac" class="nk-menu-link"><span class="nk-menu-text">IAC</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="/listairport/sid" class="nk-menu-link"><span class="nk-menu-text">SID</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="/listairport/star" class="nk-menu-link"><span class="nk-menu-text">STAR</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="/terminalwaypoint" class="nk-menu-link"><span class="nk-menu-text">Terminal Waypoint</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="/listairport/holding" class="nk-menu-link"><span class="nk-menu-text">Holding</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="/msa" class="nk-menu-link"><span class="nk-menu-text">MSA</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="/listairport/chartprop" class="nk-menu-link"><span class="nk-menu-text">Chart Properties</span></a>
                            </li>
                            <!-- <li class="nk-menu-item">
                                <a href="/listairport/frame" class="nk-menu-link"><span class="nk-menu-text">Frame Properties</span></a>
                            </li>
                              -->
                             <li class="nk-menu-item">
                                <a href="/vfr_planning" class="nk-menu-link"><span class="nk-menu-text">VFR Planning</span></a>
                            </li>
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    @endpermission
                    @permission('view.customer.page')
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">CUSTOMER PAGES</h6>
                    </li><!-- .nk-menu-item --> 
                    <li class="nk-menu-item">
                        <a href="{{ url('/template/html/index.html') }}" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-user-circle"></em></span>
                            <span class="nk-menu-text">Data Customer</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="/aipsubmission/html" class="nk-menu-link"><span class="nk-menu-text">Detail Data</span></a>
                            </li>
                          
                            <li class="nk-menu-item">
                                <a href="{{ url('/amdt/aic') }}" class="nk-menu-link"><span class="nk-menu-text">Update Profil</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ url('/amdt/amdt') }}" class="nk-menu-link"><span class="nk-menu-text">Ubah Password</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ url('/amdt/supp') }}" class="nk-menu-link"><span class="nk-menu-text">Update Data Revocery</span></a>
                            </li>
                             <li class="nk-menu-item">
                                <a href="{{ url('/amdt/supp') }}" class="nk-menu-link"><span class="nk-menu-text">Billing Information</span></a>
                            </li>
                        </ul>
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