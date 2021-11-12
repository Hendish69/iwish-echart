<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model; 

class CecAftnlog extends Model
{
	protected $table = 'cec_aftn_log';
	protected $primaryKey = 'id';
	protected $casts = [
	      'data' => 'array',
	];
    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'created_at';
    protected $fillable = [
		'id', 'data'
    ];  
  public $incrementing = true; 
}
