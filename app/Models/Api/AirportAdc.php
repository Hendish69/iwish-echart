<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class AirportAdc extends Model
{
    use PostgisTrait;

	protected $table = 'arpt_adc';
	protected $primaryKey = 'id';
	
	const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
	protected $fillable = [
		'arpt_ident', 'layer', 'elevation', 'geom', 'toponimi', 'param'
    ];
    protected $postgisFields = [
		'geom',
	];

	public $incrementing = true;
}
