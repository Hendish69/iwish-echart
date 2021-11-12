<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class SuasRemarks extends Model
{
	protected $table = 'suas_rmk';
	protected $primaryKey = 'id';
	protected $casts = [
        'suas_id' => 'string',
	];
	const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
	protected $fillable = [
        'suas_id', 'note_type', 'note_nbr', 'remarks', 'editor', 'id','status'
    ];
	
    public $incrementing = true;
    
}
