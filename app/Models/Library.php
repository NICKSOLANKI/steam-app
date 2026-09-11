<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_title',
        'game_image',
        'price',
    ];

    /**
     * Get the user that owns the library item
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
