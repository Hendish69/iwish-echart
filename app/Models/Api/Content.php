<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;
    protected $fillable = [
        "title",
        "body",
        "user_id",
        "status",
        "section_id"
    ];
    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
  
}
