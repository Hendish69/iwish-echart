<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class Holding extends Model
{
    use PostgisTrait;
	protected $table = 'holding';
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
		return $this->hasMany(Navaid::class, 'nav_id', 'fix_id')->join('cod_nav_types','cod_nav_types.id','navaid.type');
	}
	public function waypoint()
	{
		return $this->hasMany(Waypoint::class, 'wpt_id', 'fix_id');
	}
	public function airport()
	{
		return $this->hasMany(Airport::class, 'arpt_ident', 'hld_type');
	}
	
	// public $incrementing = false;
}
