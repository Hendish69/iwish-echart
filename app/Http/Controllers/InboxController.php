<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, View, Validator};
use App\Models\{User}; 

class InboxController extends Controller
{
	private $inbox;
	public function __construct()
	{
		$this->middleware('auth');	
	} 
	public function index(Request $request)
	{ 
		return View::make('pages.inbox');
	}
	public function show($id)
	{
		$user=Auth::user();
		$data['showbyId'] = $user->notifications()->where('id', $id)->first();
		$user->unreadNotifications()->where('id', $id)->update(['read_at' => now()]);
		return View::make('pages.inbox',$data);
	}

}