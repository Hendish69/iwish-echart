<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Airport;

class PostflightReport extends Model
{
	protected $table = 'postflight_report';
	protected $fillable = ['postflight_report_id', 'flight_number', 'date', 'departure', 'destination', 'route', 'aircraft', 'atd', 'ata', 'pic', 'id_no', 'email', 'birds', 'birds_location', 'birds_detail', 'birds_at', 'suggestion', 'meteorological_information'];
	protected $with = ['services', 'navigations', 'lightnings', 'departure', 'destination'];

	protected static function boot()
	{
		parent::boot();

		static::creating(function($model) {
			$model->created_by = Auth::user()->id;
			$model->status = 'NEW';
		});
	}

	public function services()
	{
		return $this->hasMany(PostflightService::class, 'postflight_report_id', 'id');
	}

	public function navigations()
	{
		return $this->hasMany(PostflightNavigation::class, 'postflight_report_id', 'id');
	}

	public function lightnings()
	{
		return $this->hasMany(PostflightLightning::class, 'postflight_report_id', 'id');
	}

	public function departure()
	{
		return $this->hasOne(Airport::class, 'arpt_ident', 'departure');
	}

	public function destination()
	{
		return $this->hasOne(Airport::class, 'arpt_ident', 'destination');
	}
}