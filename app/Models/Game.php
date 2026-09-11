<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory;

    protected $appends = ['image_url'];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'original_price',
        'genre',
        'developer',
        'release_date',
        'trailer_url',
        'tags',
        'supports_windows',
        'supports_controller',
        'is_single_player',
        'min_requirements',
        'rec_requirements',
        'image_path',
        'is_featured',
        'is_active'
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'release_date' => 'date',
        'supports_windows' => 'boolean',
        'supports_controller' => 'boolean',
        'is_single_player' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean'
    ];

    // -------------------------
    // --- Accessors ---
    // -------------------------

    public function getDiscountAttribute(): int
    {
        if ($this->original_price && $this->original_price > $this->price) {
            return round((($this->original_price - $this->price) / $this->original_price) * 100);
        }
        return 0;
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image_path ? asset('storage/' . ltrim($this->image_path, '/')) : asset('images/placeholder.jpg');
    }

    public function getFormattedPriceAttribute(): string
    {
        return '₹' . number_format($this->price, 2);
    }

    public function getFormattedOriginalPriceAttribute(): ?string
    {
        return $this->original_price ? '₹' . number_format($this->original_price, 2) : null;
    }

    public function getShortDescriptionAttribute(): string
    {
        return strlen($this->description) > 100 ? substr($this->description, 0, 100) . '...' : $this->description;
    }

    public function getFullTitleAttribute(): string
    {
        return $this->title . ' (' . ucfirst($this->genre) . ')';
    }

    // -------------------------
    // --- Mutators ---
    // -------------------------

    public function setTitleAttribute(string $value): void
    {
        $this->attributes['title'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function setImagePathAttribute($file)
    {
        if ($file) {
            if (!empty($this->attributes['image_path'])) {
                Storage::disk('public')->delete($this->attributes['image_path']);
            }

            if (is_object($file) && method_exists($file, 'store')) {
                $path = $file->store('games', 'public');
            } else {
                $path = $file;
            }

            $this->attributes['image_path'] = $path;
        }
    }

    public function setIsActiveAttribute($value)
    {
        $this->attributes['is_active'] = (bool) $value;
    }

    public function setIsFeaturedAttribute($value)
    {
        $this->attributes['is_featured'] = (bool) $value;
    }

    // -------------------------
    // --- Scopes ---
    // -------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOfGenre($query, string $genre)
    {
        return $query->where('genre', $genre);
    }

    public function scopePriceBetween($query, float $min, float $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    // -------------------------
    // --- Route Model Binding ---
    // -------------------------

    public function getRouteKeyName()
    {
        if (request()->is('admin/*')) {
            return 'id';
        }
        return 'slug';
    }

    // -------------------------
    // --- Helper Methods ---
    // -------------------------

    public function toggleActive(): void
    {
        $this->is_active = !$this->is_active;
        $this->save();
    }

    public function toggleFeatured(): void
    {
        $this->is_featured = !$this->is_featured;
        $this->save();
    }

    public function isDiscounted(): bool
    {
        return $this->original_price && $this->original_price > $this->price;
    }

    public function deleteWithImage(): bool
    {
        if ($this->image_path) {
            Storage::disk('public')->delete($this->image_path);
        }
        return $this->delete();
    }

    // -------------------------
    // --- Admin/Frontend Helpers ---
    // -------------------------

    public function toArrayForAdmin(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'genre' => $this->genre,
            'description' => $this->description,
            'price' => $this->price,
            'original_price' => $this->original_price,
            'image_path' => $this->image_path,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'image_url' => $this->imageUrl
        ];
    }

    // -------------------------
    // --- Banner / Slider Relationship ---
    // -------------------------

    public function banners()
    {
        return $this->hasMany(BannerSlider::class, 'game_id', 'id');
    }

    public function getActiveBanners()
    {
        return $this->banners()->where('is_active', true)->orderBy('id', 'desc')->get();
    }

    public function getBannerImagesArray()
    {
        $images = [];
        foreach($this->getActiveBanners() as $banner){
            $images[] = $banner->image_path ? Storage::disk('public')->url($banner->image_path) : asset('images/placeholder.jpg');
        }
        return $images;
    }

    public function getFirstBannerImageAttribute()
    {
        $first = $this->getActiveBanners()->first();
        return $first ? Storage::disk('public')->url($first->image_path) : asset('images/placeholder.jpg');
    }

    // -------------------------
    // --- Frontend Store Helpers ---
    // -------------------------

    public static function getFeaturedGames($limit = 8)
    {
        return self::featured()->active()->latest()->take($limit)->get();
    }

    public static function getLatestGames($limit = 8)
    {
        return self::active()->latest()->take($limit)->get();
    }

    public static function getGamesByGenre($genre, $limit = 8)
    {
        return self::active()->ofGenre($genre)->latest()->take($limit)->get();
    }
}