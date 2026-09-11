<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityServer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'owner_id',
        'is_public',
        'max_members',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->hasMany(CommunityMember::class, 'server_id');
    }

    public function channels()
    {
        return $this->hasMany(CommunityChannel::class, 'server_id');
    }

    public function voiceSessions()
    {
        return $this->hasManyThrough(VoiceSession::class, CommunityChannel::class);
    }
}
