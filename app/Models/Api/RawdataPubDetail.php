<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class RawdataPubDetail extends Model
{
	protected $table = 'rawdata_pub_detail';
    protected $primaryKey = 'rawdata_detail_id';

    const UPDATED_AT = null;
    const CREATED_AT = null;
	protected $fillable = [
        'rawdataid', 'req_action', 'status_action', 'status_remarks', 'status_date','status_pic'
        // 'rawdata_id', 'tablename', 'fieldname', 'fieldid', 'status_raw', 'status_draft', 'status_pub', 'raw_notam', 'raw_remarks'
    ];
	

    public $incrementing = true;
    public function users()
	{
		return $this->hasMany(User::class, 'id', 'status_pic');
    }
    

    

}
