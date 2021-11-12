<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Airport;
use App\Models\Api\Ats;
use DB;
class VfrPlanning extends Model
{
    // use PostgisTrait;
    use HasFactory;
    protected $table = 'vfr_planning';
    protected $fillable = ['id', 'aircraft', 'departure', 'destination','etd','eta', 'speed'];
    protected $with = ['departure', 'destination'];
    // protected $postgisFields = [
    //     'geom',
    // ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->created_by = Auth::user()->id;
        });
      
        public function departure()
        {
            return $this->hasOne(Airport::class, 'arpt_ident', 'departure');
        }

        public function destination()
        {
            return $this->hasOne(Airport::class, 'arpt_ident', 'destination');
        }
    }
}
