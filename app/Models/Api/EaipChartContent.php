<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class EaipChartContent extends Model
{
	protected $table = 'eaip_chart_content';
	protected $primaryKey = 'id';
	
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'eff_date';
    protected $fillable = [
		'id', 'category_id', 'arpt_ident', 'content', 'sequence', 'editor', 'status', 'src_id', 'page'
    ];

	public $incrementing = true;
}
