<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
	public function forceLogin(Request $request, string $user)
	{
		$user = User::find($user);

		Auth::login($user);
	}
}