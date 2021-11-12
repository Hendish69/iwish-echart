<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class MsaSegment extends Model
{
	protected $table = 'msa_seg';
	protected $primaryKey = 'id';
	const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
	protected $fillable = [
		'id','msa_area_id','seq','center_id','bearing','radius','shap','taa_center','bearing1'
    ];
	public function navaid()
	{
		return $this->hasMany(NavaidTemp::class, 'nav_id', 'center_id')->join('cod_nav_types','cod_nav_types.id','navaid_temp.type');
	}

    public function airport()
	{
		return $this->hasMany(Airport::class, 'arpt_ident', 'center_id');
	}

	
	public $incrementing = true;
}
