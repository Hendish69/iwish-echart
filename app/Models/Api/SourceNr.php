<?php
namespace App\Models\Api;
use Illuminate\Database\Eloquent\Model;


class SourceNr extends Model //implements AuthenticatableContract, AuthorizableContract
{
    // use Authenticatable, Authorizable;

	protected $table = 'source_nr';
    protected $primaryKey = 'id';

    const UPDATED_AT = 'update_at';
    const CREATED_AT = 'create_at';
	protected $fillable = ['id', 'src_id', 'src_type', 'pub_date', 'eff_date','publish','raw_type'];

    public $incrementing = true;
    
    public function note()
	{
		return $this->hasMany(SourceNrSeg::class, 'src_id', 'src_id');
	}
	
}
