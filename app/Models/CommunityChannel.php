<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityChannel extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_id',
        'name',
        'type',
        'description',
        'position',
        'is_private',
    ];

    public function server()
    {
        return $this->belongsTo(CommunityServer::class, 'server_id');
    }

    public function voiceSessions()
    {
        return $this->hasMany(VoiceSession::class, 'channel_id');
    }

    public function messages()
    {
        return $this->hasMany(VoiceChatMessage::class, 'channel_id');
    }
}
