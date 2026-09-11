<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_id',
        'user_id',
        'role',
        'nickname',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function server()
    {
        return $this->belongsTo(CommunityServer::class, 'server_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
