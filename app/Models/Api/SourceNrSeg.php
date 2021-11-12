<?php
namespace App\Models\Api;
use Illuminate\Database\Eloquent\Model;


class SourceNrSeg extends Model //implements AuthenticatableContract, AuthorizableContract
{
    // use Authenticatable, Authorizable;

	protected $table = 'source_nr_seg';
    protected $primaryKey = 'id';

    const UPDATED_AT =null;
    const CREATED_AT = null;
	protected $fillable = ['id', 'src_id','vol','subject','affected_to','period_start', 'period_end','period_start_time', 'period_end_time','cancel_record','date_inserted', 'inserted_by'];

    public $incrementing = true;

	
}
