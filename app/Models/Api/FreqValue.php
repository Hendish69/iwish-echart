<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class FreqValue extends Model
{
	protected $table = 'freq_value';
	protected $primaryKey = 'id';
	
	const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
    protected $fillable = [
		'freq_id', 'freq', 'unit','status'
    ];

   
	public $incrementing = true;
}
