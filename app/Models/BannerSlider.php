<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerSlider extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'title',
        'slug',
        'image_path',
        'price',
        'original_price',
        'display_order',
        'is_active'
    ];
    
    protected $attributes = [
        'game_id' => null,
        'is_active' => true,
        'display_order' => 1
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_active' => 'boolean',
        'display_order' => 'integer'
    ];

    /**
     * Relationship with Game model
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Scope for active sliders
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered sliders
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    /**
     * Get all active slider games in order
     */
    public static function getActiveSliders()
    {
        return self::active()->ordered()->get();
    }

    /**
     * Get slider games for banner display
     */
    public static function getBannerSliders()
    {
        $items = self::getActiveSliders()->map(function($slider) {
            $imagePath = $slider->image_path ? 'storage/' . ltrim($slider->image_path, '/') : 'images/placeholder.jpg';
            return [
                'slug' => $slider->slug,
                'image' => asset($imagePath),
                'title' => $slider->title,
                'old_price' => $slider->original_price ?: $slider->price,
                'new_price' => $slider->price
            ];
        })->toArray();

        // If no sliders configured, show message or use featured games as fallback
        if (count($items) === 0) {
            // Get featured games as fallback
            $featuredGames = \App\Models\Game::featured()->active()->take(3)->get();
            foreach ($featuredGames as $game) {
                $items[] = [
                    'slug' => $game->slug,
                    'image' => $game->image_url,
                    'title' => $game->title,
                    'old_price' => $game->original_price ?: $game->price,
                    'new_price' => $game->price
                ];
            }
        }

        return $items;
    }

    /**
     * Update slider with game data
     */
    public function updateFromGame(Game $game)
    {
        $this->update([
            'title' => $game->title,
            'slug' => $game->slug,
            'image_path' => $game->image_path,
            'price' => $game->price,
            'original_price' => $game->original_price,
        ]);
    }

    /**
     * Create or update slider from game
     */
    public static function createOrUpdateFromGame(Game $game, $displayOrder)
    {
        // Check if we already have 3 sliders and this is a new one
        $existingSlider = self::where('display_order', $displayOrder)->first();
        if (!$existingSlider && self::count() >= 3) {
            throw new \Exception('Maximum 3 slider banners allowed!');
        }
        
        return self::updateOrCreate(
            ['display_order' => $displayOrder],
            [
                'game_id' => $game->id,
                'title' => $game->title,
                'slug' => $game->slug,
                'image_path' => $game->image_path,
                'price' => $game->price,
                'original_price' => $game->original_price,
                'is_active' => true
            ]
        );
    }
    
    /**
     * Get next available display order
     */
    public static function getNextDisplayOrder()
    {
        $maxOrder = self::max('display_order') ?? 0;
        return min($maxOrder + 1, 3); // Maximum 3 positions
    }
    
    /**
     * Check if we can add more sliders
     */
    public static function canAddMore()
    {
        return self::count() < 3;
    }
}