<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class Country extends Model
{
	protected $table = 'country';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
		'id', 'ident', 'country', 'area', 'country_name'
    ];

   
  public $incrementing = true;
  


}
