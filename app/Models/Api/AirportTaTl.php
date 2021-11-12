<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class AirportTaTl extends Model
{

	protected $table = 'arpt_tl_ta';
	protected $primaryKey = 'arpt_ident';
	protected $casts = [
		'arpt_ident' => 'string',
		
	];
	const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'edit_date';
	protected $fillable = [
		'arpt_ident','tl', 'ta','id','circ_a','circ_b','circ_c','circ_d','circ_a_val','circ_b_val','circ_c_val','circ_d_val'
    ];

	public $timestamps = false;
	public $incrementing = true;
}
