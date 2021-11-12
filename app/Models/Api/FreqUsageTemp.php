<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class FreqUsageTemp extends Model
{
	protected $table = 'freq_used_temp';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'eff_date';
    protected $fillable = [
		'id','arpt_ident', 'asp_id', 'freqid','seq','editor'
    ];

   
  public $incrementing = true;
  
  public function callsign()
	{
		return $this->hasMany(FrequencyTemp::class, 'id', 'freqid');
  }
  public function airport()
	{
		return $this->hasMany(Airport::class, 'arpt_ident', 'arpt_ident');
  }
  public function airspace()
	{
		return $this->hasMany(AirspaceTemp::class, 'ats_airspace_id', 'asp_id');
  }
  
  
}
