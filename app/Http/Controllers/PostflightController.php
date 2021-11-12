<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\{PostflightReport, PostflightService, PostflightNavigation, PostflightLightning};
use App\ApiResponse;
use Exception;

class PostflightController extends Controller
{
	public function index(Request $request)
	{
		return View::make('pages.postflight.index');
	}

	public function airportList(Request $request)
	{
		$results = [];

		foreach (DB::table('arpt')->where('ctry', 'ID')->get() as $airport) {
			$results[] = [
				'value' => $airport->arpt_ident,
				'label' => $airport->icao.' - '.$airport->arpt_name.' - '.$airport->city_name,
			];
		}

		return ApiResponse::success($results);
	}

	public function list(Request $request)
	{
		if (Auth::user()->isAdmin()) {
			$reports = PostflightReport::orderBy('created_at', 'desc')->get();

			return ApiResponse::success($reports);
		} else {
			$reports = PostflightReport::where('created_by', Auth::user()->id)->orderBy('created_at', 'desc')->get();

			return ApiResponse::success($reports);
		}
	}

	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'flight_number' => 'required|string',
			'date' => 'required|date:Y-m-d',
			'departure' => 'required|string',
			'destination' => 'required|string',
			'route' => 'required|string',
			'aircraft' => 'required|string',
			'atd' => 'required',
			'ata' => 'required',
			'pic' => 'required|string',
			'id_no' => 'required|string',
			'email' => 'required|string',
		]);

		if ($validator->fails()) {
			return ApiResponse::fail($validator->errors());
		}

		try {
			DB::beginTransaction();

			$master = new PostflightReport();
			$master->fill($request->all());

			$master->save();

			if ($request->has('services')) {
				foreach ($request->services as $service) {
					$service['postflight_report_id'] = $master->id;
					$model = new PostflightService();

					$model->fill($service);

					$model->save();
				}
			}

			if ($request->has('navigations')) {
				foreach ($request->navigations as $navigation) {
					$navigation['postflight_report_id'] = $master->id;
					$model = new PostflightNavigation();
					$model->fill($navigation);

					$model->save();
				}
			}

			if ($request->has('lightnings')) {
				foreach ($request->lightnings as $lightning) {
					$lightning['postflight_report_id'] = $master->id;
					$model = new PostflightLightning();
					$model->fill($lightning);

					$model->save();
				}
			}

			DB::commit();

			return ApiResponse::success(true);
		} catch (Exception $e) {
			DB::rollback();
			return ApiResponse::error('query_error', $e->getMessage());
		}
	}

	public function view(Request $request, string $id)
	{
		$report = PostflightReport::find($id);

		return ApiResponse::success($report);
	}
}










































/**
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostflightTable extends Migration
{
    public function up()
    {
        Schema::create('postflight_report', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->date('date');
            $table->bigInteger('created_by');
            $table->string('flight_number');
            $table->string('departure');
            $table->string('destination');
            $table->string('route');
            $table->string('aircraft');
            $table->integer('atd');
            $table->integer('utc');
            $table->string('status');
            $table->text('meteorological_information');
            $table->string('pic');
            $table->string('id_no');
            $table->string('email');
            $table->string('birds')->nullable();
            $table->string('birds_location')->nullable();
            $table->string('birds_detail')->nullable();
            $table->dateTime('birds_at')->nullable();
            $table->text('suggestion');
            $table->boolean('is_correct')->nullable();
        });

        Schema::create('postflight_report_service', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('postflight_report_id');
            $table->timestamps();
            $table->string('name');
            $table->string('freq');
            $table->string('readibility');
            $table->string('distance');
            $table->string('flight_level');
            $table->string('procedure');
            $table->string('pharaseology');
        });

        Schema::create('postflight_report_navigation', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('postflight_report_id');
            $table->timestamps();
            $table->string('type');
            $table->string('ident');
            $table->string('freq');
            $table->string('reception_1');
            $table->string('reception_2');
            $table->string('reception_3');
            $table->string('reception_4');
        });

        Schema::create('postflight_report_lightning', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('postflight_report_id');
            $table->string('type');
            $table->string('reception');
        });
    }
    public function down()
    {
        Schema::dropIfExists('postflight_report');
        Schema::dropIfExists('postflight_report_service');
        Schema::dropIfExists('postflight_report_navigation');
        Schema::dropIfExists('postflight_report_lightning');
    }
}
*/