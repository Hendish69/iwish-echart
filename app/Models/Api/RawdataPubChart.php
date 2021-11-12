<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class RawdataPubChart extends Model
{
	protected $table = 'rawdata_pub_chart';
	protected $primaryKey = 'rawdata_chart_id';
	
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
    protected $fillable = [
		'rawdata_chart_id', 'rawdataid','chartname_id', 'path_file', 'filename', 'chart_filename', 'ischange', 'update_cycle'
    ];

   
  public $incrementing = true;
  public function getFilePathAttribute()
    {
        return app(FileManager::class)->getFile($this->attributes['path_file']);
    }
    public function setFilenamesAttribute($value)
    {
        $this->attributes['files'] = json_encode($value);
    }
    public function chart()
    {
      {
        return $this->hasMany(PdfFile::class, 'arptchart_id', 'chartname_id');
      }
    }

}
