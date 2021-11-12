<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class ChartProcedure extends Model
{
	protected $table = 'chart_proc';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
    protected $fillable = [
		'id','chart_id','proc_id','eff_date','editor','deleted','status'
    ];
    

    public $incrementing = true;

    public function segment()
    {
      return $this->hasOne(Arptprocedure::class,'proc_id','proc_id');
    }
    
}
