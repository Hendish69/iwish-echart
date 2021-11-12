<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;


class RunwayTemp extends Model
{


	protected $table = 'arpt_rwy_temp';
	protected $primaryKey = 'id';
	// protected $casts = [
	// 	'rwy_id' => 'string',
		
	// ];
	// protected $keyType = 'string';

	const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';

	protected $fillable = [
        'id','rwy_id', 'arpt_ident', 'rwy_ident', 'length', 'width', 'pcn', 'surface','strip_l', 'strip_w','thr_low', 'thr_high','editor','status', 'src_id', 'page'
    ];

    // 


	public $incrementing = true;

	public function physicals()
	{
		return $this->hasMany(RwyPhysicalTemp::class, 'rwy_id', 'rwy_id')->orderby('rwy_ident','asc');
	}
	// public function lighting()
	// {
	// 	return $this->hasMany(RwylgtTemp::class, 'rwy_id', 'rwy_id');
	// }
	
}
