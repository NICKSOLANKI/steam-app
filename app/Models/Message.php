<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'channel',
        'content',
    ];

    /**
     * The user who sent the message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
