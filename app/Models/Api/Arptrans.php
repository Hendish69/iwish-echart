<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class Arptrans extends Model
{
    use PostgisTrait;
	protected $table = 'arpt_trans';
	protected $primaryKey = 'id';
	// protected $casts = [
  //       'proc_id' => 'string',
  //   ];
    protected $postgisFields = [
		'geom',
    ];
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'eff_date';
	protected $fillable = [
		'id','proc_id', 'arpt_ident','chart_type','rnav','sub_chart_type','trans_ident','rwy_id','rwy_trans','rt_type','vnav','nav_spec','geom','deleted','editor','status'
    ];
    public $incrementing = true;
    public function segment()
	{
		return $this->hasMany(Arptranseg::class, 'proc_id', 'proc_id')->orderby('seq_num','asc');
	}

  public function airport()
	{
		return $this->hasMany(Airport::class, 'arpt_ident', 'arpt_ident');
	}
    
    
}
