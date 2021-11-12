<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model; 

class CecAcft extends Model
{ 
	protected $table = 'cec_acft';
	protected $primaryKey = 'icao'; 
	public $timestamps = false;
	public $incrementing = false;
	protected $fillable = [
		'icao', 'eridle', 'erfull', 'ertaxi', 'erclimb', 'erdescend', 'erholding','ercruise', 'description', 'erlanding', 'tstartup', 'ttakeoff', 'tlanding', 'rateclimb', 'ratedescend', 'tidle', 'erapch'
    ];  
}
