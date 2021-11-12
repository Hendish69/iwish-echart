<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class RawdataPubNotam extends Model
{
	protected $table = 'rawdata_pub_notam';
    protected $primaryKey = 'rawdata_notam_id';

    const UPDATED_AT = 'status_date';
    const CREATED_AT = 'status_date';
	protected $fillable = [
        'rawdata_notam_id','rawdataid','notam_nr','notam_content','status_pic',
    ];
	

    public $incrementing = true;
    public function users()
	{
		return $this->hasMany(User::class, 'id', 'status_pic');
    }
    

    

}
