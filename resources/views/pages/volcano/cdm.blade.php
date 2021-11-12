@extends('layouts.app')

@section('template_title')
    CDM
@endsection

@section('head')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="nk-wrap">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4>Volcano Colaborative Decision Making (CDM)</h4>
                    </div><!-- .nk-block-head-content -->
                </div><!-- .nk-block-between -->
            </div>
                <div class="col-sm-3 text-left">
                    {{-- <div class="form-group">
                        <div class="form-control-wrap">
                            <div class="form-icon form-icon-left">
                                <em class="icon ni ni-search"></em>
                            </div>
                            <input type="text" class="form-control form-round" v-model="search" id="default-03" placeholder="Search..">
                        </div>
                    </div> --}}
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <template v-if="iscdm">
                        <div class="col-sm-4 text-left mt-3 pl-0">
                            <a v-on:click="newdata()" class="btn btn-dim btn-success"><i class="icon ni ni-plus"></i> New Group</a>
                        </div>
                        <div class="card card-preview  mt-1">
                            <div class="card-inner">
                                    <table class="datatable-init table table-bordered table-hover" id="table-content">
                                        <thead class="thead-dark">
                                            <tr align="center">
                                                <th>NO</th>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th>Last Update</th>
                                                <!-- git<th>Informations</th> -->
                                            </tr>
                                        </thead>
                                        <tbody id="cdmlist">
                                            @foreach($cdms as $cdm)
                                                <tr class="nk-tb-item">

                                                    <td align="center" style="cursor:pointer"><a onclick="cdmlog({{ $cdm->va_no  }})">{{ $cdm->va_no  }}</a></td>
                                                    <td style="cursor:pointer"><a onclick="cdmlog({{ $cdm->va_no  }})">{{ $cdm->room_name  }}</a></td>
                                                    <td align="center"> 
                                                        @if ($cdm->va_status ==4)
                                                            <span class="tb-odr-status">
                                                                <strong style="color:#CC0505; font-weight:bolder;cursor:pointer"><a onclick="cdmlog({{ $cdm->va_no  }})">RED</a></strong>
                                                            </span>
                                                        @endif
                                                        @if ($cdm->va_status ==3)
                                                            <strong style="color:#FF9100; font-weight:bolder;cursor:pointer"><a onclick="cdmlog({{ $cdm->va_no  }})">ORANGE</a></strong>
                                                        @endif
                                                        @if ($cdm->va_status ==2)
                                                            <span class="tb-odr-status">
                                                                <strong style="color:#e7d107; font-weight:bolder;cursor:pointer"><a onclick="cdmlog({{ $cdm->va_no  }})">YELLOW</a></strong>
                                                            </span>
                                                        @endif
                                                        @if ($cdm->va_status==1)
                                                            <strong style="color:#179638; font-weight:bolder;cursor:pointer"><a onclick="cdmlog({{ $cdm->va_no  }})">GREEN</a></strong>
                                                        @endif
                                                    </td>
                                                    <td style="cursor:pointer"><a onclick="cdmlog({{ $cdm->va_no  }})">{{ $cdm->cdm_date}}</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                        </template>
                        <template v-if="newgroup">
                            <div class="card card-preview  mt-1">
                                <div class="card-inner">
                                    <div class="col-md-6">
                                        <strong>Volcano</strong>
                                        <br>
                                        <select selected="selected" @click="selecttype()" class="form-control" v-html="grouplist">
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Status</strong>
                                        <br>
                                        <select selected="selected" @click="selecttype()" class="form-control">
                                            <option value="1">Green Alert</option>
                                            <option value="2">Yellow Alert</option>
                                            <option value="3">Orange Alert</option>
                                            <option value="4">Red Alert</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Description</strong>
                                        <br>
                                        <input type="text" class="form-control" id="description" name="description">
                                    </div>
                                </div>
                            </div>
                            <div class="card card-preview  mt-1">
                                <div class="nk-block-between">
                                    <div class="nk-block-head-content">
                                        <h6>Participants</h6>
                                    </div><!-- .nk-block-head-content -->
                                </div><!-- .nk-block-between -->
                                <div class="card-inner">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button onclick="backtolist()" class="btn btn-dim btn-success"><i class="icon ni ni-plus-circle-fill"></i> Add Participants</button>
                                            &nbsp;
                                            <button class="btn btn-dim btn-primary" id="btn_formulir"><i class="icon ni ni-save-fill"></i> Process</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                        <strong>Users</strong>
                                        <br>
                                        <select selected="selected" @click="selecttype()" class="form-control" v-html="userlist">
                                        </select>
                                    </div>
                                <div class="card-inner">
                                    <table class="datatable-init table table-bordered table-hover" id="table-content">
                                        <thead class="thead-dark">
                                            <tr align="center">
                                                <th>No</th>
                                                <th>First Name</th>
                                                <th>Last name</th>
                                                <th>Email</th>
                                                <!-- <th>Contact</th> -->
                                                <th>Position</th>
                                                <th>Unit</th>
                                                <!-- git<th>Informations</th> -->
                                            </tr>
                                        </thead>
                                        <tbody id="cdmlist">
                                            @foreach($users as $no=> $usr)
                                                <tr class="nk-tb-item">

                                                    <td align="center" style="cursor:pointer"><a onclick="cdmlog({{ $usr->id  }})">{{ $no +1  }}</a></td>
                                                    
                                                    <td align="center" style="cursor:pointer"><a onclick="cdmlog({{ $usr->id  }})">{{ $usr->first_name  }}</a></td>
                                                    <td style="cursor:pointer"><a onclick="cdmlog({{ $usr->id  }})">{{ $usr->last_name  }}</a></td>
                                                    <td style="cursor:pointer"><a onclick="cdmlog({{ $usr->id  }})">{{ $usr->email}}</a></td>
                                                    <!-- <td style="cursor:pointer"><a onclick="cdmlog({{ $usr->id  }})">{{ $usr->user_phone}}</a></td> -->
                                                    <td style="cursor:pointer"><a onclick="cdmlog({{ $usr->id  }})">{{ $usr->user_position}}</a></td>
                                                    <td style="cursor:pointer"><a onclick="cdmlog({{ $usr->id  }})">{{ $usr->user_unit}}</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                   
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            <!-- </template>
            <template v-else>
                <div class="text-center">
                    <strong>Loading...</strong>
                    <br>
                    <br>
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status" aria-hidden="true"></div>
                </div>
            </template> -->
        </div>
    </div>
    

@endsection
@section('footer_scripts')
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script type="text/javascript">
var cdm=@json($cdms);
var va=@json($volcanos);
var usr=@json($users);

Vue.config.silent = true
var AppVue = new Vue({
    el: '#app',
   
    data() {
        return {
            iscdm:true,
            newgroup:false,
            editgroup:false,
            grouplist:'',
            userlist:'',

        }
  },
  mounted() {
   
  },
  methods: {
    newdata(){
        // console.log(cdm,va);
        this.iscdm=false,
        this.newgroup=true;
        va.forEach(v=>{
            var ix=cdm.findIndex(x=>x.va_no===v.va_no)
            // console.log(ix)
            if (ix==-1){
                this.grouplist +='<option value="v.va">'+v.va_name+ ' - ' + v.va_subregion+'</option>';

            }
        })
        usr.forEach(v=>{
            var ix=cdm.findIndex(x=>x.va_no===v.va_no)
            // console.log(ix)
            if (ix==-1){
                this.userlist +=' <option value="v.id">'+v.first_name+ ' - ' + v.email+'</option>';

            }
        })
                
    }

    }
})
var tabe = $('#table-content_filter');
    // $("#table-content_filter :input").addClass("form-round");
    tabe.append(" <b>Appended text</b>.");
   

    function cdmlog(va_no){
    window.scrollTo(0,0);
    window.location.href = '/cdmlogdetail/' + va_no;
  // return view('pages.volcano.cdmdetail');
//   return view('daftarPeriksa')->with(['req' => $req]);
//   window.location.href = '/cdmlog/';
//  qcdmdetail(va_no)
 // history.back(1);

}
</script>
@endsection