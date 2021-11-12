<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class ArptproceduresegTemp extends Model
{
	protected $table = 'arpt_proc_seg_temp';
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
		return $this->hasMany(ArptransTemp::class, 'proc_id', 'trans_id')->orderby('rt_type','asc');
	}
    
    
}
