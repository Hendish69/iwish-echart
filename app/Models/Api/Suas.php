<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class Suas extends Model
{
    use PostgisTrait;
	protected $table = 'suas';
	protected $primaryKey = 'id';
	protected $casts = [
        'suas_id' => 'string',
    ];
    protected $postgisFields = [
		'geom',
	];
	const UPDATED_AT = 'eff_date';
    const CREATED_AT = 'update_cycle';
	public $incrementing = true;
	protected $fillable = [
		'suas_id', 'suas_ident', 'suas_sector', 'suas_name', 'suas_type', 'ctry', 'icao_acc', 'icao_reg', 'upper', 'lower', 'call_sign', 'eff_times', 'editor', 'deleted','id', 'geom','status'
    ];


	public function boundary()
	{
		return $this->hasMany(SuasSegment::class, 'suas_id', 'suas_id')->orderby('suas_seq');
	}
	public function remarks()
	{
		return $this->hasMany(SuasRemarks::class, 'suas_id', 'suas_id')->orderby('note_nbr');
	}
	
	
}
