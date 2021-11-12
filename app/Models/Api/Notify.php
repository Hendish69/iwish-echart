<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
	protected $table = 'notify';
    protected $primaryKey = 'notif_id';

    const UPDATED_AT = 'update_at';
    const CREATED_AT = 'create_at';
	protected $fillable = [
        'notif_id', 'to_userid', 'from_userid','tag','email_subject', 'cc_email', 'email_content',
    ];
	

    public $incrementing = true;
    
    public function tousers()
	{
		return $this->hasMany(User::class, 'id', 'to_userid');
    }

    public function fromuser()
	{
		return $this->hasMany(User::class, 'id', 'from_userid');
    }

    

}
