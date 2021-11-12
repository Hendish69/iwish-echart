<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class FrequencyTemp extends Model
{
	protected $table = 'freq_temp';
    protected $primaryKey = 'id';

    const UPDATED_AT = 'eff_date';
    const CREATED_AT = 'update_cycle';
	protected $fillable = [
        'id', 'types', 'call_sign', 'ctry', 'remarks', 'deleted', 'editor', 'sector','status'
    ];
	

    public $incrementing = true;
    public function segment()
	{
		return $this->hasMany(FreqSegTemp::class, 'call_sign', 'id')->orderby('level')->orderByRaw('length(freq_id)')->orderByRaw('freq_id');
    }
    public function usage()
	{
		return $this->hasMany(FreqUsageTemp::class, 'freqid', 'id');
    }
}
