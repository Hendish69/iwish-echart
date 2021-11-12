<?php
namespace App\Models\Api;
use Illuminate\Database\Eloquent\Model;


class TxAshtam extends Model //implements AuthenticatableContract, AuthorizableContract
{
    // use Authenticatable, Authorizable;

	protected $table = 'tx_ashtam';
    protected $primaryKey = 'ashtam_id';

    const UPDATED_AT = null;
    const CREATED_AT = null;
	protected $fillable = [
        'ashtam_id', 'ashtam_number', 'ashtam_update_time', 'ashtam_fir', 'ashtam_utc', 'ashtam_utc_issued', 'ashtam_volcano', 'ashtam_volcano_number', 'ashtam_navaid_lat_dms', 'ashtam_navaid_lon_dms', 'ashtam_navaid_lat', 'ashtam_navaid_lon', 'ashtam_alert_code', 'ashtam_ahve', 'ashtam_ash_direction', 'ashtam_affected_route', 'ashtam_air_space', 'ashtam_source', 'ashtam_plain_language', 'ashtam_remarks', 'ashtam_date_created'
    ];


    public $incrementing = true;
    public function forecast()
	{
		return $this->hasMany(TxAshtamForecast::class, 'ashtam_id', 'ashtam_id')->orderby('ashtam_fcst_hr');
	}
    
   
}
