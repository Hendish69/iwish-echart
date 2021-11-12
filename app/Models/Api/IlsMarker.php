<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class IlsMarker extends Model
{
    use PostgisTrait;
	protected $table = 'arpt_marker';
	protected $primaryKey = 'id';
	protected $casts = [
        'mrkr_id' => 'string',
    ];
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'eff_date';
    protected $fillable = [
		'id','mrkr_id','ils_id','loc_id', 'mrkr_type','freq','co_loc','geom', 'bear', 'elev','editor', 'deleted','opr_hrs', 'remarks','status'
    ];
    // protected $guarded = ['geom','dmegeom'];
    protected $postgisFields = [
        'geom',
	];
  public function navaid()
	{
		return $this->hasMany(Navaid::class, 'nav_id', 'loc_id');
	}
	public $incrementing = true;
}
