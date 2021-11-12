<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use App\Models\User as MasterUser;


class TxCdmUser extends Model //implements AuthenticatableContract, AuthorizableContract
{
    // use Authenticatable, Authorizable;

	protected $table = 'tx_cdm_users';
    protected $primaryKey = 'cdm_users_id';

    const UPDATED_AT =null;
    const CREATED_AT = null;
	protected $fillable = [
        'cdm_id', 'user_id', 'cdm_admin', 'cdm_notif', 'cdm_last_chat', 'cdm_users_id'
    ];

    
    protected $casts = [
        'cdm_id' => 'integer',
    ];
    public $incrementing = true;

    public function masteruser()
    {
        return $this->hasOne(MasterUser::class, 'id', 'user_id');
    }

    public function user()
	{
		return $this->hasMany(User::class, 'id', 'user_id');
    }
    public function chat()
	{
		return $this->hasMany(TxCdmChat::class, 'user_id', 'user_id')->where('chat_date','!=',null)->orderby('chat_date','desc');
    }
   
}
