<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FeedbackFaq extends Model
{
	protected $table = 'feedback_faq';
	public $timestamps = false;

	protected static function boot()
	{
		parent::boot();

		self::creating(function($model) {
			$model->created_at = Carbon::now();
			$model->created_by = Auth::user()->id;
		});
	}
}