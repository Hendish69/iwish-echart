<?php

namespace App\Models\Api;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
//=======

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';
    protected $primaryKey = 'id';

    const UPDATED_AT = 'update_at';
    const CREATED_AT = 'create_at';
	protected $fillable = [
        'id', 'role_id', 'user_id', 'delete_at'
    ];
	

    public $incrementing = true;
    public function roles()
	{
		return $this->hasMany(Role::class, 'id', 'role_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'id','user_id');
    }


}
