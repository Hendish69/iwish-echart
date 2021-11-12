<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Temp1 extends Model
{
	protected $table = 'temp1';
	protected $primaryKey = 'A';
	protected $casts = [
        'A' => 'string',
    ];
    const UPDATED_AT = 'UPDATE_C';
    const CREATED_AT = 'UPDATE_C';
    protected $fillable = [
		'A', 'B', 'NO'
    ];
   
	public $incrementing = false;
}
