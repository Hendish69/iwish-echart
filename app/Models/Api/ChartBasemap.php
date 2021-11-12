<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class ChartBasemap extends Model
{
  use PostgisTrait;

	protected $table = 'bm_chart';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
    protected $fillable = [
		'id','chart_id','level','grid','panel_hor','panel_ver','idx','scale','width_panel','length_panel','frame_size','projection','cen_mer','lat_ref','sp1','sp2','arpt_ident','status_update','area','frame','create_chart','date_gen','deleted','editor'
    ];
      protected $postgisFields = [
      'area',
      'frame',
    ];

    public $incrementing = true;

    public function airport()
	{
		return $this->hasMany(Airport::class, 'arpt_ident', 'arpt_ident');
	}
  public function chart()
	{
		return $this->hasMany(ChartProperties::class, 'bm_id', 'chart_id');
	}
}
