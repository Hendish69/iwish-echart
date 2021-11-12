<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class LocIndicatorTemp extends Model
{

	protected $table = 'loc_indicator_temp';
	protected $primaryKey = 'loc_id';

	const UPDATED_AT = 'update_at';
    const CREATED_AT = 'create_at';
	protected $fillable = [
		'loc_id','loc_arptident','tbl', 'indicator', 'city', 'name', 'ctry', 'status', 'deleted', 'editor'
    ];

	public $incrementing = true;
	public function country()
	{
		return $this->hasMany(Country::class, 'ident', 'ctry');
	}
	
	
}
