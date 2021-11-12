<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    public function user()
    {
    	return $this->belongsTo("App\Models\User");
    	// return $this->hasMany(User::class, 'id', 'user_id')->where('id','!=','0')->where('id','!=',null);
    }
    public function volcano(){
    	return $this->belongsTo("App\Models\Api\TxVona");
    }
}

