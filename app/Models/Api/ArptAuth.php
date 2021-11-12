<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class ArptAuth extends Model
{
    protected $table = 'arpt_auth';
    protected $primaryKey = 'id';
	
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
    protected $fillable = [
		'id', 'name', 'class', 'otban', 'pia', 'address', 'remarks', 'pia_address', 'pic_otban', 'pic_pia', 'deleted'
    ];

    public $incrementing = true;
    public function airport()
	{
		return $this->hasMany(Airport::class, 'auth', 'id')->orderby('arpt_name','asc');
	}
  public function users()
	{
		return $this->hasMany(User::class, 'pia_id', 'id')->orderby('name');
	}

}