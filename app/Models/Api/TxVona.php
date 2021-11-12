<?php
namespace App\Models\Api;
use Illuminate\Database\Eloquent\Model;


class TxVona extends Model //implements AuthenticatableContract, AuthorizableContract
{
    // use Authenticatable, Authorizable;

	protected $table = 'tx_vona';
    protected $primaryKey = 'vona_id';

    const UPDATED_AT = 'created_date';
    const CREATED_AT = 'created_date';
	protected $fillable = [
        'vona_id', 'uuid', 'noticenumber', 'issued', 'code_id', 'smithsonian_id', 'volcano', 'cu_code', 'prev_code', 'location', 'vas', 'vch_summit', 'vch_asl', 'vch_other', 'remarks', 'issued_utc'
    ];


    public $incrementing = true;
    public function volcano()
	{
		return $this->hasMany(Volcano::class, 'va_no', 'smithsonian_id');
    }
   
}
