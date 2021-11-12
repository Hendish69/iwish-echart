<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class AtsRemarks extends Model
{

	protected $table = 'ats_rem';
	protected $primaryKey = 'id';
	protected $casts = [
        'ats_id' => 'string',
	];
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
		'id','ats_id', 'remarks', 'asp_id', 'tbl', 'airspace_id','status','src_id','page'
    ];

	public $incrementing = false;
}
