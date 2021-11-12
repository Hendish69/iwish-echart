<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class CodChartTypes extends Model
{
	protected $table = 'cod_chart_types';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
		'id', 'definition', 'description', 'grp', 'code', 'seq'
    ];
    protected $casts = [
        'id' => 'string',
    ];
   
  public $incrementing = false;
  


}
