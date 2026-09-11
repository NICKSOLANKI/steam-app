<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoiceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_id',
        'user_id',
        'session_id',
        'is_muted',
        'is_deafened',
        'joined_at',
        'left_at',
    ];

    protected $casts = [
        'is_muted' => 'boolean',
        'is_deafened' => 'boolean',
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
    ];

    public function channel()
    {
        return $this->belongsTo(CommunityChannel::class, 'channel_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
