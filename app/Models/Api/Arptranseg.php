<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class Arptranseg extends Model
{
    use PostgisTrait;
	protected $table = 'arpt_trans_seg';
	protected $primaryKey = 'id';

    protected $postgisFields = [
		'geom',
    ];
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'edit_date';
	protected $fillable = [
		'id','proc_id', 'seq_num','fix_id','wd1','wd2','wd3','wd4','turn_dir','rnp','path_term','recd_nav','theta','rho','mag_crs','true_crs','rt_dist_from','alt_desc','alt1','alt2','sp_lim','vert_angle','center_fix','arc_rad','geom','moca','leg_time','dist_cal','dist_to_thr','recd_nav1','theta1','rho1','tch','deleted','status'
    ];
    public $incrementing = true;

    public function arpt()
    {
      return $this->hasMany(Airport::class, 'arpt_ident', 'fix_id');
    }

    public function marker()
    {
      return $this->hasMany(IlsMarker::class, 'mrkr_id', 'fix_id');
    }

    public function rwy()
    {
      return $this->hasMany(RwyPhysical::class, 'rwy_key', 'fix_id');
    }

    public function navaid()
	{
		return $this->hasMany(Navaid::class, 'nav_id', 'fix_id')->join('cod_nav_types','cod_nav_types.id','=','navaid.type');
	}

	public function waypoint()
	{
		return $this->hasMany(Waypoint::class, 'wpt_id', 'fix_id');
	}

	public function recdnav1()
    {
      return $this->hasMany(Navaid::class, 'nav_id', 'recd_nav')->join('cod_nav_types','cod_nav_types.id','=','navaid.type');
    }
    public function recdnav2()
    {
      return $this->hasMany(Navaid::class, 'nav_id', 'recd_nav1')->join('cod_nav_types','cod_nav_types.id','=','navaid.type');
    }
    public function recdils1()
    {
      return $this->hasMany(Ils::class, 'ils_id', 'recd_nav');
    }
    public function recdils2()
    {
      return $this->hasMany(Ils::class, 'ils_id', 'recd_nav1');
    }
    public function holding()
    {
      return $this->hasMany(Holding::class, 'id', 'center_fix');
    }
    public function centnav()
    {
      return $this->hasMany(Navaid::class, 'nav_id', 'center_fix')->join('cod_nav_types','cod_nav_types.id','=','navaid_temp.type');
    }

    public function centwpt()
    {
      return $this->hasMany(Waypoint::class, 'wpt_id', 'center_fix');
    }
}
