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
                        <!-- <a class="link link-primary link-sm" tabindex="-1" href="{{ route('password.request') }}">{{ __('auth.forgot') }}</a> -->
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
                <div class="form-note-s2 pt-4"> New on our platform? <a href="{{ route('register') }}">Create an account</a></div>
            @endif
            
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
                <p>&copy; 2021 {{ config('app.name') }}. All Rights Reserved. </p>
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
    </div><!-- .nk-split-content -->
</div><!-- .nk-split -->
 
@endsection
@section('footer_scripts')

<script type="text/javascript">
 
</script>
@endsection
