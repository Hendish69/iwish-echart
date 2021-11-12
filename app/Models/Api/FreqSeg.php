<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class FreqSeg extends Model
{
	protected $table = 'freq_seg';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = 'eff_date';
    const CREATED_AT = 'update_cycle';
    protected $fillable = [
		'id', 'freq_id', 'call_sign', 'level', 'opr_hrs', 'remarks', 'editor', 'deleted', 'satcom', 'logon','status'
    ];

   
  public $incrementing = true;

  public function value()
	{
		return $this->hasMany(FreqValue::class, 'freq_id', 'freq_id')->orderby('freq');
  }
  


}
