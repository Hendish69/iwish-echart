<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class CodEaip extends Model
{
	protected $table = 'cod_eaip';
	protected $primaryKey = 'id';

	const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
		'id', 'seq', 'sub_id', 'definition', 'parentid', 'level', 'menu_desc', 'deleted', 'gid', 'page'
    ];

	public $incrementing = true;
}