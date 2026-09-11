<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Voice channel authorization
Broadcast::channel('voice-channel.{channelId}', function ($user, $channelId) {
    // Check if user is a member of the server that contains this channel
    $channel = \App\Models\CommunityChannel::find($channelId);
    if (!$channel) {
        return false;
    }

    $member = \App\Models\CommunityMember::where('server_id', $channel->server_id)
        ->where('user_id', $user->id)
        ->first();

    return $member !== null;
});
