<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id', 'game_title', 'game_image', 'price', 'quantity'
    ];
}
