<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class EaipPushbackTemp extends Model
{
	protected $table = 'eaip_pushback_temp';
    protected $primaryKey = 'id';

    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'eff_date';
	protected $fillable = [
        'arpt_ident_pushback', 'no_gate', 'ramp_name', 'procedure', 'radio', 'sequence', 'editor', 'nbr', 'id','remarks','deleted'
    ];
	

	public $incrementing = true;
}
