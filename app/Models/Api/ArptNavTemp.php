<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class ArptNavTemp extends Model
{
	protected $table = 'arpt_nav_temp';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
    protected $fillable = [
		'id','arpt_ident', 'nav_id','ils_id','status','seq','status'
    ];

    public $incrementing = true;
    
    public function navaid()
        {
            return $this->hasMany(NavaidTemp::class, 'nav_id', 'nav_id')->join('cod_nav_types','cod_nav_types.id','=','navaid_temp.type');
    }
    
    public function ils()
	{
		return $this->hasMany(IlsTemp::class, 'ils_id', 'ils_id')->orderby('ils_ident');
	}

  public function airport()
	{
		return $this->hasMany(Airport::class, 'arpt_ident', 'arpt_ident');
	}


}
