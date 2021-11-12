<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class RawdataPubChange extends Model
{
	protected $table = 'rawdata_pub_change';
    protected $primaryKey = 'rawdata_change_id';

    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
	protected $fillable = [
        'rawdata_change_id','rawdataid','sub_id','ident','field_name','field_desc','curr_value','request_value','status'
    ];
	

    public $incrementing = true;


}
