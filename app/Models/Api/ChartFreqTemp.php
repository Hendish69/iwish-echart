<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class ChartFreqTemp extends Model
{
	protected $table = 'freq_chart_temp';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
    protected $fillable = [
		'id', 'arpt_ident', 'seq', 'freqid', 'deleted', 'editor', 'chart_types', 'status'
    ];

   
  public $incrementing = true;
  
  public function usage()
	{
		return $this->hasMany(FreqUsageTemp::class, 'id', 'freqid');
  }
  
  
  
}
