<?php
namespace App\Models\Api;

// use Illuminate\Auth\Authenticatable;
// use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
// use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
// use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;


class TxAshtamForecast extends Model //implements AuthenticatableContract, AuthorizableContract
{
    // use Authenticatable, Authorizable;

	protected $table = 'tx_ashtam_forecast';
    protected $primaryKey = 'ashtam_id';

    const UPDATED_AT = null;
    const CREATED_AT = null;
	protected $fillable = [
        'ashtam_id', 'ashtam_fcst_hr', 'ashtam_desc', 'ashtam_idd'
    ];


    public $incrementing = true;
   
}
