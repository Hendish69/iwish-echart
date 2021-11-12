@extends('layouts.app')

@section('template_title')
    AIP Submission
@endsection

@section('head')
<style>
.container {
  position: relative;
  width: 100%;
  overflow: hidden;
  padding-top: 56.25%; /* 16:9 Aspect Ratio */
}

.responsive-iframe {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  width: 100%;
  height: 400px;
  border: none;
}
</style>
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
                                        @foreach($codeaip as $cod)
                                            <option value="{{ $cod->id }}">{{ $cod->sub_id }} {{ $cod->definition }} </option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">eAIP Sub Section</label>
                            <select class="custom-select" name="subsection" id="subsection" onchange="ListSubSection()">
                                <option selected>Select Subsection</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

@endsection
@section('footer_scripts')

<script type="text/javascript">
$("#ad22list").hide();
$("#showadpdf").hide();

var submenu=@json($id);
let uri = 'api/eaip/menu/two/';
let second_opt = 'subsection';
let section = $('#section') ;
section.select2me(second_opt,uri);
// console.log(section)
$(function() { 
    if(section.val() > 0){
        section.trigger('change');
    }
});

function backtolist(){
    aboutvol("ad22list");
    aboutvol("showadpdf");
}

function ListSubSection() {

    $(document).on('change', '#subsection', function (e) {
    e.preventDefault();
        // let sub = document.getElementById( "subsection" ).value;
        let ss = document.getElementById("subsection");
        let sub = ss.value;
        let sub_text= ss.options[ss.selectedIndex].text; 
        console.log(sub,sub_text,submenu)
        switch ( sub ) {
            case '17':
            case '18':
            case '34':
                window.location.href = '/gen02/'+sub+'/'+submenu;
                break;
            case '30':
                window.location.href = '/gen22/'+submenu;
                break;
            case '32':
                window.location.href = '/gen24/'+submenu;
                break;
            case '33':
                window.location.href = '/gen25/'+submenu;
                break;
            case '96':
                window.location.href = '/listairport/'+submenu;

                break;
            case '59':
                window.scrollTo(0,0);
                window.location.href = '/listairpace/'+submenu;

                // vol.style.visibility = 'visible';
                break;
            case '61':
            case '62':
            case '63':
            case '64':
                window.scrollTo(0,0);
                if (submenu=='edit'){
                    window.location.href = '/listats/'+ sub;
                }else if (submenu=='html'){
                    window.location.href = '/enroutehtml/' + sub;
                }
                break;
            case '66':
                window.scrollTo(0,0);
                window.scrollTo(0,0);
                if (submenu=='edit'){
                    window.location.href = '/navaid';
                }else if (submenu=='html'){
                    window.location.href = '/enr41/66';
                }
            
            
                break;
            case '68':
                window.scrollTo(0,0);
                if (submenu=='edit'){
                    window.location.href = '/waypoint';
                }else if (submenu=='html'){
                    window.location.href = '/enr41/68';
                }
                
            
                break;
            case '70':
            case '71':
                window.scrollTo(0,0);
                window.location.href = '/listsuas/'+submenu+'/' +sub;
                break;
          
            default:
                window.scrollTo(0,0);
                if (submenu=='edit'){
                     let subs_ = sub_text.split("/");
                    let sub_ = sub_text.replace("/", " OR ");
                    window.location.href = '/gen.edit/'+sub+'/'+sub_;
                }else if (submenu=='html'){
                    window.location.href = 'text/html/' + sub;
                }
                break;
        
    }



    });
}

</script>
@endsection