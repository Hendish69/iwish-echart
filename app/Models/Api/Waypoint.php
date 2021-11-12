<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class Waypoint extends Model
{
    use PostgisTrait;
	protected $table = 'waypoint';
	protected $primaryKey = 'id';
	protected $casts = [
        'wpt_id' => 'string',
    ];
    protected $postgisFields = [
		'geom',
    ];
    const UPDATED_AT = 'edit_date';
    const CREATED_AT = 'update_cycle';
    protected $fillable = [
		'id','wpt_id', 'wpt_name', 'desc_name', 'ctry', 'type', 'usage_cd', 'mag_var', 'geom','status','editor'
    ];

	public $incrementing = true;
}
