<div class="nk-split nk-split-page nk-split-md">
    <div class="nk-split-content nk-split-stretch bg-gray-100 d-flex" data-content="athPromo" data-toggle-screen="lg" data-toggle-overlay="true" style="height:calc(100vh - 164px)!important">
        <div class="slider-wrap w-100 w-max-550px p-3 p-sm-5 m-auto">
            <div class="brand-logo pb-5">
                <a href="/" class="logo-link w-100">
                    <img class="logo-light logo-img logo-img-lg w-100" src="{{ url('/template/images/last_logo.png') }}" srcset="{{ url('/template/images/last_logo.png 2x') }}" alt="logo" style="max-height:80px!important">
                    <img class="logo-dark logo-img logo-img-lg w-100" src="{{ url('/template/images/last_logo.png') }}" srcset="{{ url('/template/images/last_logo.png') }}" alt="logo-dark" style="max-height:80px!important">
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
                                <h6 class="text-center"> </h6>
                                <h6 class="text-center"> </h6>
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
                                <h6 class="text-center"> </h6>
                                <h6 class="text-center"> </h6>
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
                                <h6 class="text-center" id="PUBLICATION1"></h6>
                                <h6 class="text-center" id="PUBLICATION2"></h6>
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
                                <h6 class="text-center"> </h6>
                                <h6 class="text-center"> </h6>
                            </div>
                            <br>
                            <br>      
                        </div>
                    </div>
                </div>
                
            </div><!-- .slider-init -->
            <div class="slider-dots"></div>
            <div class="slider-arrows"></div>
            <div class="floating col-md-12 bg-primary footer-activities">
                <div class="card h-100 bg-transparent">
                    <div class="card-inner bg-transparent"> 
                        <div class="analytic-au">
                            <div class="analytic-data-group analytic-au-group g-3">
                                <div class="analytic-data analytic-au-data">
                                    <div class="title text-center text-white">Visitors</div>
                                    <div class="change text-center text-white" style="font-size: large;">{{ $visitors }}</div>
                                    <!-- <div class="change up text-center"><em class="icon ni ni-arrow-long-up"></em>4.63%</div> -->
                                </div>
                                <div class="analytic-data analytic-au-data">
                                    <div class="title text-center text-white">Online Users</div>
                                    <div class="change up text-center text-white" style="font-size: large;">{{ $OnlineUsers }}</div>
                                    <!-- <div class="change down text-center"><em class="icon ni ni-arrow-long-up"></em>1.92%</div> -->
                                </div>
                                <div class="analytic-data analytic-au-data">
                                    <div class="title text-center text-white">Active Users</div>
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


@section('footer_scripts')
{{-- <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/vuex"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script> --}}
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
                    // console.log(ip)
                    if (ip.data.length>0){
                        var d = new Date(ip.data[0].pub_date);
                        var n = d.getFullYear().toString().substr( -2 );
                        var volc = ip.data[0].pub_type + ' ' + ip.data[0].nr + '/'+n
                        
                        document.getElementById('judulpub').innerHTML="The Latest Publication";
                        document.getElementById('PUBLICATION').innerHTML=volc;
                    }
                    // document.getElementById('PUBLICATION1').innerHTML='Publication Date = ' + ip.data[0].pub_date;
                    // document.getElementById('PUBLICATION2').innerHTML='Effective Date = ' + ip.data[0].eff_date;
                    // console.log(ip)
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
