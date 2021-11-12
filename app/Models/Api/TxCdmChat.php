<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use App\Managers\FileManager;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class TxCdmChat extends Model
{
	protected $table = 'tx_cdm_chat';
    protected $primaryKey = 'chat_id';
    const UPDATED_AT ='chat_date';
    const CREATED_AT = 'chat_date';
	protected $fillable = [
        'cdm_id', 'chat_id', 'va_no', 'user_id', 'chat_id_reply', 'chat_type', 'chat_content', 'chat_file_path', 'chat_file_name', 'chat_file_ext', 'chat_file_size', 'chat_date', 'chat_status'
    ];
    // protected $appends = ['chat_file_path'];
    public $incrementing = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->user_id = Auth::user()->id;
            $model->chat_date = Carbon::now();
        });
    }

    public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id')->where('id','!=','0')->where('id','!=',null);
	}
   
    public function getChatFilePathAttribute()
    {
        return app(FileManager::class)->getFile('chat/'.$this->attributes['chat_file_path']);
    }
}
