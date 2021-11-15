<div class="nk-split nk-split-page nk-split-md">
    <div class="nk-split-content nk-split-stretch bg-gray-100 d-flex" data-content="athPromo" data-toggle-screen="lg" data-toggle-overlay="true" style="height:calc(100vh - 164px)!important">
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
            <div class="floating col-md-12 bg-primary footer-activities">
                <div class="card h-100 bg-transparent">
                    <div class="card-inner bg-transparent"> 
                        <div class="analytic-au">
                            <div class="analytic-data-group analytic-au-group g-3">
                                <div class="analytic-data analytic-au-data">
                                    <div class="title text-center text-white">Visitors</div>
                                    <div class="change text-center text-white" style="font-size: large;">{{ $visitors }}</div>
             
                                </div>
                                <div class="analytic-data analytic-au-data">
                                    <div class="title text-center text-white">Online Users</div>
                                    <div class="change up text-center text-white" style="font-size: large;">{{ $OnlineUsers }}</div>
                                </div>
                                <div class="analytic-data analytic-au-data">
                                    <div class="title text-center text-white">Active Users</div>
                                    <div class="change text-center text-gray" style="font-size: large;">{{ $ActiveUsers }}</div>
                                </div>
                            </div>
                             
                        </div>
                    </div>
                </div>
            </div> 
        </div> 
    </div><!-- .nk-split-content -->
</div><!-- .nk-split -->


@section('footer_scripts') 

@endsection
