<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoiceSignalingEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $channelId;
    public $userId;
    public $type; // 'offer', 'answer', 'ice-candidate', 'join', 'leave'
    public $data;
    public $senderId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($channelId, $userId, $type, $data, $senderId)
    {
        $this->channelId = $channelId;
        $this->userId = $userId;
        $this->type = $type;
        $this->data = $data;
        $this->senderId = $senderId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('voice-channel.' . $this->channelId);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'voice.signaling';
    }
}
