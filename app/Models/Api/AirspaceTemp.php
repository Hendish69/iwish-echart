<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class AirspaceTemp extends Model
{
    use PostgisTrait;
	protected $table = 'airspace_temp';
	protected $primaryKey = 'id';
	protected $casts = [
        'ats_airspace_id' => 'string',
    ];
    protected $postgisFields = [
		'geom',
    ];
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
    public $incrementing = true;
    protected $fillable = [
        'ats_airspace_id', 'airspace_name', 'airspace_type', 'airspace_code','airspace_rnp', 'rvsm', 'icao_acc', 'icao_reg', 'ctry', 'rvsm_upper', 'rvsm_lower', 'ats_unit', 'editor', 'deleted','id', 'geom', 'arpt_ident','status'
    ];

    public function class()
	{
		return $this->hasMany(AirspaceClassTemp::class, 'asp_id', 'ats_airspace_id');
    }
    
    public function freq()
	{
		return $this->hasMany(FreqUsageTemp::class, 'asp_id', 'ats_airspace_id');
    }
    
    public function boundary()
	{
		return $this->hasMany(AirspaceSegmentTemp::class, 'asp_id', 'ats_airspace_id')->orderby('air_seq');
	}



}
