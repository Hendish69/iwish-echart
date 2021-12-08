<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', App::getLocale()) }}" class="js">
    <head>
        <base href="../">
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
        <link rel="shortcut icon" src="{{ url('template/images/favicon.ico') }}">
        {{-- CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@hasSection('template_title')@yield('template_title') | @endif {{ config('app.name', Lang::get('titles.app')) }}</title>
        <meta name="description" content="">
        <meta name="author" content="Heru Purnomo">
        <link rel="shortcut icon" href="{{ url('template/images/favicon.png') }}">

        {{-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --}}
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        {{-- Fonts --}}
        {{-- yield('template_linked_fonts') --}}

        {{-- Styles --}}
        {{-- <link href="{{ mix('/css/app.css') }}" rel="stylesheet"> --}}

        <link href="{{ asset('template/assets/css/dashlite.css') }}" rel="stylesheet" >
        <link href="{{ asset('template/assets/css/theme.css') }}" id="skin-default" rel="stylesheet" >

        @yield('template_linked_css')

        <style type="text/css">
            body { min-height: calc(100vh + 1px); }
            @yield('template_fastload_css')

            @if (Auth::User() && (Auth::User()->profile) && (Auth::User()->profile->avatar_status == 0))
                .user-avatari {
                    background: url({{ Gravatar::get(Auth::user()->email) }}) 50% 50% no-repeat !important;
                    background-size: auto 100% !important;
                }
            @elseif (Auth::User() && (Auth::User()->profile) && (Auth::User()->profile->avatar_status == 1 ))
                .user-avatari {
                    background: url({{ Auth::User()->profile->avatar }}) 50% 50% no-repeat !important;
                    background-size: auto 100% !important; 
                }
                
            @endif

        </style>

        {{-- Scripts --}}
        <script>
             if(navigator.userAgent.match(/Android/i)){
                window.scrollTo(0,1);
             }
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>

        @if (Auth::User() && (Auth::User()->profile) && $theme->link != null && $theme->link != 'null')
            <link rel="stylesheet" type="text/css" href="{{ $theme->link }}">
        @endif

        @yield('head')
        @include('scripts.ga-analytics')
    </head>
    <body class="nk-body bg-lighter npc-default has-sidebar">
    <div class="loader" id="loader"></div>
    <div id="app" class="nk-app-root">
        <div id="app_ctn" class="nk-main overlay">
        @if (Auth::User())
            @include('partials.sidebar')
        @endif
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar   ">
            @if (Auth::User())
                <div class="nk-wrap ">
                @include('partials.header')
            @endif
                    <div class="nk-content ">
                        @include('partials.form-status') 
                        @yield('content')
                    </div>
            @if (Auth::User())
                @include('partials.footer')
            @endif
            </div>
            <!-- wrap @e -->
        </div>
    </div>

        {{-- Scripts --}} 
        <script src="{{ asset('/template/assets/js/bundle.js') }}"> type="text/javascript"</script>
        <script src="{{ asset ('/template/assets/js/scripts.js') }}" type="text/javascript"></script>
        <script src="{{ asset ('/template/assets/js/global.js') }}" type="text/javascript"></script> 
        <script src="{{ asset ('/js/funct.js') }}" type="text/javascript"></script>
        <script src="{{ asset ('/js/eaip.js') }}" type="text/javascript"></script>
        <script src="{{ asset ('/js/magvar/newGeomag.js') }}" type="text/javascript"></script>
        <script src="{{ asset ('/js/numeral.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset ('/js/js.cookie.min.js') }}"></script>
        <script type="text/javascript"> 
            $(window).on('load', function () {
                $('#loader').fadeOut();
                $('#app_ctn').removeClass('overlay'); 

                let comp = Cookies.get('is-compact');
                if(comp){
                    $('#sidebarMenu').addClass('is-compact');
                }
 
            }); 
            @if(request()->route()->uri!='login')
            window.addEventListener('DOMContentLoaded', (event) => { 
                const button = document.getElementById('btnFullscreen');
                if(button!==null)
                button.addEventListener('click', () => {
                        toogleFullscreen(document.documentElement);
                }); 
            });
            @endif
            
		//alert("{{ request()->route()->uri }}");
        </script>
            @php
            $route_aip = array('electronicaip','aipsubmission','listats','atsdetail','timeline','listairport','editairport','aipedit','edit22','edit23','edit24','edit25','edit26','edit27','edit28','edit29','edit210','edit211','edit212','edit213','edit214','edit215','edit216','edit216','edit217','edit218','edit219','edit220','edit221','edit222','edit223','edit224');
            $route_map = array('home','volcanoes','interaktif/Airport','interaktif/Airspace','interaktif/Enroute','interaktif/Navaid','interaktif/Waypoint','vfr_planning','inavcec/predictool') ;
            $route_profil = array('profile/{profile}/edit');
	    @endphp
            @if ( in_array(request()->route()->uri, $route_aip) )
                <script src="{{ url ('/js/eaip.js') }}"></script>    
            @endif
 	    @if ( in_array(request()->route()->uri, $route_profil ) )
		<script src="{{ url ('/template/assets/js/autosuggest.min.js') }}"></script>
        	<link rel="stylesheet" href="{{ url ('/template/assets/css/autosuggest.min.css') }}">
            @endif
            @if ( in_array(request()->route()->uri, $route_map) )
                @if(config('settings.googleMapsAPIStatus'))
                    <script src="{{ asset('js/numeral.min.js') }}"></script> 
                    <script src="https://unpkg.com/@googlemaps/markerwithlabel@1.0.3/dist/index.min.js" type="text/javascript"></script>
                    <script src="https://polyfill.io/v3/polyfill.min.js?features=default" type="text/javascript" ></script>
                    <!-- <script src="https://unpkg.com/@googlemaps/js-api-loader@1.0.0/dist/index.min.js"></script> -->
                    <script src="https://maps.googleapis.com/maps/api/js?key={!!config('settings.googleMapsAPIKey')!!}&libraries=places&v=weekly&language=en&region=ID" type="text/javascript"></script>
                @endif
            @endif
            <script>
            var APP_URLE = "<?php echo env('APP_URL'); ?>";
            const XHR = (type, endpoint, params, callback) => {
                if (type == 'GET') {
                    $.ajax({
                        type: type,
                        url: APP_URLE+'/'+endpoint,
                        complete: data => {
                            callback(data.responseJSON)
                        }
                    })
                } else {
                    if (params instanceof FormData) {
                        params.append('_token', "<?php echo csrf_token(); ?>")
                        $.ajax({
                            type: type,
                            url: APP_URLE+'/'+endpoint,
                            data: params,
                            processData: false,
                            contentType: false,
                            complete: data => {
                                callback(data.responseJSON)
                            }
                        })
                    } else {
                        params['_token'] = "<?php echo csrf_token(); ?>"
                        $.ajax({
                            type: type,
                            url: APP_URLE+'/'+endpoint,
                            data: params,
                            complete: data => {
                                callback(data.responseJSON)
                            }
                        })
                    }
                }
            }
        </script>
        @yield('footer_scripts')
        @yield('ext_script') 
    </body>
    
</html>
