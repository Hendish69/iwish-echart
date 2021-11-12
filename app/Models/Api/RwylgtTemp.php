<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class RwylgtTemp extends Model
{

	protected $table = 'eaip_rwy_lgt_temp';
	protected $primaryKey = 'id';
	// protected $casts = [
	// 	'rwy_id' => 'string',
		
	// ];
	const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'eff_date';
	protected $fillable = [
		'id','rwy_id', 'apch_lgt_type_len', 'thr_lgt_clr_wbar', 'vasis_meht_papi','tdz_lgt_len', 'rwy_ctrln_lgt_length_spc_clr', 'rwy_edge_lgt_len_spc_clr','rwy_end_lgt_clr_wbar', 'swy_lgt_len_clr', 'remark', 'editor','status', 'src_id', 'page'
    ];

	public $incrementing = true;
}
