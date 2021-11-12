<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model; 

class CecAirport extends Model
{ 
	protected $table = 'cec_airport';
	protected $primaryKey = 'icao'; 
	public $timestamps = false;
	protected $casts = [
		'location' => 'array',
		
	];
	protected $fillable = [
		'icao', 'taxiout', 'gndholding', 'arrholding', 'approach', 'taxiin', 'location', 'dep_features', 'arr_features'
    ]; 
	public $incrementing = false;

	public function depFeature()
	{
		return $this->hasMany(CecArrFeature::class, 'id', 'dep_features');
	}
	public function arrFeature()
	{
		return $this->hasMany(CecArrFeature::class, 'id', 'arr_features');
	} 
	 
	// public function icao(){ 
	// 	return $this->icao; 
	// }
}
