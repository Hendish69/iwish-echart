<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model; 

class CecArrFeature extends Model
{ 
	protected $table = 'cec_arrfeature';
	protected $primaryKey = 'id';
	const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'created_at'; 
    protected $casts = [
		'reduction' => 'array', 
	];
	
	protected $fillable = [
		'name', 'reduction', 'description', 'fcode', 'reductionmin', 'reductionmax', 'effect','created_at','updated_at'
    ]; 
 //    public function airport(){
	//    return $this->hasMany(CecAirport::class, 'arr_features','id');
	// }

	public $incrementing = true; 
	
}
