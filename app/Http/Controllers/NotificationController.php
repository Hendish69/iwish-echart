<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Notification;
use App\Notifications\SendChangeEmail;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
  
    public function index()
    {
        // return view('product');
    }
    
    public function sendChangeEmailNotif() {
        $userSchema = User::first();
  
        $notifData = [
            'name' => 'Important Notification',
            'body' => 'You must change your email profile to begin as An Originator Airport.',
            'thanks' => 'Thank you',
            'notifText' => 'Update Your Email Profile',
            'notifUrl' => url('/'),
            'notif_id' => mt_rand(1000000000, 9999999999);
        ];
  
        Notification::send($userSchema, new SendChangeEmail($notifData));
   
        dd('Task completed!');
    }
}
