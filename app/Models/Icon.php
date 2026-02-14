<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Icon extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'emoji',
        'svg_path',
        'tier',
        'category',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    // Relationships

    public function qrSlots(): HasMany
    {
        return $this->hasMany(QrSlot::class);
    }

    // Scopes

    public function scopeTier($query, string $tier)
    {
        return $query->where('tier', $tier);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFree($query)
    {
        return $query->where('tier', 'free');
    }

    public function scopePro($query)
    {
        return $query->where('tier', 'pro');
    }

    // Accessors

    public function getSvgUrlAttribute(): ?string
    {
        return $this->svg_path ? asset($this->svg_path) : null;
    }
}
