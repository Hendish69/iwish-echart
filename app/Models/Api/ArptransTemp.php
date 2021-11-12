<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class ArptransTemp extends Model
{
    use PostgisTrait;
	protected $table = 'arpt_trans_temp';
	protected $primaryKey = 'id';
	// protected $casts = [
  //       'proc_id' => 'string',
  //   ];
    protected $postgisFields = [
		'geom',
    ];
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'eff_date';
	protected $fillable = [
		'id','proc_id', 'arpt_ident','chart_type','rnav','sub_chart_type','trans_ident','rwy_id','rwy_trans','rt_type','vnav','nav_spec','geom','deleted','editor','status'
    ];
    public $incrementing = true;
    public function segment()
	{
		return $this->hasMany(ArptransegTemp::class, 'proc_id', 'proc_id');
	}
	public function airport()
	{
		return $this->hasMany(Airport::class, 'arpt_ident', 'arpt_ident');
	}
	public function runway()
	{
		return $this->hasMany(RwyPhysicalTemp::class, 'rwy_key', 'rwy_id');
	}
    
    
}
