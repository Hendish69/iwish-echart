<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class MsaArea extends Model
{
    use PostgisTrait;
	protected $table = 'msa_area';
	protected $primaryKey = 'id';
	const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
	protected $fillable = [
		'id','msa_area_id','msa_id','area','alt','geom'
    ];
	protected $postgisFields = [
        'geom',
	];
	public function segment()
	{
		return $this->hasMany(MsaSegment::class, 'msa_area_id', 'msa_area_id')->orderby('seq','asc');
	}
	
	public $incrementing = true;
}
