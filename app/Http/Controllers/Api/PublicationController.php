<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\EaipPublication;
use App\Models\Api\EaipPublicationDetail;
use App\Models\Api\Publication;
use App\Models\Api\User;
use App\Models\Api\Airac;
use App\Models\Api\SourceNr;
use App\Models\Api\SourceNrSeg;
use App\Models\Api\UserGroup;
use App\Models\Api\RawdataPub;
use App\Models\Api\RawdataPubDetail;
use App\Models\Api\RawdataPubNotam;
use App\Models\Api\TbReff;
use App\Models\Api\RawdataPubChart;
use App\Models\Api\CodStatusRequest;
use App\Models\Api\Org;
use App\Models\Api\RawdataPubAtt;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;

class PublicationController extends Controller
{
    public function index(Request $request, RequestParamHandler $rpm)
	{
		$builder = EaipPublication::query()
			->with(['detail']);
		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
	}

	public function getsourcenr(Request $request, RequestParamHandler $rpm)
	{
		$builder = SourceNr::query()->with(['note']);
		
		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
	}
	public function sourcenrsave(Request $request)
	{
		// dd($request);
		
		if ($request->status=='R'){
			$source = SourceNr::find($request->id);
			$source->update($request->all());
		}else{
			$last = SourceNr::latest('id')->first();
            // $request->id = $last->id + 1;
            $request->merge([
                'id' => $last->id + 1,
            ]);
			SourceNr::create($request->all());
		}
		if ($request->status_supp=='R'){
			$source = SourceNrSeg::find($request->id_supp);
			$source->update($request->all());
		}else if ($request->status_supp=='N'){
			$last = SourceNrSeg::latest('id')->first();
            // $request->id = $last->id + 1;
            $request->merge([
                'id' => $last->id + 1,
            ]);
			SourceNrSeg::create($request->all());
		}
		return redirect()->back()->withInput();
	}
	public function sourcenrsegsave(Request $request)
	{
		dd($request);
		if ($request->status=='R'){
			$source = SourceNr::find($request->id);
			$source->update($request->all());
		}else{
			$last = SourceNr::latest('id')->first();
            // $request->id = $last->id + 1;
            $request->merge([
                'id' => $last->id + 1,
            ]);
			SourceNr::create($request->all());
		}
		
		if ($request->status_supp=='R'){
			$source = SourceNrSeg::find($request->id_supp);
			$source->update($request->all());
		}else if ($request->status_supp=='N'){
			$last = SourceNrSeg::latest('id')->first();
            // $request->id = $last->id + 1;
            $request->merge([
                'id' => $last->id + 1,
            ]);
			SourceNrSeg::create($request->all());
		}

		return redirect()->back()->withInput();
	}
	public function airac(Request $request, RequestParamHandler $rpm)
	{
		$builder = Airac::query();
		
		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
	}
	public function getnotam(Request $request, RequestParamHandler $rpm)
	{
		$builder = RawdataPubNotam::query()->with(['users']);
		
		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
	}

	public function getchartaffect(Request $request, RequestParamHandler $rpm)
	{
		$builder = RawdataPubChart::query()->with(['chart']);
		
		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
	}
	public function uploadfile(Request $request)
    {
		        // do we have an image to process?
        if($request->image){
            //$filename = substr( md5( $student->id . '-' . time() ), 0, 15) . '.' . $request->file('image')->getClientOriginalExtension();
            $filename = $student->id.'-'.substr( md5( $student->id . '-' . time() ), 0, 15) . '.jpg'; // for now just assume .jpg : \
            $path = public_path('alumni-photos/' . $filename);
            Image::make($request->image)->orientate()->fit(500)->save($path);

            // now update the photo column on the student record
            $student->photo = $filename;
            $student->save();
        }

        return 'success';
	}
	
	public function uploadfile___(Request $request)
    {
        $response = null;
        $user = (object) ['image' => ""];

        if ($request->hasFile('image')) {
            $original_filename = $request->file('image')->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $destination_path = './Upload/Datapendukung/';
            $image = 'U-' . time() . '.' . $file_ext;

            if ($request->file('image')->move($destination_path, $image)) {
                $user->image = '/Upload/Datapendukung/' . $image;
                return $this->responseRequestSuccess($user);
            } else {
                return $this->responseRequestError('Cannot upload file');
            }
        } else {
            return $this->responseRequestError('File not found');
        }
    }

	public function rawpubatt(Request $request, RequestParamHandler $rpm)
	{
		$builder = RawdataPubAtt::query();
		
		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
	}
	public function saverawpubatt(Request $request)
	{
		$temp = RawdataPubAtt::create($request->all());
        return ApiResponse::success($temp->rawdata_att_id);
		// RawdataPubDetail::create($request->all());
	}

	public function tablereff(Request $request, RequestParamHandler $rpm)
	{
		$builder = TbReff::query();
		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
	}
	public function publication(Request $request, RequestParamHandler $rpm)
	{
		$builder = Publication::query()->with(['rawpub']);
		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
	}

	public function rawpubdetail(Request $request, RequestParamHandler $rpm)
	{
		$builder = RawdataPubDetail::query()
			->join('tb_reff','tb_reff.reff_code','rawdata_pub_detail.req_action')
			->leftjoin('users','users.id','rawdata_pub_detail.status_pic')
			->orderby('tb_reff.reff_order');

		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
	}
	public function saverawpubdetail(Request $request)
	{
		$temp = RawdataPubDetail::create($request->all());
        return ApiResponse::success($temp->rawdata_detail_id);
		// RawdataPubDetail::create($request->all());
	}

	public function updaterawpubdetail(Request $request, string $id)
	{
		$airport = RawdataPubDetail::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

		return ApiResponse::success($airport->fresh());
	}

	public function updatepublication(Request $request, string $id)
	{
		$airport = Publication::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

		return ApiResponse::success($airport->fresh());
	}

	public function savepublication(Request $request)
	{
		Publication::create($request->all());
	}

	public function save(Request $request)
	{
		EaipPublication::create($request->all());
	}

	public function update(Request $request, string $id)
	{
		$airport = EaipPublication::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

		return ApiResponse::success($airport->fresh());
	}

	public function remove(Request $request, string $id)
	{
		$ats = EaipPublication::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
    }
    

    public function codstatus(Request $request, RequestParamHandler $rpm)
	{
		$builder = CodStatusRequest::query();
		// ->with(['raw_arpt']);

		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
	}

	public function rawdataindex(Request $request, RequestParamHandler $rpm)
	{
		$builder = RawdataPub::query()
		->with(['airport'=> function($query) {
			return $query->with(['auth' => function($query) {
				return $query->with(['users' => function($query) {
					return $query->with(['roleuser'  => function($query) {
					//return $query->with(['usergroup'  => function($query) {
						return $query->with(['roles']);
					}
					]);
				}
				]);
					}
				]);
			}
			])
		->with(['users' => function($query) {
			return $query->with(['roleuser'  => function($query) {
				return $query->with(['roles']);
			}
			]);
		}
		])
		->with(['attach'])
		->with(['source'])
		// ->with(['navaid' => function($query) {
		// 	return $query->join('cod_nav_types','cod_nav_types.id','navaid_temp.type');
		// 		}
		// 	])
		// ->with(['waypoint' => function($query) {
		// 		return $query->join('cod_wpt_usage','cod_wpt_usage.id','waypoint_temp.usage_cd');
		// 			}
		// 	])
		->with(['ats'
			])
		->with(['notam'])
		->with([
			'detail' => function($query) {
				return $query->with(['users'])->join('tb_reff','tb_reff.reff_code','rawdata_pub_detail.req_action')
								->where('tb_reff.reff_group','0020')
								->orderby('rawdata_pub_detail.rawdata_detail_id')
								->orderby('tb_reff.reff_order')
								;
			}
		]);
		
		$results = $rpm->process($request, $builder);
		// dd($results);
		return ApiResponse::success($results);
	}

	public function rawdatadalamprosespublication(Request $request, RequestParamHandler $rpm)
	{
		// dd( $request->tablename);
		if ( $request->tablename=='arpt'){
			$builder = RawdataPub::query()->where('tablename','=','arpt')
					->where('status_raw','>',70)->where('status_raw','<>','100');
		}else{
			$builder = RawdataPub::query()->where('tablename','<>','arpt')->where('status_raw','>',70)->where('status_raw','<>','100');
		}
		
		$results = $rpm->process($request, $builder);
		// dd($results);
		return ApiResponse::success($results);
	}
	

	public function saverawdata(Request $request)
	{
		RawdataPub::create($request->all());
	}

	public function updaterawdata(Request $request, string $id)
	{
		$airport = RawdataPub::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

		return ApiResponse::success($airport->fresh());
	}

	public function removerawdata(Request $request, string $id)
	{
		$ats = RawdataPub::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
	}

	public function savedetail(Request $request)
	{
		EaipPublicationDetail::create($request->all());
	}

	public function updatdetail(Request $request, string $id)
	{
		$airport = EaipPublicationDetail::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

		return ApiResponse::success($airport->fresh());
	}

	public function removedetail(Request $request, string $id)
	{
		$ats = EaipPublicationDetail::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
    }
    
    public function orglist(Request $request, RequestParamHandler $rpm)
	{
        $builder = Org::query();

    $results = $rpm->process($request, $builder);

    return ApiResponse::success($results);
    }

    public function usergrouplist(Request $request, RequestParamHandler $rpm)
	{
        $builder = UserGroup::query();

    $results = $rpm->process($request, $builder);

    return ApiResponse::success($results);
    }
    
    public function userindex(Request $request, RequestParamHandler $rpm)
	{
        $builder = User::query()->select('users.*','country.country')
		->join('country','country.ident','users.user_country')
		->with(['org'])->with(['usergroup'])->with(['pia']);
        // ->join('user_group','user_group.group_id','tm_users.group_id')
        // ->join('org','org.org_id','tm_users.org_id')
        // ->leftjoin('arpt_auth','arpt_auth.id','tm_users.pia_id');
        // ->with(['usergroup'])
        // ->with(['org']);

    $results = $rpm->process($request, $builder);

    return ApiResponse::success($results);
	}
	

	public function saveuser(Request $request)
	{
		User::create($request->all());
	}

	public function updateuser(Request $request, string $id)
	{
        $airport = User::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

		return ApiResponse::success($airport->fresh());
    }
    

}
