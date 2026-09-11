<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommunityServer;
use App\Models\CommunityChannel;
use App\Models\CommunityMember;
use App\Models\VoiceSession;
use App\Models\VoiceChatMessage;
use App\Events\VoiceSignalingEvent;
use Illuminate\Support\Str;

class DiscordController extends Controller
{
    /**
     * Show the Discord-like community interface
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access Discord community');
        }

        $user = Auth::user();
        $servers = $user ? $user->servers : [];
        $publicServers = CommunityServer::where('is_public', true)
            ->whereDoesntHave('members', function ($query) use ($user) {
                if ($user) {
                    $query->where('user_id', $user->id);
                }
            })
            ->limit(10)
            ->get();

        return view('discord.index', compact('servers', 'publicServers'));
    }

    /**
     * Create a new community server
     */
    public function createServer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'is_public' => 'boolean',
        ]);

        $server = CommunityServer::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => Auth::id(),
            'is_public' => $request->is_public ?? true,
        ]);

        // Add owner as a member
        CommunityMember::create([
            'server_id' => $server->id,
            'user_id' => Auth::id(),
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        // Create default text and voice channels
        CommunityChannel::create([
            'server_id' => $server->id,
            'name' => 'general',
            'type' => 'text',
            'position' => 1,
        ]);

        CommunityChannel::create([
            'server_id' => $server->id,
            'name' => 'General Voice',
            'type' => 'voice',
            'position' => 1,
        ]);

        return response()->json(['server' => $server->load('channels')]);
    }

    /**
     * Join a community server
     */
    public function joinServer($serverId)
    {
        $server = CommunityServer::findOrFail($serverId);

        // Check if user is already a member
        $existingMember = CommunityMember::where('server_id', $serverId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingMember) {
            return response()->json(['message' => 'Already a member'], 400);
        }

        // Check server capacity
        $memberCount = CommunityMember::where('server_id', $serverId)->count();
        if ($memberCount >= $server->max_members) {
            return response()->json(['message' => 'Server is full'], 400);
        }

        CommunityMember::create([
            'server_id' => $serverId,
            'user_id' => Auth::id(),
            'role' => 'member',
            'joined_at' => now(),
        ]);

        return response()->json(['message' => 'Successfully joined server']);
    }

    /**
     * Leave a community server
     */
    public function leaveServer($serverId)
    {
        $member = CommunityMember::where('server_id', $serverId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Remove from any active voice sessions
        VoiceSession::where('user_id', Auth::id())
            ->whereHas('channel', function ($query) use ($serverId) {
                $query->where('server_id', $serverId);
            })
            ->update(['left_at' => now()]);

        $member->delete();

        return response()->json(['message' => 'Successfully left server']);
    }

    /**
     * Get server details with channels and members
     */
    public function getServer($serverId)
    {
        $server = CommunityServer::with(['channels', 'members.user', 'owner'])
            ->findOrFail($serverId);

        // Get active voice sessions
        $activeVoiceSessions = VoiceSession::with('user')
            ->whereNull('left_at')
            ->whereHas('channel', function ($query) use ($serverId) {
                $query->where('server_id', $serverId);
            })
            ->get()
            ->groupBy('channel_id');

        return response()->json([
            'server' => $server,
            'activeVoiceSessions' => $activeVoiceSessions,
        ]);
    }

    /**
     * Create a new channel in a server
     */
    public function createChannel(Request $request, $serverId)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:text,voice',
            'description' => 'nullable|string|max:500',
        ]);

        $server = CommunityServer::findOrFail($serverId);

        // Check if user is owner or admin
        $member = CommunityMember::where('server_id', $serverId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!in_array($member->role, ['owner', 'admin'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $maxPosition = CommunityChannel::where('server_id', $serverId)->max('position') ?? 0;

        $channel = CommunityChannel::create([
            'server_id' => $serverId,
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'position' => $maxPosition + 1,
        ]);

        return response()->json(['channel' => $channel]);
    }

    /**
     * Join a voice channel
     */
    public function joinVoiceChannel($channelId)
    {
        $channel = CommunityChannel::findOrFail($channelId);

        if ($channel->type !== 'voice') {
            return response()->json(['message' => 'Not a voice channel'], 400);
        }

        // Check if user is a member of the server
        $member = CommunityMember::where('server_id', $channel->server_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // End any existing voice session
        VoiceSession::where('user_id', Auth::id())
            ->whereNull('left_at')
            ->update(['left_at' => now()]);

        // Create new voice session
        $session = VoiceSession::create([
            'channel_id' => $channelId,
            'user_id' => Auth::id(),
            'session_id' => Str::uuid(),
            'joined_at' => now(),
        ]);

        return response()->json([
            'session' => $session->load('user'),
            'channel' => $channel,
        ]);
    }

    /**
     * Leave a voice channel
     */
    public function leaveVoiceChannel($channelId)
    {
        $session = VoiceSession::where('channel_id', $channelId)
            ->where('user_id', Auth::id())
            ->whereNull('left_at')
            ->firstOrFail();

        $session->update(['left_at' => now()]);

        return response()->json(['message' => 'Left voice channel']);
    }

    /**
     * Send a text message to a channel
     */
    public function sendMessage(Request $request, $channelId)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $channel = CommunityChannel::findOrFail($channelId);

        if ($channel->type !== 'text') {
            return response()->json(['message' => 'Not a text channel'], 400);
        }

        // Check if user is a member of the server
        CommunityMember::where('server_id', $channel->server_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $message = VoiceChatMessage::create([
            'channel_id' => $channelId,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return response()->json(['message' => $message->load('user')]);
    }

    /**
     * Get messages for a channel
     */
    public function getMessages($channelId)
    {
        $channel = CommunityChannel::findOrFail($channelId);

        // Check if user is a member of the server
        CommunityMember::where('server_id', $channel->server_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $messages = VoiceChatMessage::with('user')
            ->where('channel_id', $channelId)
            ->orderBy('created_at', 'asc')
            ->limit(100)
            ->get();

        return response()->json(['messages' => $messages]);
    }

    /**
     * Update voice session state (mute/deafen)
     */
    public function updateVoiceSession(Request $request, $channelId)
    {
        $request->validate([
            'is_muted' => 'boolean',
            'is_deafened' => 'boolean',
        ]);

        $session = VoiceSession::where('channel_id', $channelId)
            ->where('user_id', Auth::id())
            ->whereNull('left_at')
            ->firstOrFail();

        $session->update([
            'is_muted' => $request->is_muted ?? $session->is_muted,
            'is_deafened' => $request->is_deafened ?? $session->is_deafened,
        ]);

        return response()->json(['session' => $session]);
    }

    /**
     * Get active users in a voice channel
     */
    public function getVoiceChannelUsers($channelId)
    {
        $sessions = VoiceSession::with('user')
            ->where('channel_id', $channelId)
            ->whereNull('left_at')
            ->get();

        return response()->json(['users' => $sessions]);
    }

    /**
     * WebRTC signaling - Send offer/answer/ICE candidate
     */
    public function sendSignal(Request $request, $channelId)
    {
        $request->validate([
            'type' => 'required|in:offer,answer,ice-candidate,join,leave',
            'data' => 'required',
            'target_user_id' => 'nullable',
        ]);

        $channel = CommunityChannel::findOrFail($channelId);
        
        // Verify user is in voice channel
        $session = VoiceSession::where('channel_id', $channelId)
            ->where('user_id', Auth::id())
            ->whereNull('left_at')
            ->firstOrFail();

        $type = $request->type;
        $data = is_string($request->data) ? $request->data : json_encode($request->data);
        $targetUserId = $request->target_user_id ?: null;

        // Store signal in database for polling (simple approach)
        DB::table('voice_signals')->insert([
            'channel_id' => $channelId,
            'sender_id' => Auth::id(),
            'target_user_id' => $targetUserId,
            'type' => $type,
            'data' => $data,
            'created_at' => now(),
        ]);

        // Also broadcast via WebSocket if available
        try {
            broadcast(new VoiceSignalingEvent(
                $channelId,
                $targetUserId ?? Auth::id(),
                $type,
                $data,
                Auth::id()
            ));
        } catch (\Exception $e) {
            // Broadcasting might fail if not configured
            logger('Broadcasting failed: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Signal sent']);
    }

    /**
     * Get pending signals for the current user
     */
    public function getSignals(Request $request, $channelId)
    {
        $channel = CommunityChannel::findOrFail($channelId);
        
        // Verify user is in voice channel
        VoiceSession::where('channel_id', $channelId)
            ->where('user_id', Auth::id())
            ->whereNull('left_at')
            ->firstOrFail();

        $afterId = (int) $request->query('after', 0);

        // Get signals meant for this user or broadcast to all
        $signals = DB::table('voice_signals')
            ->where('channel_id', $channelId)
            ->where('id', '>', $afterId)
            ->where(function ($query) {
                $query->where('target_user_id', Auth::id())
                    ->orWhereNull('target_user_id');
            })
            ->where('sender_id', '!=', Auth::id())
            ->where('created_at', '>', now()->subMinutes(5))
            ->orderBy('id', 'asc')
            ->get();

        // Delete old signals to prevent buildup
        DB::table('voice_signals')
            ->where('created_at', '<', now()->subMinutes(10))
            ->delete();

        return response()->json(['signals' => $signals]);
    }

    /**
     * Get WebRTC signaling information for a user
     */
    public function getSignalInfo($channelId, $userId)
    {
        $channel = CommunityChannel::findOrFail($channelId);
        
        // Verify user is in voice channel
        VoiceSession::where('channel_id', $channelId)
            ->where('user_id', Auth::id())
            ->whereNull('left_at')
            ->firstOrFail();

        // Get the target user's session info
        $targetSession = VoiceSession::where('channel_id', $channelId)
            ->where('user_id', $userId)
            ->whereNull('left_at')
            ->with('user')
            ->first();

        if (!$targetSession) {
            return response()->json(['error' => 'User not in voice channel'], 404);
        }

        return response()->json([
            'user' => $targetSession->user,
            'session_id' => $targetSession->session_id,
        ]);
    }
}