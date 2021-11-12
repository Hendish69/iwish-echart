<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class TempUpdateDetail extends Model
{
	protected $table = 'temp_update_detail';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'effdate';
    protected $fillable = [
		'id', 'refid', 'field', 'val', 'seq', 'editor','status','src_id','page'
    ];

	public $incrementing = true;
}
