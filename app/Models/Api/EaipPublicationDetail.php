<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class EaipPublicationDetail extends Model
{
	protected $table = 'eaip_publication_detail';
    protected $primaryKey = 'id';

    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
	protected $fillable = [
        'id', 'eaip_pub_id', 'arptident_pub', 'gen_enr_pub', 'status_detail', 'status_by_detail', 'notam_detail', 'rem_detail'
    ];
	

	public $incrementing = true;
}
