<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
	protected $table = 'user_group';
    protected $primaryKey = 'group_id';

    const UPDATED_AT = null;
    const CREATED_AT = null;
	protected $fillable = [
        'group_id', 'group_name', 'group_desc', 'group_status', 'group_player', 'group_order'
    ];
	

    public $incrementing = true;

}
