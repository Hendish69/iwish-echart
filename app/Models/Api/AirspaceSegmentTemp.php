<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class AirspaceSegmentTemp extends Model
{
	protected $table = 'airspace_seg_temp';
	protected $primaryKey = 'id';
	protected $casts = [
        'asp_id' => 'string',
	];
	const UPDATED_AT = 'eff_date';
    const CREATED_AT = 'update_cycle';
	protected $fillable = [
        'id','asp_seg_id', 'asp_id', 'air_seq', 'point1_lat', 'point1_long', 'shap', 'nav_id', 'arpt_ident', 'rwy_id', 'arc_dist', 'arc_lat', 'arc_long','remarks','status'
    ];
	
	public $incrementing = true;
	
	public function navaid()
	{
		return $this->hasMany(NavaidTemp::class, 'nav_id', 'nav_id')->join('cod_nav_types','cod_nav_types.id','=','navaid_temp.type');
	}

	public function airport()
	{
		return $this->hasMany(AirportTemp::class, 'arpt_ident', 'arpt_ident');
	}
    
}
