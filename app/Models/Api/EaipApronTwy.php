<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class EaipApronTwy extends Model
{
	protected $table = 'eaip_apron_twy';
    protected $primaryKey = 'id';

    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'eff_date';
	protected $fillable = [
        'id','arpt_ident', 'name', 'dimension', 'surface','strength','type','group','status','sequence', 'editor', 'page','src_id','deleted'
    ];
	

	public $incrementing = true;
    // public function temp()
	// {
	// 	return $this->hasMany(TempUpdate::class, 'table_nm', $table);
	// }
}
