<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class Ats extends Model
{
    use PostgisTrait;

	protected $table = 'ats';
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
		return $this->hasMany(AtsRemarks::class, 'ats_id', 'ats_id');
  }
  
  public function wpt1()
	{
		return $this->hasMany(Waypoint::class, 'wpt_id', 'point');
  }
  
  public function nav1()
	{
    // if (substr('point',0,3)=='NAV'){
      return $this->hasMany(Navaid::class, 'nav_id', 'point')->join('cod_nav_types','cod_nav_types.id','navaid.type');
    // }else{
    //   return $this->hasMany(Waypoint::class, 'wpt_id', 'point')->where(substr('point',0,3)=='WPT');
    // }
		
  }
  
  public function wpt2()
	{
		return $this->hasMany(Waypoint::class, 'wpt_id', 'point2');
  }
  
  public function nav2()
	{
		return $this->hasMany(Navaid::class, 'nav_id', 'point2')->join('cod_nav_types','cod_nav_types.id','navaid.type');
	}
  
}
