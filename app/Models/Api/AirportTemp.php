<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class AirportTemp extends Model
{
    use PostgisTrait;

	protected $table = 'arpt_temp';
	protected $primaryKey = 'arpt_ident';
	protected $casts = [
		'arpt_ident' => 'string',
		
	];
	protected $keyType = 'string';
	const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'edit_date';
	protected $fillable = [
		'arpt_ident','city_name', 'ctry', 'iata', 'icao', 'arpt_name', 'time', 'type', 'elev', 'mag_var', 'type_of_traffic', 'editor', 'geom','vol','auth','raim','pia_id','status'
    ];
    protected $postgisFields = [
		'geom',
	];

	public $timestamps = false;
	public $incrementing = false;

	public function runways()
	{
		return $this->hasMany(RunwayTemp::class, 'arpt_ident', 'arpt_ident');
	}
	
	public function info()
	{
		return $this->hasMany(EaipChartContentTemp::class, 'arpt_ident', 'arpt_ident');
	}
	public function adc()
	{
		return $this->hasMany(AirportAdc::class, 'arpt_ident', 'arpt_ident');
	}
	public function auth()
	{
		return $this->hasMany(ArptAuth::class, 'id', 'auth');
	}
	public function country()
	{
		return $this->hasMany(Country::class, 'ident', 'ctry');
	}
	public function tatl()
	{
		return $this->hasMany(AirportTaTl::class, 'arpt_ident', 'arpt_ident');
	}
	
}
