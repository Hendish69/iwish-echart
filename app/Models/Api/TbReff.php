<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class TbReff extends Model
{
	protected $table = 'tb_reff';
    protected $primaryKey = 'reff_group_id';

    const UPDATED_AT = null;
    const CREATED_AT = null;
	protected $fillable = [
        'reff_group', 'reff_code', 'reff_name', 'reff_short', 'reff_desc', 'reff_class', 'reff_status', 'reff_order', 'reff_group_id'
    ];
	

    public $incrementing = true;

}
