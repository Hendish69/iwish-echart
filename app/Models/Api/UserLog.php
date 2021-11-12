<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $table = 'userlog';
    protected $hidden = [
        'pic'
    ];
    protected $fillable = [
		'initial', 'pass', 'nama', 'isadmin', 'validto', 'pic', 'id', 'fsize', 'npic', 'srid, status', 'hostname'
    ];
    

}