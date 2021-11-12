<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class Volcano extends Model
{
	use PostgisTrait;
	
	protected $table = 'tm_volcano';
	protected $primaryKey = 'va_no';
	protected $casts = [
        'va_no' => 'string',
	];
	protected $keyType = 'string';
	const UPDATED_AT = null;
    const CREATED_AT = null;
	protected $fillable = [
		'va_no', 'va_name', 'va_state', 'va_subregion', 'va_summit_elevm', 'va_status', 'va_last_update', 'va_fir', 'va_geom'
    ];
    protected $postgisFields = [
		'va_geom',
	];
	


	public $incrementing = false;

	public function cdm()
    {
        return $this->hasOne(TxCdm::class, 'va_no', 'va_no');
    }

    public function ashtam()
	{
		return $this->hasMany(TxAshtam::class, 'ashtam_volcano_number', 'va_no')->orderby('ashtam_update_time','desc');
	}
	public function vona()
	{
		return $this->hasMany(TxVona::class, 'smithsonian_id', 'va_no')->orderby('issued','desc');
    }
    
}
