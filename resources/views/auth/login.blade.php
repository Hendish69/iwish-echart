@extends('layouts.app')
@section('head')
<style type="text/css">
    .floating{
        position: fixed;
        bottom: 0px;
        right: 0px;
        z-index: 1009;
        border: 1px solid #dbdfea;
        border-radius: 5px;
    }
  /*  @media only screen and (max-width: 768px) {
        .footer-activities{
            display: none;
        }
    }*/
</style>
@endsection
@section('content')
<div class="nk-split nk-split-page nk-split-md">
    <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white">
        <div class="absolute-top-right d-lg-none p-3 p-sm-5">
            <a href="#" class="toggle btn-white btn btn-icon btn-light" data-target="athPromo"><em class="icon ni ni-info"></em></a>
        </div>
        <div class="nk-block nk-block-middle nk-auth-body">
            <div class="brand-logo pb-5">
                <a href="/" class="logo-link w-100">
                    <img class="logo-light logo-img logo-img-lg w-100" src="{{ url('/template/images/last_logo.png') }}" srcset="{{ url('/template/images/last_logo.png 2x') }}" alt="logo" style="max-height:130px!important">
                    <img class="logo-dark logo-img logo-img-lg w-100" src="{{ url('/template/images/last_logo.png') }}" srcset="{{ url('/template/images/last_logo.png') }}" alt="logo-dark" style="max-height:130px!important">
                </a>
            </div>
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h5 class="nk-block-title">{{ __('Sign In') }}</h5>
                    <div class="nk-block-des">
                        <p>Access the {{ config('app.name') }} panel using your email and passcode.</p>
                    </div>
                </div>
            </div><!-- .nk-block-head -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <div class="form-label-group">
                        <label class="form-label" for="default-01">{{ __('Email or Username') }}</label>
                        {{-- <a class="link link-primary link-sm" tabindex="-1" href="#">Need Help?</a> --}}
                    </div>
                    <input name="email" type="text" class="form-control form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" placeholder="Enter your email address or username" value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div><!-- .foem-group -->
                <div class="form-group">
                    <div class="form-label-group">
                        <label class="form-label" for="password">{{ __('Passcode') }}</label>
                        <a class="link link-primary link-sm" tabindex="-1" href="{{ route('password.request') }}">{{ __('auth.forgot') }}</a>
                    </div>
                    <div class="form-control-wrap">
                        <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                        </a>
                        <input type="password" name="password" class="form-control form-control-lg{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" placeholder="Enter your passcode">
                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div><!-- .foem-group -->
                <div class="form-group">
                    <button class="btn btn-lg btn-primary btn-block">Sign in</button>
                </div>
            </form><!-- form -->
            @if (Route::has('register'))
                <div class="form-note-s2 pt-4"> New on our platform? <a href="{{ route('register') }}">Create an account</a>
            @endif
            </div>
            {{-- include('partials.socials-icons') --}}
            {{-- <div class="text-center mt-5">
                <span class="fw-500">I don't have an account? <a href="#">Try 15 days free</a></span>
            </div> --}}
        </div><!-- .nk-block -->
        <div class="nk-block nk-auth-footer">
            <div class="nk-block-between">
                <ul class="nav nav-sm">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Help</a>
                    </li>
                </ul><!-- .nav -->
            </div>
            <div class="mt-3">
                <p>&copy; 2020 {{ config('app.name') }}. All Rights Reserved. </p>
            </div>
        </div><!-- .nk-block -->
    </div><!-- .nk-split-content -->
    <div class="nk-split-content nk-split-stretch bg-gray-100 d-flex toggle-break-lg toggle-slide toggle-slide-right" data-content="athPromo" data-toggle-screen="lg" data-toggle-overlay="true">
        <div class="slider-wrap w-100 w-max-550px p-3 p-sm-5 m-auto">
            <div class="brand-logo pb-5">
                <a href="/" class="logo-link w-100">
                    <img class="logo-light logo-img logo-img-lg w-100" src="{{ url('/template/images/last_logo.png') }}" srcset="{{ url('/template/images/last_logo.png 2x') }}" alt="logo" style="max-height:130px!important">
                    <img class="logo-dark logo-img logo-img-lg w-100" src="{{ url('/template/images/last_logo.png') }}" srcset="{{ url('/template/images/last_logo.png') }}" alt="logo-dark" style="max-height:130px!important">
                </a>
                <div class="nk-block-des w-100 text-center">
                    <p>Ministry of Transportation Republic of Indonesia</p>
                </div>
            </div>
            <div class="card-inner text-center" style="padding-top:60px;">
                <h4 class="text-indigo text-center">Aeronautical Information Updates</h4>
            </div>
            <div class="slider-init" data-slick='{"dots":true, "arrows":false}'>
                <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">  
                    <div class="carousel-inner align-content-center">    
                        <div class="carousel-item active">
                            <div class="nk-feature-img">
                                <!-- <img class="round" src="./images/slides/promo-c.png" srcset="./images/slides/promo-c2x.png 2x" alt=""> -->
                            </div>
                            <div class="nk-feature-content py-4 p-sm-5">
                                <h5 class="text-indigo text-center">CVGHM - VONA</h5>        
                                <h6 class="text-center" id="VOLCANO"></h6>

                            </div>
                            <br>
                            <br>
                            <!-- <img src="./images/slides/promo-a.png" class="d-block w-100" alt="...">     -->
                        </div>    
                        <div class="carousel-item">
                            <div class="nk-feature-img">
                                <!-- <img class="round" src="./images/slides/promo-c.png" srcset="./images/slides/promo-c2x.png 2x" alt=""> -->
                            </div>
                            <div class="nk-feature-content py-4 p-sm-5">
                                <h5 class="text-indigo text-center">NOF/ACC - ASHTAM</h5>        
                                <h6 class="text-center" id="ASHTAM"></h6>
                            </div>
                            <br>
                            <br>      
                        </div>    
                        <div class="carousel-item">
                            <div class="nk-feature-img">
                                <!-- <img class="round" src="./images/slides/promo-c.png" srcset="./images/slides/promo-c2x.png 2x" alt=""> -->
                            </div>
                            <div class="nk-feature-content py-4 p-sm-5">
                                <h5 id="judulpub" class="text-indigo text-center"></h5>        
                                <h6 class="text-center" id="PUBLICATION"></h6>
                            </div>
                            <br>
                            <br>      
                        </div>   
                        <div class="carousel-item">
                            <div class="nk-feature-img">
                                <!-- <img class="round" src="./images/slides/promo-c.png" srcset="./images/slides/promo-c2x.png 2x" alt=""> -->
                            </div>
                            <div class="nk-feature-content py-4 p-sm-5">
                                <h5 class="text-indigo text-center">Request Data Change</h5>        
                                <h6 class="text-center" id="ARPT"></h6>
                            </div>
                            <br>
                            <br>      
                        </div>
                    </div>
                </div>
                
            </div><!-- .slider-init -->
            <div class="slider-dots"></div>
            <div class="slider-arrows"></div> 
            <div class="floating col-md-12 bg-transparent-25 footer-activities">
                <div class="card h-100 bg-transparent">
                    <div class="card-inner bg-transparent"> 
                        <div class="analytic-au">
                            <div class="analytic-data-group analytic-au-group g-3">
                                <div class="analytic-data analytic-au-data">
                                    <div class="title text-center">Visitors</div>
                                    <div class="change text-center text-info" style="font-size: large;">{{ $visitors }}</div>
                                    <!-- <div class="change up text-center"><em class="icon ni ni-arrow-long-up"></em>4.63%</div> -->
                                </div>
                                <div class="analytic-data analytic-au-data">
                                    <div class="title text-center">Online Users</div>
                                    <div class="change up text-center" style="font-size: large;">{{ $OnlineUsers }}</div>
                                    <!-- <div class="change down text-center"><em class="icon ni ni-arrow-long-up"></em>1.92%</div> -->
                                </div>
                                <div class="analytic-data analytic-au-data">
                                    <div class="title text-center">Active Users</div>
                                    <div class="change text-center text-gray" style="font-size: large;">{{ $ActiveUsers }}</div>
                                    <!-- <div class="change up text-center"><em class="icon ni ni-arrow-long-up"></em>3.45%</div> -->
                                </div>
                            </div>
                             
                        </div>
                    </div>
                </div>
            </div> 
        </div><!-- .slider-wrap --> 
    </div><!-- .nk-split-content -->
</div><!-- .nk-split -->
 
@endsection
@section('footer_scripts')

<script type="text/javascript">

        $.ajax({
                type: 'GET',
                url:'api/vol/vona?sort=issued:desc&limit=1',
                async: true,
                success: (ip) => {
                    // console.log(ip.data[0])
                    var volc = ip.data[0].volcano[0].va_name + ' - ' + ip.data[0].noticenumber + ' - <strong class="'+ getwarna(ip.data[0].cu_code) + '">' + ip.data[0].cu_code + '</strong>'
                    document.getElementById('VOLCANO').innerHTML=volc;
                    // console.log(ip)
                }
            });
            $.ajax({
                type: 'GET',
                url:'api/vol/ashtam?sort=ashtam_update_time:desc&limit=1',
                async: true,
                success: (ip) => {
                    var volc = ip.data[0].ashtam_volcano + ' - ' + ip.data[0].ashtam_number + ' - <strong class="'+ getwarna(ip.data[0].ashtam_alert_code) + '">' + ip.data[0].ashtam_alert_code + '</strong>'
                    document.getElementById('ASHTAM').innerHTML=volc;
                    // console.log(ip)
                }
            });
            $.ajax({
                type: 'GET',
                url:'api/vol/request?sort=rawdata_id:desc&limit=1',
                async: true,
                success: (ip) => {
                    // console.log(ip.data.length)
                    if (ip.data.length>0){
                        var volc = ip.data[0].icao + ' - ' + ip.data[0].arpt_name + ' - ' + ip.data[0].city_name
                        document.getElementById('ARPT').innerHTML=volc;
                    }
                    // console.log(ip)
                }
            });
            
            $.ajax({
                type: 'GET',
                url: 'api/vol/request?status_raw=100&sort=pub_date:asc&limit=1',
                async: true,
                success: (ip) => {
                    if (ip.data.length>0){
                        var d = new Date(ip.data[0].pub_date);
                        var n = d.getFullYear().toString().substr( -2 );
                        var volc = ip.data[0].pub_type + ' ' + ip.data[0].nr + '/'+n
                        
                        document.getElementById('judulpub').innerHTML="The Latest Publication";
                        document.getElementById('PUBLICATION').innerHTML=volc;
                    }
                   
                }
            });
        function getwarna(warna){
            if (warna.includes('ORANGE')){
                return 'text-orange'
            } else if (warna.includes('RED')){
                return 'text-danger'
            }else if (warna.includes('GREEN')){
                return 'text-success'
            }else if (warna.includes('YELLOW')){
                return 'text-warning'
            }
        }

</script>
@endsection
