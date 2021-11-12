<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class Ils extends Model
{
    use PostgisTrait;
	protected $table = 'arpt_ils';
	protected $primaryKey = 'id';
	protected $casts = [
        'ils_id' => 'string',
    ];
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'eff_date';
    protected $fillable = [
		'arpt_ident', 'rwy_id', 'ils_ident', 'ils_name', 'ils_cat', 'freq', 'bear', 'gs_freq', 'ch', 'gs_pos', 'gs_angle', 'gs_hgt', 'gs_elev', 'dme_avail', 'editor', 'deleted','id','geom', 'gs_geom', 'ils_id', 'nav_id', 'opr_hrs', 'remarks','status'
    ];
    // protected $guarded = ['geom','dmegeom'];
    protected $postgisFields = [
        'geom',
        'gs_geom',
        'dmegeom',
  ];
  public function marker()
	{
		return $this->hasMany(IlsMarker::class, 'ils_id', 'ils_id');
	}
  public function navaid()
	{
		return $this->hasMany(Navaid::class, 'nav_id', 'nav_id');
	}
  public function airport()
	{
		return $this->hasMany(Airport::class, 'arpt_ident', 'arpt_ident');
	}
  public function thr()
	{
		return $this->hasMany(RwyPhysical::class, 'rwy_key', 'rwy_id');
	}

	public $incrementing = true;
}
