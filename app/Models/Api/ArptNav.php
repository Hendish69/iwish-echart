<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;


class ArptNav extends Model
{
	protected $table = 'arpt_nav';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
    protected $fillable = [
      'id','arpt_ident', 'nav_id','ils_id','status','seq','status'
    ];

   
  public $incrementing = true;
  
  public function navaid()
	{
		return $this->hasMany(Navaid::class, 'nav_id', 'nav_id')->join('cod_nav_types','cod_nav_types.id','=','navaid.type');
  }
  
  public function ils()
	{
		return $this->hasMany(Ils::class, 'ils_id', 'ils_id');
	}

  public function airport()
	{
		return $this->hasMany(Airport::class, 'arpt_ident', 'arpt_ident');
	}
}
