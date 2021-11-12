<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackAttachment extends Model
{
	protected $table = 'feedback_attachment';
	public $timestamps = false;
	protected $appends = ['attachment_url'];

	public function getAttachmentUrlAttribute()
	{
		return env('APP_URL').'/uploads/'.$this->attachment;
	}
}