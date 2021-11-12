<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Arptprocedure extends Model
{
	protected $table = 'arpt_proc';
	protected $primaryKey = 'id';
	// protected $casts = [
    //     'proc_id' => 'string',
	// ];
	const UPDATED_AT = 'update_cycle';
	const CREATED_AT = 'eff_date';
	protected $fillable = [
		'id','proc_id', 'arpt_ident', 'proc_name', 'remarks', 'proc_text','chart_type','note', 'delete', 'editor','status','rwy'
    ];
	public $incrementing = true;
	

	public function chart()
	{
		return $this->hasMany(CodChartTypes::class, 'id', 'chart_type');
	}
	public function airport()
	{
		return $this->hasMany(Airport::class, 'arpt_ident', 'arpt_ident');
	}
	public function segment()
	{
		return $this->hasMany(Arptprocedureseg::class, 'proc_id', 'proc_id');
	}
}
