<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class CodStatusRequest extends Model
{
	protected $table = 'cod_status_request';
	protected $primaryKey = 'req_id';

	const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
		'id', 'req_definition', 'req_action'
    ];

	public $incrementing = true;
}