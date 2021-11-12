<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class SysMenu extends Model
{
	protected $table = 'sysmenu';
	protected $primaryKey = 'id';
	
    // const UPDATED_AT = null;
    // const CREATED_AT = 'null';
    protected $fillable = [
		'id','menudesc','parentid','menulevel','imgindex','fname','rem','idx','usr','class','deleted','status'

    ];
   
	public $incrementing = true;
}
