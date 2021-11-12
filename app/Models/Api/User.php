<?php
namespace App\Models\Api;

// use Illuminate\Auth\Authenticatable;
// use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
// use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
// use Laravel\Lumen\Auth\Authorizable;

use Illuminate\Database\Eloquent\Model;


class User extends Model //implements AuthenticatableContract, AuthorizableContract
{
    // use Authenticatable, Authorizable;

	protected $table = 'users';
    protected $primaryKey = 'id';

    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'created_at';
    protected $fillable = [
        'id','name','first_name','last_name','email','email_verified_at','password','remember_token','activated','token','signup_ip_address','signup_confirmation_ip_address','signup_sm_ip_address','admin_ip_address','updated_ip_address','deleted_ip_address','created_at','updated_at','deleted_at','user_number','user_sex','user_position','user_unit','user_sub_unit','user_phone','user_country','org_id','icao_code','user_fir','device_id','app_login','user_status','pia_id','api_token','user_photo','group_id','session_id','status_login'
        // 'user_id', 'user_login', 'user_pass', 'user_fullname', 'user_number', 'user_sex', 'user_level', 'user_position', 'user_unit', 'user_sub_unit', 'user_address', 'user_phone', 'user_email', 'user_photo', 'user_country', 'org_id', 'group_id', 'device_id', 'app_login', 'user_status', 'user_sts_login', 'user_create', 'date_create', 'ip_create', 'user_update', 'last_update', 'ip_update','pia_id'
        // 'user_id', 'user_login', 'user_pass', 'user_fullname', 'user_number', 'user_sex', 'user_level', 'user_position', 'user_unit', 'user_sub_unit', 'user_address', 'user_phone', 'user_email', 'user_photo', 'user_country', 'org_id','group_id', 'icao_code', 'user_fir', 'device_id', 'app_login', 'last_login', 'user_status', 'user_sts_login', 'user_create', 'date_create', 'ip_create', 'user_update', 'last_update', 'ip_update','pia_id'
    ];
    // protected $rules = [
    //     // 'user_login' => 'string|required|email|unique:users',
    //     'user_login' => 'string|required',
    //     'user_pass' => 'string|required',
    // ];
    protected $hidden = [
        'api_token',
    ];

    public $incrementing = true;

    public function organization()
    {
        return $this->hasOne(Org::class, 'org_id', 'org_id');
    }

    public function org()
    {
        return $this->hasMany(Org::class, 'org_id', 'org_id');
    }
        
    public function usergroup()
    {
        return $this->hasMany(UserGroup::class, 'group_id', 'group_id');
    }
        
    public function pia()
    {
        return $this->hasMany(ArptAuth::class, 'id', 'pia_id');
    }
        
    public function roleuser()
    {
            return $this->hasOne(RoleUser::class, 'user_id', 'id');
    }
}
