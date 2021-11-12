<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class RequestParamHandler
{
	public function process(Request $request, Builder $builder, int $perPage = 10)
	{
		foreach ($request->all() as $field => $find) {
			if ($field == 'paginate' || $field == 'page' || $field == 'or' || $field == 'sort' || $field == 'per_page' || $field == 'limit') {
				continue;
			}
				$builder->where($field, $find);
			// dd($builder);
		}
		// airports?sort=arpt_ident:asc
		if ($request->has('sort')) {
			$sorts = explode(':', $request->get('sort'));
			if($sorts[1] == 'asc' || $sorts[1] == 'desc') $sorts[1]= $sorts[1].' NULLS LAST';
			$builder->orderByRaw($sorts[0]." ".$sorts[1]);
		}
		if ($request->has('limit')) {
			
			$builder->limit(1);
		}

		if ($request->has('or')) {
			$orwh = explode(':', $request->get('or'));
			$builder->orwhere($orwh[0], $orwh[1]);
		}

		if ($request->has('per_page')) {
			$perPage = $request->get('per_page');
        }


		if ($request->has('paginate')) {
			return $builder->paginate($perPage);
		}

		return $builder->get();
	}
}
