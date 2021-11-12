<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class NavaidTemp extends Model
{
    use PostgisTrait;
	protected $table = 'navaid_temp';
	protected $primaryKey = 'id';
	// protected $casts = [
  //       'nav_id' => 'string',
  //   ];
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
    protected $fillable = [
		'nav_id', 'nav_name', 'nav_ident', 'type', 'col_dme', 'freq', 'range','altitude', 'mag_var', 'channel', 'dme_wgs_lat', 'dme_wgs_long','dme_range', 'dme_elev', 'status_vld','ctry','editor','edit_date','geom', 'dmegeom','opr_hrs', 'remarks'
    ];
    // protected $guarded = ['geom','dmegeom'];
    protected $postgisFields = [
        'geom',
        'dmegeom',
	];
  public $incrementing = true;
  
  public function ats()
	{
		return $this->hasMany(AtsTemp::class,'point', 'nav_id')->orderby('ats_ident');
  }
  public function ats2()
	{
		return $this->hasMany(AtsTemp::class, 'point2', 'nav_id')->orderby('ats_ident');
  }

  public function transition()
	{
		return $this->hasMany(Arptranseg::class, 'fix_id', 'nav_id');
  }
  public function arptnav()
	{
		return $this->hasMany(ArptNavTemp::class, 'nav_id', 'nav_id')->join('arpt','arpt.arpt_ident','arpt_nav_temp.arpt_ident');
  }

  

}
