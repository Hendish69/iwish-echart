<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class Obstacle extends Model
{
    use PostgisTrait;

	protected $table = 'arpt_obstacle';
	protected $primaryKey = 'id';

	const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
	protected $fillable = [
		'id','arpt_ident','obs_type', 'lighted', 'obs_group', 'rwy_id', 'bearing', 'dist', 'geom', 'elev_ft','hgt','position', 'remarks','notes','source', 'deleted', 'editor', 'src_id','status'
    ];
    protected $postgisFields = [
		'geom',
	];


	public $incrementing = true;
}
