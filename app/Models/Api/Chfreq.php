<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class Chfreq extends Model
{
	protected $table = 'ch_freq';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
		'id', 'definition', 'mls_freq', 'mls_ch', 'gs_freq', 'ils_yes'
    ];

   
  public $incrementing = false;
  


}
