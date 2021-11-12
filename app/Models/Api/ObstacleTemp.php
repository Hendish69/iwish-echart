<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class ObstacleTemp extends Model
{
    use PostgisTrait;

	protected $table = 'arpt_obstacle_temp';
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
