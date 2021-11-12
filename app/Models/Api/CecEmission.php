<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model; 

class CecEmission extends Model
{
    protected $table = 'cec_emission';
    protected $primaryKey = 'id';
     
    protected $fillable = [
        'emissionstart', 'emissiontaxiout', 'emissiongndholding', 'emissiontakeoff', 'emissionclimb', 'emissioncruise', 'emissiondescend', 'emissionholding', 'emissionapproach', 'emissionlanding', 'emissiontaxiin', 'fpl_id', 'emissiontotal'
    ];

    public $incrementing = true;
    public function fpl()
    {
        return $this->belongsTo(CecFpl::class, 'emission_id', 'fpl_id');
    }
}
