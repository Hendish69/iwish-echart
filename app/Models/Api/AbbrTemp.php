<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class AbbrTemp extends Model
{
	protected $table = 'abbr_temp';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = 'update_at';
    const CREATED_AT = 'create_at';
    protected $fillable = [
		'id', 'ident', 'definition', 'pref', 'delete', 'status'
    ];

   
  public $incrementing = true;
  


}
