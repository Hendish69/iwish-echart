@extends('layouts.app')

@section('template_title')
    Electronic AIP
@endsection

@section('head')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
              <!-- HEADER -->
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                        <h5>Table of Content</h5>
                    </div>
                </div>
                <div class="row g-gs">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">eAIP Section</label>
                            <div class="form-control-wrap">
                                <select class="custom-select" id="section" required>
                                    <option selected>Select Section</option>
                                        @foreach($codeaip as $codeaip)
                                            <option value="{{ $codeaip->id }}">{{ $codeaip->sub_id }} {{ $codeaip->definition }} </option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">eAIP Sub Section</label>
                            <select class="custom-select" name="subsection" id="subsection" onchange="eaipSubSection()">
                                <option selected>Select Subsection</option>
                            </select>
                        </div>
                    </div>
                </div>
                <form action="" class="mt-3">
                    <div class="col-md-12" name="Vol-Data" id="Vol-Data" style='visibility: hidden' onclick="AspChecked()">
                    </div>
                    <div class="col-md-12" name="List-Data" id="List-Data" style='visibility: hidden'>
                        <table class="table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark" name="row-judul" id="row-judul">
                            </thead>
                            <tbody name="isi-table" id="isi-table">

                            </tbody>
                        </table>
                    </div>
                        <!-- <div class="col-md-12" name="List-Data" id="List-Data" style='visibility: hidden'>
                            <div class="card">
                                <table class="datatable table-sm table-striped table-hover">
                                    <thead class="thead-dark" name="row-judul" id="row-judul">
                                    </thead>
                                    <tbody name="isi-table" id="isi-table">
                                    </tbody>
                                </table>
                            </div>
                        </div> -->
                </form>
            </div>
        </div>
    </div>
    

@endsection
@section('footer_scripts')

<script type="text/javascript">
let uri = 'api/eaip/menu/two/';
let second_opt = 'subsection';
let section = $('#section') ;
section.select2me(second_opt,uri); 
$(function() { 
    if(section.val() > 0){
        section.trigger('change');
    }
});

// $('#section').select2me('subsection','api/eaip/menu/two/');

function eaipSubSection() {

$(document).on('change', '#subsection', function (e) {
  e.preventDefault();
    let ss = document.getElementById("subsection");
    let sub = ss.value;
    let sub_text= ss.options[ss.selectedIndex].text;
    // let x = document.getElementById("List-Data");
    let vol = document.getElementById("Vol-Data");
    Visibility("List-Data",'True');
    // console.log( 'subsection ' + sub );
    let tab = document.getElementById("table-content");
    vol.style.visibility = 'hidden';
    tab.setAttribute( 'class', 'datatable-init table table-bordered table-hover' );
    switch (sub) {
        case '30':
            //gen 2.5
            window.scrollTo(0,0);
            window.location.href = '/gen22/html';
            break;
        case '32':
            //gen 2.5
            window.scrollTo(0,0);
            window.location.href = '/gen24/html';
            break;
        case '33':
            //gen 2.5
            window.scrollTo(0,0);
            window.location.href = '/gen25/html';
            break;

        case '96':
            window.scrollTo(0,0);
            window.location.href = '/listairport/html';
            break;
        case '59':
            window.scrollTo(0,0);
            window.location.href = '/listairpace/html';

           
            break;
        case '61':
        case '62':
        case '63':
        case '64':
            window.scrollTo(0,0);
            window.location.href = '/enroutehtml/' + sub;
            break;
        case '66':
            window.scrollTo(0,0);
            window.location.href = '/enr41/66';

            break;
        case '68':
            window.scrollTo(0,0);
            window.location.href = '/enr41/68';
            // AtsList('67');
            break;
        case '70':
        case '71':
            window.scrollTo(0,0);
            window.location.href = '/listsuas/html/' + sub ;
            // AtsList('67');
            break;
        default:
            window.scrollTo(0,0);
            window.location.href = 'text/html/' + sub;

            break;
    }

});
}

</script>
@endsection