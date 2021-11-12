<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class RawdataPubAtt extends Model
{
	protected $table = 'rawdata_pub_att';
	protected $primaryKey = 'rawdata_att_id';
	
    const UPDATED_AT = 'update_cycle';
    const CREATED_AT = 'update_cycle';
    protected $fillable = [
		'rawdatadetailid', 'path_file', 'filename'
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

}
