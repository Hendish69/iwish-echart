<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PostflightService extends Model
{
	protected $table = 'postflight_report_service';
	protected $fillable = ['postflight_report_id', 'name', 'freq', 'readibility', 'distance', 'flight_level', 'pharaseology', 'procedure'];
}