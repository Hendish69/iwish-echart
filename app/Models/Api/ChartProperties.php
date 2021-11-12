<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class ChartProperties extends Model
{
	protected $table = 'propchart';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
    protected $fillable = [
		'id','bm_id','chart_id','chart_name','chart_type','rnav','customer','source','srcnum','footer','msa_id','sn','seq','rwy','nav','cat','status','editor','eff_date','deleted','publish_date','page','remarks','chart_arpt_ident'
    ];
    

    public $incrementing = true;
    public function basemap()
    {
      return $this->hasMany(ChartBasemap::class, 'chart_id', 'bm_id');
    }

    public function procedure()
    {
      return $this->hasMany(ChartProcedureTemp::class,'chart_id','chart_id');
    }
    public function aip()
    {
      return $this->hasMany(PdfFile::class,'chart_id','chart_id');
    }
    public function msa()
    {
      return $this->hasMany(Msa::class,'msa_id','msa_id');
    }

    public function source()
    {
      return $this->hasMany(SourceNr::class,'id','sn');
    }
    
}
