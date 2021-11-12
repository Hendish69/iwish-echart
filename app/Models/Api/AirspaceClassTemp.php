<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class AirspaceClassTemp extends Model
{
	protected $table = 'airspace_class_temp';
	protected $primaryKey = 'id';
	protected $casts = [
        'asp_id' => 'string',
	];
	const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
	protected $fillable = [
        'asp_id', 'asp_class', 'asp_sector', 'upper', 'lower','remarks', 'editor', 'deleted','id','status'
    ];
	
    public $incrementing = true;
    
}
