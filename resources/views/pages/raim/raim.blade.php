@extends('layouts.app')

@section('template_title')
    GPS RAIM PREDICTool
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
    <div class="panel panel-default">
        <a class="btn" onclick="showinfo('about')" data-toggle="modal"><h6>:: GPS RAIM PREDICTool ::</h6></a>
        <br>
        <div class="modal-dialog-lg" role="document" id="about" style="visibility: hidden">
            <div class="modal-content">
                <div class="modal-footer bg-gray">
                    <h6 class="modal-title text-black-50">:: GPS RAIM PREDICTool ::</h6>
                    <a v-on:click="closed" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <ul>
                        <li align="justify"><strong>PREDICTool</strong>, developed by Egis Avia, is a web-based GPS Receiver Autonomous Integrity Monitoring (RAIM) prediction tool. It permits to answer the operational requirement to check the availability of GPS-based operations and is dedicated to the approach phase of flight. This check is done for Baro and non-Baro aided TSO-C129a and TSO-C145/146 receivers.</li>
                        <li align="justify">RAIM prediction is based on the latest almanac and NANUs (Notice Advisory to Navstar Users) issued by the<i> US Coast Guard</i>. This tool gives the predicted RAIM availability information for a 72 hours period for each airfield. Computation is done on a 1 minute basis starting at midnight of the current day. Computation is automatically executed every day and also on detection of new applicable NANU</li>
                        <li align="justify">Predicted RAIM outages are displayed for the selected airfield on a webpage giving the following information: site description, prediction parameters, predicted unavailability displayed (table and two charts - one for each receiver type). For each computation, a file (in csv format) is generated.</li>
                    </ul>
                </div>
                <div class="modal-footer bg-light">
                    <span class="sub-text">&copy; 2020 IWISHIndonesia.</span>
                </div>
            </div>
        </div>
    </div> <!-- <div class="form-check bg-black"> -->
    <div class="row mt-3">
        <div class="col-md-12">
            <button class="btn btn-dim btn-info" onclick="addarptraim()"><i class="icon ni ni-plus-circle-fill" align="left" aria-hidden="true"></i> Add</button>
            <table class="datatable-init table table-bordered table-hover" id="table-content">
                <thead class="thead-dark" align="center">
                    <tr>
                        <th>#</th>
                        <th style="cursor:pointer">ICAO</th>
                        <th style="cursor:pointer">Name</th>
                        <th style="cursor:pointer">City</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($airport as $arpt)
                        <!-- <tr v-bind:key="airport.arpt_ident" collapse="0" v-bind:id="airport.arpt_ident">
                            <td class="tb-tnx-action">
                                <div class="dropdown">
                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-left dropdown-menu-sm-12">
                                        <ul class="link-list-plain">
                                            <a class="btn btn-dim btn-primary" onclick="GetRaim({{-- json_encode($arpt) --}})"><em class="icon ni ni-view-col-fill"></em> View</a>
                                            <a class="btn btn-dim btn-danger" onclick="removelist({{-- $arpt->arpt_ident --}})"><em class="icon ni ni-delete"></em>Remove</a>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                            <td style="cursor:pointer" class="arptlstpdf"><a onclick="GetRaim({{-- json_encode($arpt) --}})">{{-- $arpt->icao --}}</a></td>
                            <td style="cursor:pointer" class="arptlstpdf"><a onclick="GetRaim({{-- json_encode($arpt) --}})">{{-- $arpt->arpt_name --}}</a></td>
                            <td style="cursor:pointer" class="arptlstpdf"><a onclick="GetRaim({{-- json_encode($arpt) --}})">{{-- $arpt->city_name --}}</a></td>
                        </tr> -->
                        <tr class="nk-tb-item" v-bind:key="airport.arpt_ident" collapse="0" v-bind:id="airport.arpt_ident">
                            <td class="tb-tnx-action">
                                <div class="dropdown">
                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-left dropdown-menu-sm-12">
                                        <ul class="link-list-plain">
                                            <a class="btn btn-dim btn-primary" onclick="GetRaim('{{ $arpt->icao }}')"><em class="icon ni ni-view-col-fill"></em> View</a>
                                            <a class="btn btn-dim btn-danger" onclick="removelist('{{ $arpt->arpt_ident }}')"><em class="icon ni ni-delete"></em>Remove</a>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                            <td style="cursor:pointer" class="arptlstpdf"><a onclick="GetRaim('{{ $arpt->icao }}')">{{ $arpt->icao }}</a></td>
                            <td style="cursor:pointer" class="arptlstpdf"><a onclick="GetRaim('{{ $arpt->icao }}')">{{ $arpt->arpt_name }}</a></td>
                            <td style="cursor:pointer" class="arptlstpdf"><a onclick="GetRaim('{{ $arpt->icao }}')">{{ $arpt->city_name }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
    </div>
</div>
@endsection
@section('footer_scripts') 
<script type="text/javascript">
$('#about').hide();
function showinfo(id){
  var vol = document.getElementById(id);
    vol.style.visibility = 'visible';
  $('#' + id).toggle(); 
}
// function GetRaim(arpt){
//     console.log(arpt.icao)
//     let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
//     window.open('http://118.97.221.164/WebSite/site.php?site=' + arpt.icao , 'GPS RAIM PREDICTool', params)
// }
function GetRaim(icao){
    // console.log(arpt.icao)
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('http://118.97.221.164/WebSite/site.php?site=' + icao , 'GPS RAIM PREDICTool', params)
}

    function removelist(data){
            this.frequpdate = {
                raim: 0,
            }
            Swal.fire({
                title: 'Are you sure?',
                text: data.arpt_name + " will be removed from GPS RAIM list",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, removed it!'
            }).then((yesno) => {
                if (yesno.value) {
                    ApiManager.request('POST', 'airport/update/' + data.arpt_ident, this.frequpdate, (response) => {
                        if (response.isSuccess()) {
                            Swal.fire(
                                'Data updated!',
                                'GPS RAIM data has been updated',
                                'success'
                            )
                            this.loadAirportList()
                        }
                    })
                }
            })
    }
    function addarptraim(){
            // this.modaladd=true;
            // console.log('edit seq data',data)
           var inputOptions = {}; // Define like this!
            this.airportnonList.forEach(cd=>{
                inputOptions[cd.arpt_ident] = cd.icao + '-' + cd.city_name + '/' + cd.name;
            })
            Swal.fire({
                title: "GPS RAIM ",
                text : 'Added GPS RAIM Airport',
                input: 'select',
                inputOptions: inputOptions,
                showCancelButton: true,
            }).then((result) => {
                if (result.value){
                    // console.log(result.value)
                    this.frequpdate = {
                            raim: 1,
                        }
    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "this Airport will be added",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, add!'
                    }).then((yesno) => {
                        if (yesno.value) {
                            // console.log(this.frequpdate,result.value)
                            ApiManager.request('POST', 'airport/update/' + result.value, this.frequpdate, (response) => {
                                if (response.isSuccess()) {
                                    Swal.fire(
                                        'Data updated!',
                                        'GPS RAIM data has been updated',
                                        'success'
                                    )
                                    this.loadAirportList()
                                }
                            })
                        }
                    })
                }

            });
        }


// import MyFunct from './../../helpers/funct'
// import ApiManager from './../../helpers/ApiManager'
// import Swal from "sweetalert2";


// export default {
//     // components: {
//     //     'ArptSearch-component': ArptSearchComponent,
//     // },
//     data() {
//         return {
//             airportList: [],
//             airportnonList:[],
//             search:'',
//             isAirportListLoaded:false,
//             modal:false,
//             modaladd:false,
//             page: 1,
//             perPage: 10,
//             pages: [],
//             numberOfPages:'',
//             isRaim:false,

//         }
//     },
//     computed: {
//         filteredList() {
//             return this.airportList.filter(post => {
//                 return post.icao.toLowerCase().includes(this.search.toLowerCase()) || post.arpt_name.toLowerCase().includes(this.search.toLowerCase()) || post.city_name.toLowerCase().includes(this.search.toLowerCase())
//             })
//         },
//         displayraim(){
//             return this.paginate(this.filteredList)
//         },
//     },
//     created() {
//         this.loadAirportList()
//     },
//     watch: {
// 		airportList () {
// 			this.setPages();
//         },

//     },
//     methods: {
//         setPages () {
//             this.pages=[];
//             this.numberOfPages = Math.ceil(this.airportList.length / this.perPage);
//             // console.log(numberOfPages)
// 			for (let index = 1; index <= this.numberOfPages; index++) {
//                 this.pages.push(index);
//                 // console.log(this.pages)
// 			}
// 		},
// 		paginate (posts) {
// 			let page = this.page;
// 			let perPage = this.perPage;
// 			let from = (page * perPage) - perPage;
// 			let to = (page * perPage);
// 			return  posts.slice(from, to);
//         },
//         sort:function(s) {
//             //if s == current sort, reverse
//             // console.log(s)
//             if(s === this.currentSort) {
//             this.currentSortDir = this.currentSortDir==='asc'?'desc':'asc';
//             }
//             this.currentSort = s;
//             this.airportList.sort(this.sortBy(s, this.currentSortDir));
//         },
//         sortBy(property, order) {
//             this.currnetSortDir=order;
//             return function(a, b) {
//                 const varA =
//                 typeof a[property] === "string"
//                     ? a[property].toUpperCase()
//                     : a[property];
//                 const varB =
//                 typeof b[property] === "string"
//                     ? b[property].toUpperCase()
//                     : b[property];

//                 let comparison = 0;
//                 if (varA > varB) comparison = 1;
//                 else if (varA < varB) comparison = -1;
//                 return order === "desc" ? comparison * -1 : comparison;
//             };
//         },
//         showmodal(){
//             if ( this.modal==true){
//                 this.perPage= 10,
//                 this.modal=false;
//             }else{
//                 this.perPage= 4,
//                 this.modal=true;
//             }
//         },
//         closed(){
//             this.modal=false;
//         },
//         getidx: function (idx) {
//             return idx + 1
//         },
//         removelist(data){
//             this.frequpdate = {
//                 raim: 0,
//             }
//             Swal.fire({
//                 title: 'Are you sure?',
//                 text: data.arpt_name + " will be removed from GPS RAIM list",
//                 icon: 'info',
//                 showCancelButton: true,
//                 confirmButtonColor: '#3085d6',
//                 cancelButtonColor: '#d33',
//                 confirmButtonText: 'Yes, removed it!'
//             }).then((yesno) => {
//                 if (yesno.value) {
//                     ApiManager.request('POST', 'airport/update/' + data.arpt_ident, this.frequpdate, (response) => {
//                         if (response.isSuccess()) {
//                             Swal.fire(
//                                 'Data updated!',
//                                 'GPS RAIM data has been updated',
//                                 'success'
//                             )
//                             this.loadAirportList()
//                         }
//                     })
//                 }
//             })
//         },
//         addarptraim(){
//             // this.modaladd=true;
//             // console.log('edit seq data',data)
//            var inputOptions = {}; // Define like this!
//             this.airportnonList.forEach(cd=>{
//                 inputOptions[cd.arpt_ident] = cd.icao + '-' + cd.city_name + '/' + cd.name;
//             })
//             Swal.fire({
//                 title: "GPS RAIM ",
//                 text : 'Added GPS RAIM Airport',
//                 input: 'select',
//                 inputOptions: inputOptions,
//                 showCancelButton: true,
//             }).then((result) => {
//                 if (result.value){
//                     // console.log(result.value)
//                     this.frequpdate = {
//                             raim: 1,
//                         }
    
//                     Swal.fire({
//                         title: 'Are you sure?',
//                         text: "this Airport will be added",
//                         icon: 'info',
//                         showCancelButton: true,
//                         confirmButtonColor: '#3085d6',
//                         cancelButtonColor: '#d33',
//                         confirmButtonText: 'Yes, add!'
//                     }).then((yesno) => {
//                         if (yesno.value) {
//                             // console.log(this.frequpdate,result.value)
//                             ApiManager.request('POST', 'airport/update/' + result.value, this.frequpdate, (response) => {
//                                 if (response.isSuccess()) {
//                                     Swal.fire(
//                                         'Data updated!',
//                                         'GPS RAIM data has been updated',
//                                         'success'
//                                     )
//                                     this.loadAirportList()
//                                 }
//                             })
//                         }
//                     })
//                 }

//             });
//         },   
//         loadAirportnonraim() {
//             this.airportnonList = []
//             this.arpturl = 'airport/list?ctry=ID&sort=arpt_name:asc'
//             ApiManager.request('GET', this.arpturl, null, (response) => {
//                 response.getData().forEach(airport => {
//                     if (airport.raim == 0){
//                         this.airportnonList.push(airport)
//                     }
//                 })
//             })
//         },
        
//         remove(iframe){
//             this.iframeLoaded = false;
//             var frame = document.getElementById(iframe);
//             if (frame !== null){
//                 frame.src = ''; 
//                 this.iframeLoaded = true;
//                 frame.parentNode.removeChild(frame);
//             }
//         },
       
//         GetRaim(value){
//             let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
//             window.open('http://118.97.221.164/WebSite/site.php?site=' + value.icao , 'GPS RAIM PREDICTool', params)
//         },
//         TitleCase( string )
//         {
//         // console.log( 'TitleCase', string,typeof string )
//         if ( typeof string == 'undefined' || string == '' || string == null ) {
//             sentence = string;
//         } else {
//             var sentence = string.toLowerCase().split( " " );
//             // console.log(sentence)
//             for ( var i = 0; i < sentence.length; i++ ) {
//                 sentence[ i ] = sentence[ i ][ 0 ].toUpperCase() + sentence[ i ].slice( 1 );
//             }
//         }
//         // console.log(sentence.join(' '))
//         return sentence.join(' ');
//         },


//     }
// }
</script>
@endsection
