<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'duration_days',
        'is_active',
        'features',
        'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'features' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope to get only active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get plans ordered by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Check if this is a lifetime plan
     */
    public function isLifetime()
    {
        return is_null($this->duration_days);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 0);
    }

    /**
     * Get duration text
     */
    public function getDurationTextAttribute()
    {
        if ($this->isLifetime()) {
            return 'Lifetime';
        }
        
        if ($this->duration_days == 30) {
            return '1 Month';
        } elseif ($this->duration_days == 365) {
            return '1 Year';
        } else {
            return $this->duration_days . ' Days';
        }
    }

    /**
     * Relationship with subscriptions
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan', 'slug');
    }
}