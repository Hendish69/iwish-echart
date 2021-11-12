<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
	protected $table = 'roles';
    protected $primaryKey = 'id';

    const UPDATED_AT = 'update_at';
    const CREATED_AT = 'create_at';
	protected $fillable = [
        'id', 'name', 'slug', 'description', 'level', 'delete_at'
    ];
	

    public $incrementing = true;

}
