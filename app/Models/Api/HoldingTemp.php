<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class HoldingTemp extends Model
{
    use PostgisTrait;
	protected $table = 'holding_temp';
	protected $primaryKey = 'id';

	const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
	protected $postgisFields = [
        'geom',
		'poly',
	];
	protected $fillable = [
		'id','hld_type','fix_id','fix_cd','crs','turn','leg_length','leg_time','min_alt','max_alt','speed','notes','deleted','editor','eff_date','geom','mag','status','poly'
    ];
	public function navaid()
	{
		return $this->hasMany(NavaidTemp::class, 'nav_id', 'fix_id')->join('cod_nav_types','cod_nav_types.id','navaid_temp.type');
	}
	public function waypoint()
	{
		return $this->hasMany(WaypointTemp::class, 'wpt_id', 'fix_id');
	}
    public function airport()
	{
		return $this->hasMany(Airport::class, 'arpt_ident', 'hld_type');
	}
	
	// public $incrementing = false;
}
