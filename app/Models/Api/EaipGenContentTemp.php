<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class EaipGenContentTemp extends Model
{
	protected $table = 'eaip_gen_content_temp';
	protected $primaryKey = 'id';

	const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'eff_date';
    protected $fillable = [
		'id', 'sub_id', 'pra_content', 'content', 'seq', 'font', 'newdata', 'tab', 'delete', 'editor', 'tbl', 'underline', 'aligment', 'status', 'src_id', 'page'
    ];

	public $incrementing = true;
}