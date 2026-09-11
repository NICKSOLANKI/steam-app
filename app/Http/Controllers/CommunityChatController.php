<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

// The class name now correctly matches the filename
class CommunityChatController extends Controller
{
    /**
     * Show the community chat page.
     */
    public function index(): View
    {
        $messages = Message::with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('community.chat', compact('messages')); // Assuming you have a view at 'resources/views/community/chat.blade.php'
    }

    /**
     * Save a new chat message.
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $message = Message::create([
            'user_id' => Auth::id(),
            'channel' => 'community-chat', // Channel specific to this controller
            'content' => $request->input('content'),
        ]);

        return response()->json($message->load('user'));
    }

    /**
     * Fetch all chat messages for AJAX polling.
     */
    public function fetch(): JsonResponse
    {
        $messages = Message::with('user')
            ->where('channel', 'community-chat') // Fetch messages for this channel only
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}