<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;  

class CecFpl extends Model
{
	protected $table = 'cec_fpl';
	protected $primaryKey = 'id';
	const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
		'acid', 'ftype', 'eobt', 'eet', 'atd', 'ata', 'acft_id', 'adep_id', 'ades_id', 'emision_id', 'rfl'
    ];
   
  	public $incrementing = true;
 	public function acft(){
		return $this->hasOne(CecAcft::class, 'icao', 'acft_id');
	}
	 
  	public function depAirport(){
		return $this->hasOne(CecAirport::class, 'icao', 'cec_fpl.adep_id');
	}
	public function desAirport(){
		return $this->hasOne(CecAirport::class, 'icao', 'cec_fpl.ades_id');
	}
	 
	public function emission(){
		return $this->hasOne(CecEmission::class, 'id', 'emission_id');
	}
}
