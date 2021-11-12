<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class Airac extends Model
{
	protected $table = 'airac';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
		'id', 'maj_raw', 'maj_pub', 'min_raw', 'min_pub', 'eff_date'
    ];

   
  public $incrementing = true;
  


}
