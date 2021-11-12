<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PostflightLightning extends Model
{
	protected $table = 'postflight_report_lightning';
	protected $fillable = ['postflight_report_id', 'type', 'reception'];
}