<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    /**
     * Show the community chat page with all messages.
     */
    public function index()
    {
        $messages = Message::with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('notlogin.community', compact('messages'));
    }

    /**
     * Save a new message.
     */
    public function send(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'user_id' => Auth::id() ?? 1, // Replace 1 with a test user ID if not logged in
            'channel' => 'general-help',
            'content' => $request->content,
        ]);

        return response()->json($message->load('user'));
    }

    /**
     * Fetch all messages for AJAX polling.
     */
    public function fetch()
    {
        $messages = Message::with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}
