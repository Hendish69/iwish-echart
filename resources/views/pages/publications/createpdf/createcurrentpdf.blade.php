@extends('layouts.app')

@section('template_title')
    Create PDF
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
                        <h6>Table of Content</h6>
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
console.log($('#section').select2me(value));
// $('#section').select2me('subsection','api/eaip/menu/two/');

function eaipSubSection() {

$(document).on('change', '#subsection', function (e) {
  e.preventDefault();
    let sub = document.getElementById("subsection").value;
    // let x = document.getElementById("List-Data");
    let vol = document.getElementById("Vol-Data");
    Visibility("List-Data",'True');
    console.log( 'subsection ' + sub );
    let tab = document.getElementById("table-content");
    vol.style.visibility = 'hidden';
    tab.setAttribute( 'class', 'datatable-init table table-bordered table-hover' );
    switch (sub) {
        case '96':
            window.scrollTo(0,0);
            window.location.href = '/listairport/html';
            break;
        case '59':
            ArrayList('8','airspace');
            AspChecked('AFIZ');

            vol.style.visibility = 'visible';
            break;
        case '61':
        case '62':
        case '63':
        case '64':
            window.scrollTo(0,0);
            window.location.href = '/enroutehtml/' + sub;
            break;
        case '66':
            ArrayList('66','navaid')
            AspChecked('VOR/DME');

            vol.style.visibility = 'visible';
            // AtsList('66');
            break;
        case '68':
            ArrayList('68','waypoint')
            AspChecked('ENROUTE WPT');

            vol.style.visibility = 'visible';
            // AtsList('67');
            break;
        case '70':
        case '71':
            ArrayList('70','suas')
            AspChecked('ALERT');

            vol.style.visibility = 'visible';
            // AtsList('67');
            break;
        default:
            break;
    }

});
}
</script>
@endsection