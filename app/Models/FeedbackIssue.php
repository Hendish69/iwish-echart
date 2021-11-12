<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Api\CodEaip;

class FeedbackIssue extends Model
{
	protected $table = 'feedback_issue';
	protected $casts = [
		'created_at' => 'datetime',
	];	
	protected $appends = [
		'created_at_label'
	];	
	protected $with = ['progressUser', 'role', 'progress', 'reporter', 'topic', 'priority', 'section', 'subsection', 'airport'];
	protected $guarded = ['id'];
	public $timestamps = false;

	protected static function boot()
	{
		parent::boot();

		self::creating(function($model) {
			$now = Carbon::now();

			$model->no = $now->format('Ym').rand(1, 9999);
			$model->created_at = $now;
			$model->user_id = Auth::user()->id;
			$model->progress_id = 1;
			$model->progress_user_id = 5;
		});
	}

	public function reporter()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}

	public function progress()
	{
		return $this->hasOne(FeedbackProgress::class, 'id', 'progress_id');
	}

	public function role()
	{
		return $this->hasOne(Role::class, 'id', 'role_owner_id');
	}

	public function progressUser()
	{
		return $this->hasOne(FeedbackProgressUser::class, 'id', 'progress_user_id');
	}

	public function topic()
	{
		return $this->hasOne(FeedbackTopic::class, 'id', 'topic_id');
	}

	public function priority()
	{
		return $this->hasOne(FeedbackPriority::class, 'id', 'priority_id');
	}

	public function section()
	{
		return $this->hasOne(CodEaip::class, 'id', 'section_id');
	}

	public function subsection()
	{
		return $this->hasOne(CodEaip::class, 'id', 'section_id');
	}

	public function airport()
	{
		return $this->hasOne(Airport::class, 'arpt_ident', 'airport_id');
	}

	public function getCreatedAtLabelAttribute()
	{
		return $this->created_at->format('d M Y');
	}
}