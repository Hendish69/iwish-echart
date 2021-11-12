<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Org extends Model
{
	protected $table = 'org';
    protected $primaryKey = 'org_id';

    const UPDATED_AT = null;
    const CREATED_AT = null;
	protected $fillable = [
        'org_id', 'org_name', 'org_name_en', 'org_short', 'org_short_en', 'org_address', 'org_country', 'org_phone', 'org_email', 'org_fax', 'org_status', 'org_player'
    ];
	

    public $incrementing = true;

}
