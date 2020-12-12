<?php

namespace App\Http\Controllers;

use App\Events\MessengerEvent;
use App\Messenger;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function getMessages()
    {
        return $messages = Messenger::with('user')->get();
        return response()->json($messages, 200);
    }
    public function store(Request $request)
    {
        $message = new Messenger();
        $message->sender = auth()->id();
        $message->message = $request->message;
        $message->save();
        
        $user = User::find(auth()->user()->id);
        event(new MessengerEvent($message, $user));
        
        return response()->json($message, 200);
    }
}
