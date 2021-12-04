<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Td_aip extends Model
{

	protected $table = 'td_aip';
	protected $primaryKey = 'id_aip';


	const UPDATED_AT = 'wk_rekam';
    const CREATED_AT = 'wk_update';
	protected $fillable = [
		'id_aip', 'id_aip_induk', 'kd_aip_type', 'kd_aip_group', 'kd_aip_classification', 'name', 'label', 'reference', 'content', 'pages', 'nr_yr', 'affected', 'validity', 'remarks', 'icao_code', 'iata_code', 'aerodrome', 'city', 'province', 'url_file', 'no_urut', 'is_new_window', 'is_reserved', 'is_publish', 'is_file', 'id_rekam', 'id_update', 'is_active', 'is_dashboard'
    ];
    
	public $timestamps = false;
	public $incrementing = false;

	public function arpt()
	{
		return $this->hasMany(Airport::class, 'icao', 'icao_code');
	}
	
	
}
