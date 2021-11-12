<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FeedbackHistory extends Model
{
	protected $table = 'feedback_logissue';
	public $timestamps = false;

	protected static function boot()
	{
		parent::boot();

		static::creating(function($model) {
			$model->created_at = Carbon::now();
			$model->user_id = Auth::user()->id;
		});
	}
}