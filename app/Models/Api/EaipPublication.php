<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class EaipPublication extends Model
{
	protected $table = 'eaip_publication';
    protected $primaryKey = 'id';

    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
	protected $fillable = [
        'id', 'originator', 'pia', 'dt_type', 'part', 'section', 'sub_section', 'pub_date', 'eff_date', 'status', 'status_by', 'remarks'
    ];
	

    public $incrementing = true;
    public function detail()
	{
		return $this->hasMany(EaipPublicationDetail::class, 'eaip_pub_id', 'id');
	}
}
