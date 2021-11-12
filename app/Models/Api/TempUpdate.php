<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class TempUpdate extends Model
{
	protected $table = 'temp_update';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'effdate';
    protected $fillable = [
		'id', 'table_nm', 'server', 'tableid', 'status', 'editor','sync'
    ];

  public $incrementing = true;
  public function detail()
	{
		return $this->hasMany(TempUpdateDetail::class, 'refid', 'id');
  }
}
