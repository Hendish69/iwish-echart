<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Msa extends Model
{
	protected $table = 'msa';
	protected $primaryKey = 'id';
	const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
	protected $fillable = [
		'id','msa_id','ident','nav_id','arpt_ident','wpt_id','rad','editor','deleted','eff_date','taa','type'
    ];
	
	public function chart()
	{
		return $this->hasMany(ChartProperties::class, 'msa_id', 'msa_id')->orderby('chart_type','asc')->orderby('page','asc');
	}
	public function navaid()
	{
		return $this->hasMany(NavaidTemp::class, 'nav_id', 'nav_id');
	}
	public function waypoint()
	{
		return $this->hasMany(WaypointTemp::class, 'wpt_id', 'wpt_id');
	}
    public function airport()
	{
		return $this->hasMany(Airport::class, 'arpt_ident', 'arpt_ident');
	}

	public function area()
	{
		return $this->hasMany(MsaArea::class, 'msa_id', 'msa_id')->orderby('area','asc');
	}
	
	public $incrementing = true;
}
