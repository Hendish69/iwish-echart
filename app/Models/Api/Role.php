<?php

namespace App\Models\Api;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
//    use HasFactory;
    protected $table = 'roles';
    protected $primaryKey = 'id';
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'name', 'slug','description','level'
    ];

    public $incrementing = true;
    
    public function roleuser()
    {
        return $this->belongsTo(RoleUser::class,'role_id','id');
    }
}

