<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\RequestParamHandler;
use App\ApiResponse;
use App\Models\Api\UserLog;
use Bosnadev\Database\Schema\Builder;
use Doctrine\DBAL\Schema\Table;

class EchartController extends Controller
{
	public function getmenuawal(Request $request,string $tbl)
	{
        if ($tbl=='MENUAWAL'){
            $frq = "select count(a) from cntrsys where a=md5('menu') group by a";

        } else if ($tbl=='JMLMENU'){
            $frq = " select id from cntrsys where a=md5('menu')";
        }else if ($tbl=='USRLOG'){
            $frq = "select id from userlog";
        }
        $frq= DB::select(DB::raw($frq));
        return ApiResponse::success($frq);
    }

    public function getuserlog(Request $request, RequestParamHandler $rpm)
	{
		$builder = UserLog::query();

		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
    }
    public function getuserlogsave(Request $request, RequestParamHandler $rpm)
	{
		$builder = UserLog::query();

		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
    }

}
