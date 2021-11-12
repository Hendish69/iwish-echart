<?php
namespace App\Models\Api;
use Illuminate\Database\Eloquent\Model;

class TxCdmLog extends Model //implements AuthenticatableContract, AuthorizableContract
{
	protected $table = 'tx_cdm_log';
    protected $primaryKey = 'cdm_log_id';

    const UPDATED_AT ='cdm_issued';
    const CREATED_AT = 'cdm_issued';

	protected $fillable = [
        'cdm_log_id', 'cdm_id','data_id', 'cdm_type', 'cdm_stakeholder', 'cdm_stakeholder_id', 'cdm_issued', 'cdm_response', 'cdm_noticenumber', 'cdm_volcano', 'cdm_code', 'user_id', 'chat_id', 'cdm_notif', 'cdm_email'
    ];


    public $incrementing = true;
    public function chat()
	{
		return $this->hasMany(TxCdmChat::class, 'chat_id', 'chat_id');
    }
    public function user()
	{
		return $this->hasMany(User::class, 'id', 'user_id');
	}
   
}
