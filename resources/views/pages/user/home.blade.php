@extends('layouts.app')
@section('head')
<style type="text/css">
    .floating{
        position: absolute;
        bottom: 0px;
        right: 0px;
        z-index: 1009;
        border: 1px solid #dbdfea;
        border-radius: 5px;
    }
    .nk-split-page, .nk-wrap-nosidebar .nk-content {
        min-height: unset!important;
    }
  /*  @media only screen and (max-width: 768px) {
        .footer-activities{
            display: none;
        }
    }*/
</style>
@endsection
@section('template_title')
    {{ Auth::user()->name }}'s' Homepage
@endsection

@section('template_fastload_css')
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-12" >

                @include('panels.welcome-panel')

            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
@endsection
