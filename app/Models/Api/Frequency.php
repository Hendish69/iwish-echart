<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Frequency extends Model
{
	protected $table = 'freq';
    protected $primaryKey = 'id';

    const UPDATED_AT = 'eff_date';
    const CREATED_AT = 'update_cycle';
	protected $fillable = [
        'id', 'types', 'call_sign', 'ctry', 'remarks', 'deleted', 'editor', 'sector','status'
    ];
	

    public $incrementing = true;
    public function segment()
	{
		return $this->hasMany(FreqSeg::class, 'call_sign', 'id')->orderby('level');
    }
    public function usage()
	{
		return $this->hasMany(FreqUsage::class, 'freqid', 'id');
    }
}
