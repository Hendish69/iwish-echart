<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class RwyPhysicalTemp extends Model
{
    use PostgisTrait;

	protected $table = 'arpt_rwy_physical_temp';
	protected $primaryKey = 'id';
	// protected $casts = [
	// 	'rwy_key' => 'string',
		
	// ];
	// protected $keyType = 'string';
	const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
	protected $fillable = [
		'id','rwy_key', 'rwy_id', 'rwy_ident', 'geom', 'mag_brg', 'resa_l', 
        'resa_w', 'thr_elev', 'tdz_elev', 'disp_thr_length', 'swy_length', 'cwy_length', 
        'slope', 'disp_thr_elev', 'disp_geom', 'tora', 'toda', 'asda', 'lda', 'remarks', 
        'editor','true_brg', 'cwy_width', 'swy_width', 'slope1', 'status','geoid'
    ];
    protected $postgisFields = [
        'geom',
        'disp_geom',
	];

	public $incrementing = true;

	public function lighting()
	{
		return $this->hasMany(RwylgtTemp::class, 'rwy_id', 'rwy_key');
	}
}
