<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class ChartFreq extends Model
{
	protected $table = 'freq_chart';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = 'update_sycle';
    const CREATED_AT = 'update_sycle';
    protected $fillable = [
      'id', 'arpt_ident', 'seq', 'freqid', 'deleted', 'editor', 'chart_types', 'status'
      ];

   
  public $incrementing = true;
  
  public function usage()
	{
		return $this->hasMany(FreqUsage::class, 'id', 'freqid');
  }
  
  
}
