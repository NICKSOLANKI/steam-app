<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements AuthenticatableContract
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'temp_password',  // Temporary password for admin viewing only
        'role',           // user or admin
        'avatar',         // Avatar image path
        'profile_bg',     // Profile background image path
        'mini_profile',   // Mini profile image path
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',        // hide hashed password
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * User's library (games owned)
     * One user can have many library items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function libraries()
    {
        return $this->hasMany(\App\Models\Library::class);
    }

    /**
     * Community servers owned by this user
     */
    public function ownedServers()
    {
        return $this->hasMany(CommunityServer::class, 'owner_id');
    }

    /**
     * Community servers this user is a member of
     */
    public function communityMemberships()
    {
        return $this->hasMany(CommunityMember::class, 'user_id');
    }

    /**
     * All servers this user is a member of
     */
    public function servers()
    {
        return $this->belongsToMany(CommunityServer::class, 'community_members', 'user_id', 'server_id');
    }

    /**
     * Voice sessions for this user
     */
    public function voiceSessions()
    {
        return $this->hasMany(VoiceSession::class, 'user_id');
    }

    /**
     * Messages sent by this user in community channels
     */
    public function voiceChatMessages()
    {
        return $this->hasMany(VoiceChatMessage::class, 'user_id');
    }
}