<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class PdfFile extends Model
{
	protected $table = 'pdf_file';
	protected $primaryKey = 'arptchart_id';
	
    const UPDATED_AT = 'update_at';
    const CREATED_AT = 'create_at';
    protected $fillable = [
		'aip_sub','aip_sub_id','arpt_ident','arpt_pdf_type','chart_name','seq','source','nr_yr','eff_date','pub_date','path_file','chart_id','chart_code','chart_page','chart_rwy','chart_nav','deleted','editor','scale','remarks'
    ];


  public $incrementing = true;
  
  
	


}
