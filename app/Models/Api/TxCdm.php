<?php
namespace App\Models\Api;
use Illuminate\Database\Eloquent\Model;


class TxCdm extends Model //implements AuthenticatableContract, AuthorizableContract
{
    // use Authenticatable, Authorizable;

	protected $table = 'tx_cdm';
    protected $primaryKey = 'cdm_id';

    const UPDATED_AT =null;
    const CREATED_AT = null;
	protected $fillable = [
        'cdm_id','va_no', 'va_status', 'cdm_desc', 'cdm_date', 'cdm_status', 'cdm_admin'
    ];

    protected $appends = [
        'room_name',
    ];

    protected $hidden = ['volcano'];


    public $incrementing = true;

    public function getRoomNameAttribute()
    {
        return $this->volcano->va_name;
    }

    public function volcano()
    {
        return $this->hasOne(Volcano::class, 'va_no', 'va_no');
    }

    public function log()
	{
		return $this->hasMany(TxCdmLog::class, 'cdm_id', 'cdm_id')->leftjoin('tb_reff','tb_reff.reff_code','tx_cdm_log.cdm_type')->where('tb_reff.reff_group','0002')->orderby('cdm_issued','desc');
    }
    public function chat()
	{
		return $this->hasMany(TxCdmChat::class, 'cdm_id', 'cdm_id')->leftjoin('tb_reff','tb_reff.reff_code','tx_cdm_chat.chat_type')->where('tb_reff.reff_group','0002')->orderby('chat_date','desc');
    }
   
    public function user()
	{
		return $this->hasMany(TxCdmUser::class, 'cdm_id', 'cdm_id');
    }

   
   
}
