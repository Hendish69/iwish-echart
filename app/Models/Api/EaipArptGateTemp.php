<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class EaipArptGateTemp extends Model
{
	protected $table = 'eaip_arpt_gate_temp';
    protected $primaryKey = 'id';

    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'eff_date';
	protected $fillable = [
        'id','arpt_ident_gate', 'no_gate', 'gate_lat', 'gate_lon','aircraft_type','ramp_name','elevation','status','sequence', 'editor', 'page','src_id','apron_id','deleted'
    ];
	

	public $incrementing = true;
    public function apron()
	{
		return $this->hasMany(EaipApronTwyTemp::class, 'id', 'apron_id');
	}
}
