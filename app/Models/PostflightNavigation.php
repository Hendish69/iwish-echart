<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PostflightNavigation extends Model
{
	protected $table = 'postflight_report_navigation';
	protected $fillable = ['postflight_report_id', 'type', 'ident', 'freq', 'reception_1', 'reception_2', 'reception_3', 'reception_4'];
}