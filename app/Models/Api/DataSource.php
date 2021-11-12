<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class DataSource extends Model
{
	protected $table = 'datasource';
    protected $primaryKey = 'source_id';

    const UPDATED_AT = 'update_date';
    const CREATED_AT = 'create_date';
	protected $fillable = [
        'part', 'arpt_ident', 'page_nr', 'source', 'airac', 'nr', 'type', 'pub_date', 'eff_date', 'till_date', 'active', 'editor', 'deleted', 'file_name', 'section', 'sub_section', 'sourceid', 'isbundle', 'ispublish', 'isdashboard', 'source_id'
    ];
	

	public $incrementing = true;
}
