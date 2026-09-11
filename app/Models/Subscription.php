<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'plan',
        'status',
        'description',  
        'price',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be cast to dates.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'price'      => 'integer',
    ];

    /**
     * Get the user that owns the subscription.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if subscription is active.
     */
    public function isActive()
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->plan === 'monthly' && $this->end_date && $this->end_date < now()) {
            return false;
        }

        return true;
    }

    /**
     * Determine if subscription is lifetime.
     */
    public function isLifetime()
    {
        return $this->plan === 'lifetime';
    }
}