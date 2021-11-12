<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class SuasSegmentTemp extends Model
{
	protected $table = 'suas_seg_temp';
	protected $primaryKey = 'id';
	protected $casts = [
		'suas_seg_id' => 'string',
		'suas_id' => 'string',
	];
	const UPDATED_AT = 'eff_date';
    const CREATED_AT = 'update_cycle';
	protected $fillable = [
        'id','suas_seg_id', 'suas_id', 'suas_seq', 'point1_lat', 'point1_long', 'shap', 'nav_id','arpt_ident', 'arc_dist', 'arc_dist2', 'ell_azimuth', 'arc_lat', 'arc_long', 'editor', 'remarks','status'
    ];
	
	public function navaid()
	{
		return $this->hasMany(NavaidTemp::class, 'nav_id', 'nav_id')->join('cod_nav_types','cod_nav_types.id','=','navaid_temp.type');;

	}

	public function airport()
	{
		return $this->hasMany(Airport::class, 'arpt_ident', 'arpt_ident');
	}
    public $incrementing = true;
    
}
