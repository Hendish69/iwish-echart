<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class ChartMinimaTemp extends Model
{
	protected $table = 'chart_minima_temp';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
    protected $fillable = [
        'id','chart_id','ocaa','ocab','ocac','ocad','gpinopa','gpinopb','gpinopc','gpinopd','circlinga','circlingb','circlingc','circlingd','gs1','gs2','gs3','gs4','gs5','gs6','ocaa1','ocab1','ocac1','ocad1','descend','faf','mapt','dist','deleted','editor','app_type','app_len','deg','rdh','noted','precision','gpa1','gpb1','gpc1','gpd1','ca1','cb1','cc1','cd1','dist_rem','noalsa','noalsb','noalsc','noalsd','noalsgpa','noalsgpb','noalsgpc','noalsgpd','rod1','rod2','rod3','rod4','rod5','rod6','nm_to','dme1','dme2','dme3','dme4','dme5','dme6','dme_alt1','dme_alt2','dme_alt3','dme_alt4','dme_alt5','dme_alt6','gs7','rod7','dme7','dme_alt7','faf_alt','faf_to_thr','status'

      ];

   
  public $incrementing = true;
  
  
  
}
