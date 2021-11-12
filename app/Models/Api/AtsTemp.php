<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class AtsTemp extends Model
{
    use PostgisTrait;

	protected $table = 'ats_temp';
	protected $primaryKey = 'id';
	protected $casts = [
        'ats_id' => 'string',
	];
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
    protected $fillable = [
		'id','ats_id', 'ats_ident', 'ctry', 'seq_424', 'dir_424','direction','type', 'rnp_type', 'point', 'wpt_type','point2', 'wpt_type2', 'geom','track_out', 'track_in', 'dist', 'maa', 'mfa', 'mea_out', 'bidirect','seg_use', 'level', 'editor', 'deleted','status','src_id','page'
    ];
    protected $postgisFields = [
        'geom',
	];
  public $incrementing = true;
  public function remarks()
	{
		return $this->hasMany(AtsRemarksTemp::class, 'ats_id', 'ats_id');
  }
  public function wpt1()
	{
		return $this->hasMany(WaypointTemp::class, 'wpt_id', 'point');
  }
  
  public function nav1()
	{
      return $this->hasMany(NavaidTemp::class, 'nav_id', 'point')->join('cod_nav_types','cod_nav_types.id','navaid_temp.type');
  }
  
  public function wpt2()
	{
		return $this->hasMany(WaypointTemp::class, 'wpt_id', 'point2');
  }
  
  public function nav2()
	{
		return $this->hasMany(NavaidTemp::class, 'nav_id', 'point2')->join('cod_nav_types','cod_nav_types.id','navaid_temp.type');
	}
}
