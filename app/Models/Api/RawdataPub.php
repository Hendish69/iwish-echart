<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class RawdataPub extends Model
{
	protected $table = 'rawdata_pub';
    protected $primaryKey = 'rawdata_id';

    const UPDATED_AT = 'update_date';
    const CREATED_AT = 'create_date';
	protected $fillable = [
        'rawdata_id', 'tablename', 'fieldname', 'fieldid', 'status_raw', 'nr', 'pub_date','eff_date','ori_change_pic','pub_type',
        'pia_wilayah_pic','pia_wilayah_qc','pia_wilayah_drafter','pia_pusat_pic','pia_pusat_qc','pia_pusat_drafter','originator_pic','raw_type','raw_src_id'
    ];
	

    public $incrementing = true;
  public function airport()
	{
		return $this->hasMany(Airport::class, 'arpt_ident', 'fieldid');
  }
  public function source()
    {
      return $this->hasMany(SourceNr::class, 'id', 'raw_src_id');
    }
  // public function waypoint()
  //   {
  //     return $this->hasMany(WaypointTemp::class, 'wpt_id', 'fieldid');
  //   }
  public function ats()
    {
      return $this->hasMany(AtsTemp::class, 'ats_id', 'fieldid');
    }
  public function users()
    {
		return $this->hasMany(User::class, 'id', 'ori_change_pic');
    }

  public function detail()
    {
      return $this->hasMany(RawdataPubDetail::class, 'rawdataid', 'rawdata_id');
    }
  public function attach()
    {
      return $this->hasMany(RawdataPubAtt::class, 'rawdataid', 'rawdata_id');
    // //   ->hasMany('Friend')->where(function($query) {
    // //     $query->where('content_id', $this->attributes['id']);
    // //     $query->orWhere('user_id',$this->attributes['id']);
    // // })->where('type',2)->where('situation',1)->orderBy('id','DESC');
    //   return $this->hasMany(RawdataPubAtt)->where(function($query) {
    //     $query->where('rawdataid', 'rawdata_id');
    //     $query->orWhere('file_att','P');
    // });
    }
  public function notam()
    {
      return $this->hasMany( RawdataPubNotam::class, 'rawdataid', 'rawdata_id');
    }
   

}
