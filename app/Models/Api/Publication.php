<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
	protected $table = 'publication';
    protected $primaryKey = 'id';

    const UPDATED_AT = null;
    const CREATED_AT = null;
	protected $fillable = [
        'id','types','name','nr','th','airac','eff_date','remarks','rawdata','pub_date'
    ];
	

    public $incrementing = true;
    public function rawpub()
	{
		return $this->hasMany(RawdataPub::class, 'rawdata_id', 'rawdata');
    }
    
    


}
