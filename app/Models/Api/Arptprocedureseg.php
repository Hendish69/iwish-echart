<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Arptprocedureseg extends Model
{
	protected $table = 'arpt_proc_seg';
	protected $primaryKey = 'id';
	// protected $casts = [
    //     'proc_id' => 'string',
	// ];
	const UPDATED_AT = 'update_cycle';
	const CREATED_AT = 'update_cycle';
	protected $fillable = [
		'id','proc_id', 'trans_id','status'
    ];
    public $incrementing = true;
    public function transition()
	{
		return $this->hasMany(Arptrans::class, 'proc_id', 'trans_id');
	}
    
    
}
